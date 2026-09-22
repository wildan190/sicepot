<?php

namespace App\Modules\TBC\Services;

use App\Modules\TBC\Models\TbPatient;
use App\Modules\TBC\Models\TbContactInvestigation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportService
{
    /**
     * Universal Header-driven Preview for any Puskesmas Excel / CSV file
     */
    public function previewFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $formatInfo = $this->detectFormatAndHeaders($sheet);

        if ($formatInfo['type'] === 'tb_16k') {
            return $this->previewTb16k($sheet, $formatInfo);
        }

        $previewRows = [];
        $totalRows = 0;

        $highestRow = $sheet->getHighestRow();
        $startRow = $formatInfo['data_start_row'];
        $columnMap = $formatInfo['column_map'];

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseRowUniversally($sheet, $rowNum, $columnMap, $formatInfo);
            if ($parsed !== null) {
                $totalRows++;
                if (count($previewRows) < 10) {
                    $previewRows[] = $parsed;
                }
            }
        }

        return [
            'type' => $formatInfo['type'],
            'type_label' => $formatInfo['label'],
            'fasyankes_name' => $formatInfo['fasyankes_name'],
            'fasyankes_code' => $formatInfo['fasyankes_code'],
            'period' => $formatInfo['period'],
            'total_rows' => $totalRows,
            'preview_samples' => $previewRows,
            'detected_fields' => array_keys($columnMap),
            'header_row' => $formatInfo['header_row'],
        ];
    }

    /**
     * Universal Header-driven Import/Upsert for any Puskesmas Excel / CSV file
     */
    public function importFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $formatInfo = $this->detectFormatAndHeaders($sheet);

        if ($formatInfo['type'] === 'tb_16k') {
            return $this->importTb16k($sheet, $formatInfo);
        }

        $highestRow = $sheet->getHighestRow();
        $startRow = $formatInfo['data_start_row'];
        $columnMap = $formatInfo['column_map'];

        $insertedCount = 0;
        $updatedCount = 0;

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseRowUniversally($sheet, $rowNum, $columnMap, $formatInfo);
            if ($parsed === null) {
                continue;
            }

            if (empty($parsed['fasyankes_name']) && !empty($formatInfo['fasyankes_name'])) {
                $parsed['fasyankes_name'] = $formatInfo['fasyankes_name'];
            }
            if (empty($parsed['fasyankes_code']) && !empty($formatInfo['fasyankes_code'])) {
                $parsed['fasyankes_code'] = $formatInfo['fasyankes_code'];
            }

            // Realtime Upsert: Check SITB or NIK
            $existing = null;
            if (!empty($parsed['no_reg_sitb'])) {
                $existing = TbPatient::where('no_reg_sitb', $parsed['no_reg_sitb'])
                    ->where('report_type', $parsed['report_type'])
                    ->first();
            }

            if (!$existing && !empty($parsed['nik'])) {
                $existing = TbPatient::where('nik', $parsed['nik'])
                    ->where('report_type', $parsed['report_type'])
                    ->when(!empty($parsed['no_reg_terduga']), function ($q) use ($parsed) {
                        return $q->where('no_reg_terduga', $parsed['no_reg_terduga']);
                    })
                    ->first();
            }

            if (!$existing && !empty($parsed['nama_lengkap']) && !empty($parsed['kelurahan'])) {
                $existing = TbPatient::where('nama_lengkap', $parsed['nama_lengkap'])
                    ->where('kelurahan', $parsed['kelurahan'])
                    ->where('report_type', $parsed['report_type'])
                    ->first();
            }

            if ($existing) {
                $existing->update($parsed);
                $updatedCount++;
            } else {
                TbPatient::create($parsed);
                $insertedCount++;
            }
        }

        return [
            'type' => $formatInfo['type'],
            'type_label' => $formatInfo['label'],
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
            'total' => $insertedCount + $updatedCount,
        ];
    }

    /**
     * Universal Format & Header Detector:
     * Inspects headers, titles, and layout dynamically.
     */
    protected function detectFormatAndHeaders($sheet): array
    {
        $highestRow = min(35, $sheet->getHighestRow());
        $highestColStr = $sheet->getHighestColumn();
        $highestCol = min(120, Coordinate::columnIndexFromString($highestColStr));

        $fasyankesName = '';
        $fasyankesCode = '';
        $period = '';

        // 1. Extract Puskesmas metadata from top 15 rows
        for ($r = 1; $r <= 15; $r++) {
            for ($c = 1; $c <= min(10, $highestCol); $c++) {
                $val = trim((string) $sheet->getCell([$c, $r])->getValue());
                if (empty($val)) {
                    continue;
                }

                if (stripos($val, 'Nama Fasyankes') !== false || stripos($val, 'Nama Puskesmas') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) {
                            $fasyankesName = $nv;
                            break;
                        }
                    }
                } elseif (stripos($val, 'Kode Fasyankes') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) {
                            $fasyankesCode = $nv;
                            break;
                        }
                    }
                } elseif (stripos($val, 'Periode') !== false) {
                    for ($k = $c + 1; $k <= min($c + 4, $highestCol); $k++) {
                        $nv = trim(ltrim((string) $sheet->getCell([$k, $r])->getValue(), ': '));
                        if (!empty($nv)) {
                            $period = $nv;
                            break;
                        }
                    }
                }
            }
        }

        // 2. Scan to find primary header row
        $primaryKeywords = ['nik', 'ktp', 'umur', 'usia', 'kelamin', 'no. urut', 'nama lengkap', 'rekam medis', 'nama pasien', 'terduga'];
        $firstHeaderRow = 1;
        $maxPrimaryScore = 0;

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

            if ($score > $maxPrimaryScore) {
                $maxPrimaryScore = $score;
                $firstHeaderRow = $r;
            }
        }

        // 3. Merged headers across header band
        $mergedHeaders = [];
        $headerScanLimit = min($firstHeaderRow + 4, $highestRow);

        for ($c = 1; $c <= $highestCol; $c++) {
            $texts = [];
            for ($r = $firstHeaderRow; $r <= $headerScanLimit; $r++) {
                $val = trim((string) $sheet->getCell([$c, $r])->getValue());
                if ($val !== '' && !preg_match('/^\(\d+\)$/', $val)) {
                    if (preg_match('/^\d{16}$/', $val) || preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $val)) {
                        break;
                    }
                    $texts[] = $val;
                }
            }
            if (!empty($texts)) {
                $mergedHeaders[$c] = implode(' ', array_unique($texts));
            }
        }

        // 4. Find data start row
        $dataStartRow = $firstHeaderRow + 1;
        for ($r = $firstHeaderRow + 1; $r <= $highestRow + 2; $r++) {
            $hasData = false;
            $isIndexRow = false;
            for ($c = 1; $c <= min(10, $highestCol); $c++) {
                $v = trim((string) $sheet->getCell([$c, $r])->getValue());
                if ($v === '(1)' || $v === '(2)') {
                    $isIndexRow = true;
                    break;
                }
                if ($v !== '' && is_numeric($v)) {
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

        // 5. Build adaptive column map
        $columnMap = $this->buildUniversalColumnMap($mergedHeaders);

        // 6. Detect type from top metadata or headers
        $titleBlock = '';
        for ($r = 1; $r <= 6; $r++) {
            for ($c = 1; $c <= 10; $c++) {
                $titleBlock .= ' ' . (string) $sheet->getCell([$c, $r])->getValue();
            }
        }
        $allHeaderStr = implode(' ', $mergedHeaders);
        $sheetTitle = $sheet->getTitle();
        $type = 'generic';
        $label = 'Format Universal Puskesmas';

        if (
            stripos($titleBlock, 'INVESTIGASI KONTAK') !== false ||
            stripos($titleBlock, 'TBC.16K') !== false ||
            stripos($sheetTitle, 'TBC.16K') !== false ||
            stripos($sheetTitle, 'INVESTIGASI') !== false ||
            stripos($allHeaderStr, 'Kasus Indeks') !== false ||
            stripos($allHeaderStr, 'Petugas yang menginvestigasi') !== false
        ) {
            $type = 'tb_16k';
            $label = 'Formulir Investigasi Kontak (TBC.16K)';
            $dataStartRow = 15;
        } elseif (stripos($titleBlock, 'REGISTER PASIEN TBC') !== false || stripos($titleBlock, 'TB.03') !== false || stripos($allHeaderStr, 'Paduan OAT') !== false) {
            $type = 'tb_03';
            $label = 'Register Pasien TBC (Standar TB-03 SO / SITB)';
        } elseif (stripos($titleBlock, 'REGISTER TERDUGA TBC') !== false || stripos($titleBlock, 'TB.06') !== false || stripos($allHeaderStr, 'Sediaan') !== false) {
            $type = 'tb_06';
            $label = 'Register Terduga TBC (Standar TB-06)';
        }

        return [
            'type' => $type,
            'label' => $label,
            'fasyankes_name' => $fasyankesName ?: 'Puskesmas',
            'fasyankes_code' => $fasyankesCode,
            'period' => $period,
            'header_row' => $firstHeaderRow,
            'data_start_row' => $dataStartRow,
            'column_map' => $columnMap,
        ];
    }

    /**
     * Map fuzzy column headers to canonical database fields.
     */
    protected function buildUniversalColumnMap(array $headers): array
    {
        $map = [];

        $fieldRules = [
            'nama_lengkap' => [
                '/nama\s+lengkap\s+pasien/i',
                '/nama\s+lengkap\s+terduga/i',
                '/nama\s+pasien/i',
                '/nama\s+terduga/i',
                '/nama\s+klien/i',
                '/nama\s+lengkap/i',
                '/patient\s+name/i',
                '/^nama$/i',
            ],
            'nik' => [
                '/nomor\s+identitas\s+kependudukan/i',
                '/\(nik\)/i',
                '/^nik/i',
                '/nik/i',
                '/no\.?\s*ktp/i',
                '/nomor\s+induk/i',
            ],
            'no_rekam_medis' => [
                '/no\.?\s*rekam\s+medis/i',
                '/rekam\s+medis/i',
                '/no\.?\s*rm/i',
                '/no_rm/i',
                '/medrec/i',
            ],
            'no_reg_sitb' => [
                '/nomor\s+register\s+sitb/i',
                '/no\.?\s*register\s+sitb/i',
                '/reg\s*sitb/i',
                '/no\.?\s*sitb/i',
            ],
            'no_reg_terduga' => [
                '/nomor\s+register\s+terduga/i',
                '/no\.?\s*register\s+terduga/i',
                '/no\.?\s*terduga/i',
            ],
            'no_reg_pasien' => [
                '/nomor\s+register\s+pasien/i',
                '/no\.?\s*register\s+pasien/i',
                '/no\.?\s*pasien/i',
            ],
            'no_bpjs' => [
                '/bpjs/i',
                '/no\.?\s*peserta/i',
                '/no\.?\s*kartu/i',
            ],
            'umur' => [
                '/umur/i',
                '/usia/i',
                '/age/i',
            ],
            'jenis_kelamin' => [
                '/jenis\s+kelamin/i',
                '/^jk$/i',
                '/gender/i',
                '/kelamin/i',
                '/^sex$/i',
            ],
            'pekerjaan' => [
                '/pekerjaan/i',
                '/status\s+kerja/i',
                '/occupation/i',
            ],
            'kelurahan' => [
                '/pasien.*kelurahan/i',
                '/terduga.*kelurahan/i',
                '/kelurahan\s*\/\s*desa/i',
                '/kelurahan/i',
                '/desa/i',
            ],
            'kecamatan' => [
                '/pasien.*kecamatan/i',
                '/terduga.*kecamatan/i',
                '/kecamatan/i',
            ],
            'kabupaten' => [
                '/pasien.*kabupaten/i',
                '/terduga.*kabupaten/i',
                '/alamat.*kabupaten/i',
                '/kabupaten\s*\/\s*kota/i',
                '/^kabupaten$/i',
                '/^kota$/i',
            ],
            'provinsi' => [
                '/pasien.*provinsi/i',
                '/terduga.*provinsi/i',
                '/alamat.*provinsi/i',
                '/^provinsi$/i',
            ],
            'alamat_lengkap' => [
                '/alamat\s+lengkap\s+pasien/i',
                '/alamat\s+lengkap\s+terduga/i',
                '/alamat\s+lengkap/i',
                '/alamat\s+pasien/i',
                '/alamat\s+domisili/i',
            ],
            'bulan' => [
                '/^bulan$/i',
                '/periode\s+bulan/i',
                '/month/i',
            ],
            'tanggal_daftar' => [
                '/tanggal\s+(didaftar|daftar|registrasi)/i',
                '/tgl\s+daftar/i',
            ],
            'tanggal_mulai_pengobatan' => [
                '/mulai\s+pengobatan/i',
                '/tgl\s+mulai/i',
            ],
            'status_pengobatan' => [
                '/status\s+pengobatan/i',
                '/status\s+terapi/i',
            ],
            'tipe_diagnosis' => [
                '/tipe\s+diagnosis/i',
                '/tipe\s+tbc/i',
            ],
            'lokasi_anatomi' => [
                '/lokasi\s+anatomi/i',
                '/anatomi/i',
            ],
            'riwayat_pengobatan' => [
                '/riwayat\s+pengobatan/i',
                '/tipe\s+pasien/i',
            ],
            'hasil_tcm' => [
                '/hasil\s+pemeriksaan\s+diagnosis\s+tbc/i',
                '/hasil.*tcm/i',
                '/tcm\s+xpert/i',
                '/gene\s*xpert/i',
            ],
            'hasil_mikroskopis' => [
                '/hasil\s+mikroskopis/i',
                '/mikroskopis/i',
                '/bta/i',
            ],
            'hasil_diagnosis' => [
                '/^hasil\s+diagnosis$/i',
                '/terkonfirmasi\s+tbc/i',
                '/status\s+diagnosis/i',
            ],
            'hasil_akhir_pengobatan' => [
                '/hasil\s+akhir\s+pengobatan/i',
                '/hasil\s+akhir/i',
                '/outcome/i',
                '/hasil\s+pengobatan/i',
            ],
            'status_hiv' => [
                '/status\s+hiv/i',
                '/hiv/i',
                '/odhiv/i',
            ],
            'riwayat_dm' => [
                '/riwayat\s+dm/i',
                '/diabetes/i',
                '/^dm$/i',
            ],
        ];

        foreach ($fieldRules as $field => $patterns) {
            foreach ($headers as $colIdx => $headerText) {
                if (in_array($colIdx, $map)) {
                    continue;
                }

                // Ignore Registrasi columns when checking for wilayah
                if (in_array($field, ['kabupaten', 'provinsi', 'kelurahan', 'kecamatan'])) {
                    if (stripos($headerText, 'Registrasi') !== false || stripos($headerText, 'No.') !== false) {
                        continue;
                    }
                    if (stripos($headerText, 'Faskes') !== false || stripos($headerText, 'Fasyankes') !== false) {
                        continue;
                    }
                }

                if ($field === 'alamat_lengkap') {
                    if (stripos($headerText, 'Provinsi') !== false || stripos($headerText, 'Kabupaten') !== false) {
                        continue;
                    }
                }

                // Avoid date columns matching result columns
                if (in_array($field, ['hasil_tcm', 'hasil_mikroskopis', 'hasil_diagnosis', 'hasil_akhir_pengobatan'])) {
                    if (stripos($headerText, 'Tanggal') !== false || stripos($headerText, 'Tgl') !== false) {
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

        // Fallback for nama_lengkap
        if (!isset($map['nama_lengkap'])) {
            foreach ($headers as $colIdx => $headerText) {
                if (!in_array($colIdx, $map) && stripos($headerText, 'nama') !== false && stripos($headerText, 'fasyankes') === false) {
                    $map['nama_lengkap'] = $colIdx;
                    break;
                }
            }
        }

        return $map;
    }

    /**
     * Parse row using dynamic column mappings
     */
    protected function parseRowUniversally($sheet, int $rowNum, array $columnMap, array $formatInfo): ?array
    {
        $data = [];

        foreach ($columnMap as $field => $colIdx) {
            $val = $this->cleanVal($sheet->getCell([$colIdx, $rowNum])->getValue());
            $data[$field] = $val;
        }

        $nama = $data['nama_lengkap'] ?? null;
        $nik = $data['nik'] ?? null;
        $sitb = $data['no_reg_sitb'] ?? null;
        $terduga = $data['no_reg_terduga'] ?? null;

        // Skip empty rows
        if (empty($nama) && empty($nik) && empty($sitb) && empty($terduga)) {
            return null;
        }

        // Filter out header repeats
        if ($nama && (stripos($nama, 'Nama Lengkap') !== false || preg_match('/^\(\d+\)$/', $nama))) {
            return null;
        }

        // Clean umur
        $umur = $data['umur'] ?? null;
        if ($umur !== null && is_numeric($umur)) {
            $data['umur'] = (int) $umur;
        } else {
            $data['umur'] = null;
        }

        // Clean Jenis Kelamin
        $jk = strtoupper((string) ($data['jenis_kelamin'] ?? ''));
        if (in_array($jk, ['L', 'LAKI-LAKI', 'PRIA', 'M', 'MALE'])) {
            $data['jenis_kelamin'] = 'L';
        } elseif (in_array($jk, ['P', 'PEREMPUAN', 'WANITA', 'F', 'FEMALE'])) {
            $data['jenis_kelamin'] = 'P';
        } else {
            $data['jenis_kelamin'] = !empty($jk) ? substr($jk, 0, 1) : null;
        }

        // Report type
        $data['report_type'] = $formatInfo['type'] ?: 'tb_03';
        if (isset($data['hasil_diagnosis']) && stripos($data['hasil_diagnosis'], 'Bukan TBC') !== false) {
            $data['report_type'] = 'tb_06';
        }

        // Default diagnosis value
        if (empty($data['hasil_diagnosis'])) {
            $data['hasil_diagnosis'] = $data['report_type'] === 'tb_03' ? 'Terkonfirmasi TBC' : 'Terduga';
        }

        return $data;
    }

    protected function cleanVal($val): ?string
    {
        if ($val === null) {
            return null;
        }
        $str = trim((string) $val);

        return $str === '' ? null : $str;
    }

    /**
     * Preview for Formulir Investigasi Kontak (TBC.16K)
     */
    protected function previewTb16k($sheet, array $formatInfo): array
    {
        $highestRow = $sheet->getHighestRow();
        $startRow = $formatInfo['data_start_row'] ?? 15;
        $previewRows = [];
        $totalRows = 0;

        $patientMap = TbPatient::get(['no_reg_sitb', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi'])
            ->keyBy('no_reg_sitb')
            ->toArray();

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseTb16kRow($sheet, $rowNum, $patientMap);
            if ($parsed !== null) {
                $totalRows++;
                if (count($previewRows) < 10) {
                    $previewRows[] = $parsed;
                }
            }
        }

        return [
            'type' => 'tb_16k',
            'type_label' => $formatInfo['label'],
            'fasyankes_name' => $formatInfo['fasyankes_name'],
            'fasyankes_code' => $formatInfo['fasyankes_code'],
            'period' => $formatInfo['period'],
            'total_rows' => $totalRows,
            'preview_samples' => $previewRows,
            'detected_fields' => [
                'kasus_indeks_sitb', 'kasus_indeks_nama', 'nama_kontak', 'nik_kontak',
                'umur_kontak', 'jenis_kelamin_kontak', 'jenis_kontak', 'alamat_kontak',
                'kelurahan', 'hasil_evaluasi'
            ],
            'header_row' => $formatInfo['header_row'] ?? 11,
        ];
    }

    /**
     * Import / Upsert for Formulir Investigasi Kontak (TBC.16K)
     */
    protected function importTb16k($sheet, array $formatInfo): array
    {
        $highestRow = $sheet->getHighestRow();
        $startRow = $formatInfo['data_start_row'] ?? 15;
        $insertedCount = 0;
        $updatedCount = 0;

        $patientMap = TbPatient::get(['no_reg_sitb', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi'])
            ->keyBy('no_reg_sitb')
            ->toArray();

        for ($rowNum = $startRow; $rowNum <= $highestRow; $rowNum++) {
            $parsed = $this->parseTb16kRow($sheet, $rowNum, $patientMap);
            if ($parsed === null) {
                continue;
            }

            if (empty($parsed['fasyankes_name']) && !empty($formatInfo['fasyankes_name'])) {
                $parsed['fasyankes_name'] = $formatInfo['fasyankes_name'];
            }
            if (empty($parsed['fasyankes_code']) && !empty($formatInfo['fasyankes_code'])) {
                $parsed['fasyankes_code'] = $formatInfo['fasyankes_code'];
            }
            if (empty($parsed['periode']) && !empty($formatInfo['period'])) {
                $parsed['periode'] = $formatInfo['period'];
            }

            // Realtime Upsert: match by (kasus_indeks_sitb AND nama_kontak) OR (nik_kontak if valid)
            $existing = null;
            if (!empty($parsed['nik_kontak']) && !str_contains($parsed['nik_kontak'], '*')) {
                $existing = TbContactInvestigation::where('nik_kontak', $parsed['nik_kontak'])->first();
            }

            if (!$existing && !empty($parsed['kasus_indeks_sitb']) && !empty($parsed['nama_kontak'])) {
                $existing = TbContactInvestigation::where('kasus_indeks_sitb', $parsed['kasus_indeks_sitb'])
                    ->where('nama_kontak', $parsed['nama_kontak'])
                    ->first();
            }

            if (!$existing && !empty($parsed['nama_kontak']) && !empty($parsed['alamat_kontak'])) {
                $existing = TbContactInvestigation::where('nama_kontak', $parsed['nama_kontak'])
                    ->where('alamat_kontak', $parsed['alamat_kontak'])
                    ->first();
            }

            if ($existing) {
                $existing->update($parsed);
                $updatedCount++;
            } else {
                TbContactInvestigation::create($parsed);
                $insertedCount++;
            }
        }

        return [
            'type' => 'tb_16k',
            'type_label' => $formatInfo['label'],
            'inserted' => $insertedCount,
            'updated' => $updatedCount,
            'total' => $insertedCount + $updatedCount,
        ];
    }

    /**
     * Parse row of TBC.16K
     */
    protected function parseTb16kRow($sheet, int $rowNum, array &$patientMap): ?array
    {
        $no = $this->cleanVal($sheet->getCell([3, $rowNum])->getValue());
        $sitb = $this->cleanVal($sheet->getCell([7, $rowNum])->getValue());
        $namaIndeks = $this->cleanVal($sheet->getCell([8, $rowNum])->getValue());
        $namaKontak = $this->cleanVal($sheet->getCell([13, $rowNum])->getValue());

        // Skip empty or header rows
        if (empty($namaKontak) && empty($namaIndeks) && empty($sitb)) {
            return null;
        }
        if ($namaIndeks && (stripos($namaIndeks, 'Nama Kasus') !== false || stripos($namaIndeks, 'Kasus Indeks') !== false || preg_match('/^\(\d+\)$/', $namaIndeks))) {
            return null;
        }
        if ($no && !is_numeric($no) && !preg_match('/^\d+$/', $no)) {
            return null;
        }

        $nikKontak = $this->cleanVal($sheet->getCell([14, $rowNum])->getValue());
        $umurKontak = $this->cleanVal($sheet->getCell([15, $rowNum])->getValue());
        $jkRaw = strtoupper((string) $this->cleanVal($sheet->getCell([16, $rowNum])->getValue()));
        $jkKontak = in_array($jkRaw, ['L', 'P']) ? $jkRaw : (!empty($jkRaw) ? substr($jkRaw, 0, 1) : null);

        $alamatKontak = $this->cleanVal($sheet->getCell([17, $rowNum])->getValue());
        $isSerumah = !empty($this->cleanVal($sheet->getCell([19, $rowNum])->getValue()));
        $isErat = !empty($this->cleanVal($sheet->getCell([20, $rowNum])->getValue()));
        $jenisKontak = $isSerumah ? 'Kontak Serumah' : ($isErat ? 'Kontak Erat' : 'Kontak Serumah');

        $sakitTbc = !empty($this->cleanVal($sheet->getCell([35, $rowNum])->getValue()));
        $tidakTbc = !empty($this->cleanVal($sheet->getCell([36, $rowNum])->getValue()));
        $terduga = !empty($this->cleanVal($sheet->getCell([32, $rowNum])->getValue())) ||
                   !empty($this->cleanVal($sheet->getCell([33, $rowNum])->getValue())) ||
                   !empty($this->cleanVal($sheet->getCell([34, $rowNum])->getValue()));

        $hasilEvaluasi = 'Skrining Sehat';
        if ($sakitTbc) {
            $hasilEvaluasi = 'Sakit TBC';
        } elseif ($terduga) {
            $hasilEvaluasi = 'Terduga TBC';
        } elseif ($tidakTbc) {
            $hasilEvaluasi = 'Tidak TBC';
        }

        $wilayah = $this->resolveIkWilayah($sitb, $alamatKontak, $patientMap);

        return [
            'petugas_investigasi' => $this->cleanVal($sheet->getCell([4, $rowNum])->getValue()),
            'fasyankes_kader'     => $this->cleanVal($sheet->getCell([5, $rowNum])->getValue()),
            'dirujuk_oleh'        => $this->cleanVal($sheet->getCell([6, $rowNum])->getValue()),
            'kasus_indeks_sitb'   => $sitb,
            'kasus_indeks_nama'   => $namaIndeks,
            'kasus_indeks_umur'   => is_numeric($this->cleanVal($sheet->getCell([9, $rowNum])->getValue())) ? (int) $sheet->getCell([9, $rowNum])->getValue() : null,
            'kasus_indeks_tipe_diagnosis' => $this->cleanVal($sheet->getCell([10, $rowNum])->getValue()),
            'kasus_indeks_tgl_diagnosis'  => $this->cleanDateVal($sheet->getCell([11, $rowNum])->getValue()),
            'kasus_indeks_jenis'  => $this->cleanVal($sheet->getCell([12, $rowNum])->getValue()),
            'nama_kontak'         => $namaKontak,
            'nik_kontak'          => $nikKontak,
            'umur_kontak'         => is_numeric($umurKontak) ? (int) $umurKontak : null,
            'jenis_kelamin_kontak'=> $jkKontak,
            'alamat_kontak'       => $alamatKontak,
            'provinsi'            => $wilayah['provinsi'],
            'kabupaten'           => $wilayah['kabupaten'],
            'kecamatan'           => $wilayah['kecamatan'],
            'kelurahan'           => $wilayah['kelurahan'],
            'metode_ik'           => $this->cleanVal($sheet->getCell([18, $rowNum])->getValue()),
            'jenis_kontak'        => $jenisKontak,
            'tanggal_investigasi' => $this->cleanDateVal($sheet->getCell([21, $rowNum])->getValue()),
            'diperiksa_toraks'    => $this->cleanVal($sheet->getCell([22, $rowNum])->getValue()),
            'gejala_batuk'        => $this->cleanVal($sheet->getCell([23, $rowNum])->getValue()),
            'gejala_lain'         => $this->cleanVal($sheet->getCell([24, $rowNum])->getValue()),
            'faktor_risiko'       => $this->cleanVal($sheet->getCell([27, $rowNum])->getValue()),
            'status_rujukan_terduga' => $this->cleanVal($sheet->getCell([32, $rowNum])->getValue()),
            'hasil_evaluasi'      => $hasilEvaluasi,
            'status_tpt'          => !empty($this->cleanVal($sheet->getCell([39, $rowNum])->getValue())) ? 'Memenuhi Syarat TPT' : null,
        ];
    }

    /**
     * Resolve Kelurahan & Kecamatan for IK row
     */
    protected function resolveIkWilayah(?string $sitb, ?string $alamat, array &$patientMap): array
    {
        // 1. If index patient exists in tb_patients, inherit their kelurahan & kecamatan
        if (!empty($sitb) && isset($patientMap[$sitb]) && !empty($patientMap[$sitb]['kelurahan'])) {
            return [
                'provinsi'  => $patientMap[$sitb]['provinsi'] ?? 'Banten',
                'kabupaten' => $patientMap[$sitb]['kabupaten'] ?? 'Kab. Tangerang',
                'kecamatan' => $patientMap[$sitb]['kecamatan'] ?? 'Pagedangan',
                'kelurahan' => $patientMap[$sitb]['kelurahan'],
            ];
        }

        // 2. Scan address text for known kelurahan
        $known = [
            'karang tengah'  => ['kel' => 'Karang Tengah', 'kec' => 'Pagedangan'],
            'lengkong kulon' => ['kel' => 'Lengkong Kulon', 'kec' => 'Pagedangan'],
            'kadusirung'     => ['kel' => 'Kadu Sirung', 'kec' => 'Pagedangan'],
            'kadu sirung'    => ['kel' => 'Kadu Sirung', 'kec' => 'Pagedangan'],
            'malang nengah'  => ['kel' => 'Malang Nengah', 'kec' => 'Pagedangan'],
            'situgadung'     => ['kel' => 'Situ Gadung', 'kec' => 'Pagedangan'],
            'situ gadung'    => ['kel' => 'Situ Gadung', 'kec' => 'Pagedangan'],
            'medang'         => ['kel' => 'Medang', 'kec' => 'Pagedangan'],
            'cijantra'       => ['kel' => 'Cijantra', 'kec' => 'Pagedangan'],
            'cihuni'         => ['kel' => 'Cihuni', 'kec' => 'Pagedangan'],
            'cicalengka'     => ['kel' => 'Cicalengka', 'kec' => 'Pagedangan'],
            'jatake'         => ['kel' => 'Jatake', 'kec' => 'Pagedangan'],
            'pagedangan'     => ['kel' => 'Pagedangan', 'kec' => 'Pagedangan'],
            'curug'          => ['kel' => 'Curug', 'kec' => 'Curug'],
            'legok'          => ['kel' => 'Legok', 'kec' => 'Legok'],
            'cisauk'         => ['kel' => 'Cisauk', 'kec' => 'Cisauk'],
        ];

        if (!empty($alamat)) {
            foreach ($known as $k => $info) {
                if (stripos($alamat, $k) !== false) {
                    return [
                        'provinsi'  => 'Banten',
                        'kabupaten' => 'Kab. Tangerang',
                        'kecamatan' => $info['kec'],
                        'kelurahan' => $info['kel'],
                    ];
                }
            }
        }

        // Default
        return [
            'provinsi'  => 'Banten',
            'kabupaten' => 'Kab. Tangerang',
            'kecamatan' => 'Pagedangan',
            'kelurahan' => 'Pagedangan',
        ];
    }

    /**
     * Clean date value from spreadsheet
     */
    protected function cleanDateVal($val): ?string
    {
        if ($val === null || $val === '') {
            return null;
        }
        if ($val instanceof \DateTimeInterface) {
            return $val->format('Y-m-d');
        }
        if (is_numeric($val)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $val)->format('Y-m-d');
            } catch (\Throwable) {}
        }
        return trim((string) $val);
    }
}
