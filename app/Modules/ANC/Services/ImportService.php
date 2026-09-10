<?php

namespace App\Modules\ANC\Services;

use App\Modules\ANC\Models\AncPatient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ImportService
{
    /**
     * Universal preview: reads any Puskesmas ANC/KIA Excel or CSV.
     */
    public function previewFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();

        $formatInfo  = $this->detectFormatAndHeaders($sheet);
        $previewRows = [];
        $totalRows   = 0;

        $highestRow = $sheet->getHighestRow();
        $startRow   = $formatInfo['data_start_row'];
        $columnMap  = $formatInfo['column_map'];

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseRow($sheet, $rowNum, $columnMap, $formatInfo);
            if ($parsed !== null) {
                $totalRows++;
                if (count($previewRows) < 10) {
                    $previewRows[] = $parsed;
                }
            }
        }

        return [
            'type'            => $formatInfo['type'],
            'type_label'      => $formatInfo['label'],
            'fasyankes_name'  => $formatInfo['fasyankes_name'],
            'fasyankes_code'  => $formatInfo['fasyankes_code'],
            'period'          => $formatInfo['period'],
            'total_rows'      => $totalRows,
            'preview_samples' => $previewRows,
            'detected_fields' => array_keys($columnMap),
            'header_row'      => $formatInfo['header_row'],
        ];
    }

    /**
     * Universal import/upsert: persists all rows from any ANC/KIA Excel or CSV.
     */
    public function importFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();

        $formatInfo    = $this->detectFormatAndHeaders($sheet);
        $highestRow    = $sheet->getHighestRow();
        $startRow      = $formatInfo['data_start_row'];
        $columnMap     = $formatInfo['column_map'];

        $insertedCount = 0;
        $updatedCount  = 0;

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseRow($sheet, $rowNum, $columnMap, $formatInfo);
            if ($parsed === null) {
                continue;
            }

            if (empty($parsed['fasyankes_name']) && !empty($formatInfo['fasyankes_name'])) {
                $parsed['fasyankes_name'] = $formatInfo['fasyankes_name'];
            }
            if (empty($parsed['fasyankes_code']) && !empty($formatInfo['fasyankes_code'])) {
                $parsed['fasyankes_code'] = $formatInfo['fasyankes_code'];
            }

            // Auto compute Poedji Rochjati risk
            $assessment = RiskAssessmentService::calculate($parsed);
            $parsed['skor_poedji_rochjati']     = $assessment['skor'];
            $parsed['kategori_poedji_rochjati'] = $assessment['kategori'];
            $parsed['rekomendasi_faskes']       = $assessment['rekomendasi_faskes'];
            if (empty($parsed['status_risti'])) {
                $parsed['status_risti'] = $assessment['kategori'] === 'KRR' ? 'Normal' : 'Risiko Tinggi';
            }

            // Upsert strategy: NIK → nama+kelurahan
            $existing = null;
            if (!empty($parsed['nik'])) {
                $existing = AncPatient::where('nik', $parsed['nik'])->first();
            }
            if (!$existing && !empty($parsed['nama_lengkap']) && !empty($parsed['kelurahan'])) {
                $existing = AncPatient::where('nama_lengkap', $parsed['nama_lengkap'])
                    ->where('kelurahan', $parsed['kelurahan'])
                    ->first();
            }

            if ($existing) {
                $existing->update($parsed);
                $updatedCount++;
            } else {
                AncPatient::create($parsed);
                $insertedCount++;
            }
        }

        return [
            'type'       => $formatInfo['type'],
            'type_label' => $formatInfo['label'],
            'inserted'   => $insertedCount,
            'updated'    => $updatedCount,
            'total'      => $insertedCount + $updatedCount,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // INTERNAL: Header Detection & Parsing
    // ──────────────────────────────────────────────────────────────────────

    protected function detectFormatAndHeaders($sheet): array
    {
        $highestRow    = min(35, $sheet->getHighestRow());
        $highestColStr = $sheet->getHighestColumn();
        $highestCol    = min(120, Coordinate::columnIndexFromString($highestColStr));

        $fasyankesName = '';
        $fasyankesCode = '';
        $period        = '';

        // 1. Metadata from top rows
        for ($r = 1; $r <= 15; $r++) {
            for ($c = 1; $c <= min(10, $highestCol); $c++) {
                $val = trim((string) $sheet->getCell([$c, $r])->getValue());
                if (empty($val)) {
                    continue;
                }

                if (stripos($val, 'Nama Fasyankes') !== false || stripos($val, 'Nama Puskesmas') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) { $fasyankesName = $nv; break; }
                    }
                } elseif (stripos($val, 'Kode Fasyankes') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) { $fasyankesCode = $nv; break; }
                    }
                } elseif (stripos($val, 'Periode') !== false || stripos($val, 'Bulan') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) { $period = $nv; break; }
                    }
                }
            }
        }

        // 2. Find primary header row
        $primaryKeywords = ['nik', 'ktp', 'nama', 'ibu', 'hamil', 'hpht', 'hpl', 'gravida', 'kunjungan', 'anc', 'bumil'];
        $firstHeaderRow  = 1;
        $maxScore        = 0;

        for ($r = 1; $r <= $highestRow; $r++) {
            $score = 0;
            for ($c = 1; $c <= $highestCol; $c++) {
                $val = trim((string) $sheet->getCell([$c, $r])->getValue());
                if (empty($val) || preg_match('/^\(\d+\)$/', $val)) {
                    continue;
                }
                foreach ($primaryKeywords as $kw) {
                    if (stripos($val, $kw) !== false) {
                        $score += 3;
                    }
                }
            }
            if ($score > $maxScore) {
                $maxScore       = $score;
                $firstHeaderRow = $r;
            }
        }

        // 3. Find data start row (first row after header that looks like data, e.g. numeric ID, NIK, or standard value)
        $dataStartRow = $firstHeaderRow + 1;
        for ($r = $firstHeaderRow + 1; $r <= $highestRow; $r++) {
            $hasData = false;
            $isIndexRow = false;
            for ($c = 1; $c <= min(12, $highestCol); $c++) {
                $v = trim((string) $sheet->getCell([$c, $r])->getValue());
                if (preg_match('/^\(\d+\)$/', $v)) {
                    $isIndexRow = true;
                    break;
                }
                // Check if row has data signs: sequential number like "1.", 16-digit NIK, or numeric date
                if ($v !== '' && (preg_match('/^\d+\.?$/', $v) || preg_match('/^\d{16}/', $v) || (is_numeric($v) && (float)$v > 20000))) {
                    $hasData = true;
                }
            }
            if ($isIndexRow) {
                continue;
            }
            if ($hasData) {
                $dataStartRow = $r;
                break;
            }
        }

        // 4. Merge only actual header rows (from $firstHeaderRow up to $dataStartRow - 1)
        $mergedHeaders = [];
        $headerEndRow  = max($firstHeaderRow, $dataStartRow - 1);

        for ($c = 1; $c <= $highestCol; $c++) {
            $texts = [];
            for ($r = $firstHeaderRow; $r <= $headerEndRow; $r++) {
                $val = trim((string) $sheet->getCell([$c, $r])->getValue());
                if ($val !== '' && !preg_match('/^\(\d+\)$/', $val)) {
                    $texts[] = $val;
                }
            }
            if (!empty($texts)) {
                $mergedHeaders[$c] = implode(' ', array_unique($texts));
            }
        }

        // 5. Build column map
        $columnMap = $this->buildColumnMap($mergedHeaders);

        // 6. Detect report type
        $titleBlock = '';
        for ($r = 1; $r <= 6; $r++) {
            for ($c = 1; $c <= 10; $c++) {
                $titleBlock .= ' ' . (string) $sheet->getCell([$c, $r])->getValue();
            }
        }

        $type  = 'anc_generic';
        $label = 'Data Kunjungan ANC / Ibu Hamil';

        if (stripos($titleBlock, 'KOHORT') !== false || stripos($titleBlock, 'KOHOR') !== false) {
            $type  = 'anc_kohort';
            $label = 'Kohort Ibu Hamil (Register ANC)';
        } elseif (stripos($titleBlock, 'K1') !== false || stripos($titleBlock, 'K4') !== false) {
            $type  = 'anc_kunjungan';
            $label = 'Register Kunjungan ANC (K1-K6)';
        } elseif (stripos($titleBlock, 'PWS') !== false) {
            $type  = 'anc_pws';
            $label = 'Pemantauan Wilayah Setempat (PWS-KIA)';
        }

        return [
            'type'          => $type,
            'label'         => $label,
            'fasyankes_name' => $fasyankesName ?: 'Puskesmas',
            'fasyankes_code' => $fasyankesCode,
            'period'        => $period,
            'header_row'    => $firstHeaderRow,
            'data_start_row' => $dataStartRow,
            'column_map'    => $columnMap,
        ];
    }

    protected function buildColumnMap(array $headers): array
    {
        $map = [];

        $fieldRules = [
            'bulan_kunjungan'          => ['/bulan\s+kunjungan/i', '/bln\s+kunjungan/i'],
            'nama_suami'               => ['/nama\s+suami/i', '/suami/i'],
            'nama_lengkap'             => ['/nama\s+ibu/i', '/nama\s+lengkap/i', '/nama\s+pasien/i', '/^nama$/i'],
            'nik'                      => ['/nomor\s+induk\s+kependudukan/i', '/\(nik\)/i', '/^nik/i', '/nik/i', '/no\.?\s*ktp/i'],
            'no_telepon'               => ['/telp/i', '/telepon/i', '/no\.?\s*hp/i', '/kontak/i', '/wa\b/i'],
            'no_rekam_medis'           => ['/no\.?\s*rekam\s+medis/i', '/rekam\s+medis/i', '/no\.?\s*rm/i'],
            'no_reg_fasyankes'         => ['/no\.?\s*register/i', '/nomor\s+register/i', '/no\.\s*urut/i'],
            'no_bpjs'                  => ['/bpjs/i', '/no\.?\s*peserta/i', '/jaminan\s+kesehatan/i'],
            'tanggal_lahir'            => ['/tgl\s+lahir/i', '/tanggal\s+lahir/i', '/birth\s*date/i'],
            'umur'                     => ['/umur\s+ibu/i', '/usia\s+ibu/i', '/umur/i', '/usia/i'],
            'pekerjaan'                => ['/pekerjaan/i', '/status\s+kerja/i'],
            'pendidikan'               => ['/pendidikan/i', '/tingkat\s+pendidikan/i'],
            'kelurahan'                => ['/kelurahan\s+ibu/i', '/desa\s*\/\s*kel/i', '/kelurahan\/desa/i', '/kelurahan/i', '/desa/i'],
            'kecamatan'                => ['/kecamatan\s+ibu/i', '/kecamatan/i'],
            'kabupaten'                => ['/kabupaten\/kota/i', '/kabupaten/i', '/kota/i'],
            'provinsi'                 => ['/provinsi/i'],
            'alamat_lengkap'           => ['/alamat\s+lengkap/i', '/alamat\s+ibu/i', '/alamat\s+rumah/i', '/alamat/i'],
            'golongan_darah'           => ['/gol\.?\s*darah/i', '/golongan\s+darah/i'],
            'gpa'                      => ['/g\.?p\.?a/i', '/g-p-a/i'],
            'gravida'                  => ['/gravida/i', '/g\s*\//i', '/jumlah\s+kehamilan/i'],
            'para'                     => ['/para/i', '/paritas/i', '/jumlah\s+persalinan/i'],
            'abortus'                  => ['/abortus/i', '/keguguran/i', '/ab/i'],
            'usia_kehamilan'           => ['/usia\s+kehamilan/i', '/usia\s+hamil/i', '/uk\b/i', '/trimester/i', '/minggu\s+kehamilan/i', '/usia.*kunjungan/i'],
            'hpht'                     => ['/tgl\s+hpht/i', '/hpht/i', '/hari\s+pertama\s+haid/i', '/tanggal\s+hpht/i'],
            'hpl'                      => ['/^tp$/i', '/^hpl$/i', '/tgl\s+hpl/i', '/hpl/i', '/hpl\s*\/\s*tp/i', '/tp\s*\/\s*hpl/i', '/taksiran\s+persalinan/i', '/perkiraan\s+lahir/i', '/tanggal\s+perkiraan/i'],
            'tanggal_kunjungan'        => ['/tanggal\s+kunjungan/i', '/tgl\s+kunjungan/i', '/tanggal\s+periksa/i'],
            'kunjungan_ke'             => ['/kunjungan\s+ke/i', '/k1|k2|k3|k4|k5|k6/i', '/kunjungan\s+anc/i'],
            'jenis_kunjungan'          => ['/jenis\s+kunjungan/i', '/tipe\s+kunjungan/i'],
            'berat_badan'              => ['/berat\s+badan/i', '/bb\s+ibu/i', '/bb\b/i'],
            'tinggi_badan'             => ['/tinggi\s+badan/i', '/tb\s+ibu/i', '/tinggi\s+ibu/i'],
            'lila'                     => ['/lila/i', '/lingkar\s+lengan/i'],
            'tekanan_darah_sistolik'   => ['/tekanan\s+darah\s+sistolik/i', '/sistolik/i', '/sistol/i'],
            'tekanan_darah_diastolik'  => ['/tekanan\s+darah\s+diastolik/i', '/diastolik/i', '/diastol/i'],
            'tinggi_fundus_uteri'      => ['/tinggi\s+fundus/i', '/tfu/i', '/fundus\s+uteri/i'],
            'presentasi_janin'         => ['/presentasi/i', '/letak\s+janin/i', '/posisi\s+janin/i'],
            'denyut_jantung_janin'     => ['/denyut\s+jantung\s+janin/i', '/djj/i', '/detak\s+jantung/i'],
            'status_imunisasi_tt'      => ['/imunisasi\s+tt/i', '/status\s+tt/i', '/tt\s+ibu/i'],
            'hb'                       => ['/hb\s+ibu/i', '/hemoglobin/i', '/kadar\s+hb/i', '/^hb$/i'],
            'status_anemia'            => ['/anemia/i', '/status\s+anemia/i'],
            'gds'                      => ['/gds/i', '/gula\s+darah\s+sewaktu/i', '/glukosa/i'],
            'protein_urine'            => ['/protein\s+urine/i', '/proteinuria/i', '/protein\s+urin/i'],
            'hbsag'                    => ['/hbsag/i', '/hepatitis\s+b/i'],
            'hiv_status'               => ['/hiv/i', '/status\s+hiv/i'],
            'sifilis_status'           => ['/sifilis/i', '/sipilis/i'],
            'mendapat_fe'              => ['/\bfe\b/i', '/tablet\s+fe/i', '/tambah\s+darah/i', '/ttd/i'],
            'mendapat_vit_a'           => ['/\bvit\.?\s*a\b/i', '/vitamin\s+a/i'],
            'p4k'                      => ['/\bp4k\b/i', '/perencanaan\s+persalinan/i'],
            'faktor_risiko'            => ['/faktor\s+risiko/i', '/faktor\s+resiko/i'],
            'risiko_tinggi'            => ['/risiko\s+tinggi/i', '/risti/i', '/risiko\s+sangat\s+tinggi/i'],
            'status_risti'             => ['/status\s+risti/i', '/kategori\s+risiko/i'],
            'dirujuk_ke'               => ['/dirujuk\s+ke/i', '/tujuan\s+rujukan/i'],
            'alasan_rujukan'           => ['/alasan\s+rujukan/i', '/indikasi\s+rujukan/i'],
            'status_kehamilan'         => ['/status\s+kehamilan/i', '/outcome\s+kehamilan/i', '/hasil\s+kehamilan/i'],
            'tempat_bersalin'          => ['/tempat\s+bersalin/i', '/tempat\s+melahirkan/i'],
            'penolong_persalinan'      => ['/penolong\s+persalinan/i', '/penolong\s+kelahiran/i'],
            'tanggal_bersalin'         => ['/tanggal\s+bersalin/i', '/tgl\s+bersalin/i', '/tanggal\s+melahirkan/i'],
            'komplikasi_persalinan'    => ['/komplikasi/i', '/penyulit\s+persalinan/i'],
            'kondisi_bayi'             => ['/kondisi\s+bayi/i', '/keadaan\s+bayi/i', '/bayi\s+lahir/i'],
            'berat_lahir_bayi'         => ['/berat\s+lahir/i', '/bbl/i', '/berat\s+bayi/i'],
            'catatan'                  => ['/catatan/i', '/keterangan/i', '/notes/i'],
        ];

        foreach ($fieldRules as $field => $patterns) {
            foreach ($headers as $colIdx => $headerText) {
                if (isset($map[$field])) {
                    break;
                }
                if (in_array($colIdx, $map)) {
                    continue;
                }
                if (in_array($field, ['kabupaten', 'provinsi', 'kelurahan', 'kecamatan'])) {
                    if (stripos($headerText, 'Faskes') !== false || stripos($headerText, 'Fasyankes') !== false) {
                        continue;
                    }
                }
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $headerText)) {
                        $map[$field] = $colIdx;
                        break 2;
                    }
                }
            }
        }

        // Fallback: any "nama" column that is NOT suami and not yet mapped
        if (!isset($map['nama_lengkap'])) {
            foreach ($headers as $colIdx => $headerText) {
                if (!in_array($colIdx, $map) && stripos($headerText, 'nama') !== false && stripos($headerText, 'suami') === false && stripos($headerText, 'fasyankes') === false) {
                    $map['nama_lengkap'] = $colIdx;
                    break;
                }
            }
        }

        // ── Explicit fallbacks (stripos-based) for columns with non-standard headers ──

        // HPL / Taksiran Persalinan
        if (!isset($map['hpl'])) {
            foreach ($headers as $colIdx => $headerText) {
                if (in_array($colIdx, $map)) continue;
                if (stripos($headerText, 'hpl') !== false
                    || stripos($headerText, 'perkiraan') !== false
                    || stripos($headerText, 'taksiran') !== false
                    || strtoupper(trim($headerText)) === 'TP') {
                    $map['hpl'] = $colIdx;
                    break;
                }
            }
        }

        // Usia Kehamilan (handle "Usia Saat Hamil", "Usia Hamil", dll)
        if (!isset($map['usia_kehamilan'])) {
            foreach ($headers as $colIdx => $headerText) {
                if (in_array($colIdx, $map)) continue;
                $lower = strtolower($headerText);
                if ((strpos($lower, 'usia') !== false || strpos($lower, 'umur') !== false)
                    && (strpos($lower, 'kehamil') !== false || strpos($lower, 'hamil') !== false)
                    && strpos($lower, 'ibu') === false) {
                    $map['usia_kehamilan'] = $colIdx;
                    break;
                }
            }
        }

        // Kecamatan (handle header singkat seperti "Kec")
        if (!isset($map['kecamatan'])) {
            foreach ($headers as $colIdx => $headerText) {
                if (in_array($colIdx, $map)) continue;
                $upper = strtoupper(trim($headerText));
                if ($upper === 'KEC' || $upper === 'KECAMATAN') {
                    $map['kecamatan'] = $colIdx;
                    break;
                }
            }
        }

        return $map;
    }

    protected function parseRow($sheet, int $rowNum, array $columnMap, array $formatInfo): ?array
    {
        $data = [];

        foreach ($columnMap as $field => $colIdx) {
            $data[$field] = $this->cleanVal($sheet->getCell([$colIdx, $rowNum])->getValue());
        }

        $nama = $data['nama_lengkap'] ?? null;
        $nik  = $data['nik'] ?? null;

        if (empty($nama) && empty($nik)) {
            return null;
        }

        // Filter header repeats
        if ($nama && (stripos($nama, 'Nama Ibu') !== false || stripos($nama, 'Nama Lengkap') !== false || preg_match('/^\(\d+\)$/', $nama))) {
            return null;
        }

        // Handle G.P.A composite column (e.g. "G1P0A0", "G2 P1 A0", "1-0-0")
        if (!empty($data['gpa'])) {
            $gpaStr = strtoupper(trim((string)$data['gpa']));
            if (preg_match('/G\s*(\d+)/i', $gpaStr, $m)) {
                $data['gravida'] = (int) $m[1];
            }
            if (preg_match('/P\s*(\d+)/i', $gpaStr, $m)) {
                $data['para'] = (int) $m[1];
            }
            if (preg_match('/A\s*(\d+)/i', $gpaStr, $m)) {
                $data['abortus'] = (int) $m[1];
            }
        }

        // Sanitize numeric fields
        foreach (['umur', 'gravida', 'para', 'abortus'] as $intField) {
            $raw = isset($data[$intField]) ? (string) $data[$intField] : '';
            if ($raw === '' || $raw === null) {
                $data[$intField] = null;
            } elseif (is_numeric($raw)) {
                $data[$intField] = (int) $raw;
            } else {
                // Extract leading integer from strings like "24 Tahun 10 Bulan 28 Hari", "G2", "P1"
                if (preg_match('/^[^\d]*(\d+)/u', trim($raw), $m)) {
                    $data[$intField] = (int) $m[1];
                } else {
                    $data[$intField] = null;
                }
            }
        }

        foreach (['berat_badan', 'tinggi_badan', 'lila', 'berat_lahir_bayi',
                  'tekanan_darah_sistolik', 'tekanan_darah_diastolik', 'tinggi_fundus_uteri'] as $floatField) {
            $raw = isset($data[$floatField]) ? (string) $data[$floatField] : '';
            if ($raw === '' || $raw === null) {
                $data[$floatField] = null;
            } else {
                $cleanFloat = str_replace(',', '.', $raw);
                // Strip any trailing non-numeric text (e.g. "79 kg", "151 cm")
                if (preg_match('/^[\s]*(\d+[\.,]?\d*)/u', $cleanFloat, $m)) {
                    $data[$floatField] = (float) str_replace(',', '.', $m[1]);
                } else {
                    $data[$floatField] = null;
                }
            }
        }

        // Parse Excel date serials or formatted dates for date fields
        foreach (['tanggal_lahir', 'hpht', 'hpl', 'tanggal_kunjungan', 'tanggal_bersalin'] as $dateField) {
            if (!empty($data[$dateField])) {
                $data[$dateField] = $this->parseDateVal($data[$dateField]);
            } else {
                $data[$dateField] = null;
            }
        }

        // Calculate umur from tanggal_lahir if umur is missing or zero
        if (($data['umur'] === null || $data['umur'] === 0) && !empty($data['tanggal_lahir'])) {
            try {
                $birthDate    = \Carbon\Carbon::parse($data['tanggal_lahir']);
                $data['umur'] = (int) $birthDate->age;
            } catch (\Exception $e) {}
        }

        // Boolean fields
        foreach (['mendapat_fe', 'mendapat_vit_a'] as $boolField) {
            $v = strtolower((string) ($data[$boolField] ?? ''));
            $data[$boolField] = in_array($v, ['ya', 'yes', '1', 'true', 'v', '✓', 'ada']);
        }

        // If faktor_risiko is detected (e.g. KEK), ensure status_risti is set appropriately
        if (!empty($data['faktor_risiko']) && empty($data['status_risti'])) {
            $fr = strtolower($data['faktor_risiko']);
            if ($fr === 'kek' || str_contains($fr, 'risti') || str_contains($fr, 'tinggi')) {
                $data['status_risti'] = 'Risiko Tinggi';
            }
        }

        // ── Normalize bulan_kunjungan (from Excel "BULAN KUNJUNGAN" column) ──────
        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanLower = strtolower(trim((string) ($data['bulan_kunjungan'] ?? '')));
        $normalized = null;
        foreach ($indonesianMonths as $num => $name) {
            if ($bulanLower === strtolower($name) || $bulanLower === (string) $num) {
                $normalized = $name;
                break;
            }
        }
        if ($normalized) {
            $data['bulan_kunjungan'] = $normalized;
        } else {
            $data['bulan_kunjungan'] = null;
        }

        // ── Auto-derive bulan (bulan laporan) — prioritas: bulan_kunjungan > tanggal_kunjungan > created_at ──
        if (empty($data['bulan'])) {
            if (!empty($data['bulan_kunjungan'])) {
                // Primary: gunakan bulan kunjungan dari kolom Excel
                $data['bulan'] = $data['bulan_kunjungan'];
            } else {
                // Fallback: derive dari tanggal_kunjungan
                $refDate = null;
                if (!empty($data['tanggal_kunjungan'])) {
                    $refDate = \Carbon\Carbon::parse($data['tanggal_kunjungan']);
                }
                if ($refDate && $refDate->year > 2000) {
                    $data['bulan'] = $indonesianMonths[(int)$refDate->format('n')] ?? null;
                    if (empty($data['tahun'])) {
                        $data['tahun'] = (int) $refDate->format('Y');
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Parse date value from either Excel numeric serial or date string
     */
    protected function parseDateVal($val): ?string
    {
        if ($val === null || trim((string)$val) === '') {
            return null;
        }

        $str = trim((string)$val);

        // Numeric Excel timestamp (days since 1900)
        if (is_numeric($str) && (float)$str > 20000 && (float)$str < 80000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$str)->format('Y-m-d');
            } catch (\Exception $e) {}
        }

        // Standard date string parsing
        try {
            return \Carbon\Carbon::parse($str)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function cleanVal($val): ?string
    {
        if ($val === null) {
            return null;
        }
        $str = preg_replace('/^[\s\x{00a0}\x{200b}]+|[\s\x{00a0}\x{200b}]+$/u', '', (string) $val);

        return $str === '' ? null : $str;
    }
}
