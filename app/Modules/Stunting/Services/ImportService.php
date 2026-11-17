<?php

namespace App\Modules\Stunting\Services;

use App\Modules\Stunting\Models\StuntingPatient;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ImportService
{
    /**
     * Daftar lengkap kolom template Stunting (39 kolom, format baru A–AM).
     * Posisi kolom dikunci sesuai parser fixed-position pada parseRow() (format 39 kolom),
     * sehingga berkas template dapat diunggah kembali tanpa kehilangan data (round-trip).
     */
    public const TEMPLATE_COLUMNS = [
        'No',
        'NIK',
        'Nama',
        'JK',
        'Tgl Lahir',
        'BB Lahir',
        'TB Lahir',
        'Nama Ortu',
        'Provinsi',
        'Kabupaten/Kota',
        'Kecamatan',
        'Puskesmas',
        'Desa',
        'Posyandu',
        'RT',
        'RW',
        'Alamat',
        'Usia Saat Ukur',
        'Tgl Ukur',
        'Berat',
        'Tinggi',
        'Cara Ukur',
        'LiLA',
        'BB/U',
        'ZS BB/U',
        'TB/U',
        'ZS TB/U',
        'BB/TB',
        'ZS BB/TB',
        'Naik BB',
        'Vit A',
        'KPSP',
        'KIA',
        'Kelas Ibu',
        'MBG',
        'Detail',
        'Test Mantoux',
        'Test Hemoglobin',
        'Konsul Spesialis Anak',
    ];

    /**
     * Buat berkas template Excel lengkap (semua kolom) untuk diunduh pengguna.
     * Mengembalikan path absolut berkas yang dihasilkan.
     */
    public function generateTemplate(): string
    {
        $headers = self::TEMPLATE_COLUMNS;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Stunting');

        $lastCol = Coordinate::stringFromColumnIndex(count($headers));

        // Baris judul
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA BALITA STUNTING (e-PPGBM) — LENGKAP SELURUH KOLOM');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FF14532D');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Baris header kolom (baris 2 — data mulai baris 3 sesuai parser fixed-position)
        foreach ($headers as $idx => $label) {
            $coord = Coordinate::stringFromColumnIndex($idx + 1) . '2';
            $sheet->setCellValue($coord, $label);
        }

        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF16A34A']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBBF7D0']]],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(34);

        for ($i = 1; $i <= count($headers); $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setWidth(18);
        }

        $sheet->freezePane('A3');

        return $this->saveTemplate($spreadsheet, 'Template_Import_Stunting');
    }

    protected function saveTemplate(Spreadsheet $spreadsheet, string $baseName): string
    {
        $dir = storage_path('app/temp_templates');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/' . $baseName . '_' . date('Ymd_His') . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);

        return $path;
    }

    /**
     * Parse a numeric cell that may be stored as a time fraction in Excel or comma decimal.
     */
    private function parseNumCell(mixed $val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }
        if (is_numeric($val)) {
            $val = (float) $val;
            if ($val > 0 && $val < 1) {
                // Time fraction: 0.3798... × 1440 = 547 min = 9h 7m → 9.07
                $totalMins = (int) round($val * 1440);
                $hours     = (int) floor($totalMins / 60);
                $mins      = $totalMins % 60;
                return (float) ($hours . '.' . str_pad((string) $mins, 2, '0', STR_PAD_LEFT));
            }
            return $val;
        }

        $str = trim((string) $val);
        $str = str_replace(',', '.', $str);
        if (is_numeric($str)) {
            return (float) $str;
        }

        return null;
    }

    /**
     * Parse an Excel date serial or date string into a Y-m-d string.
     */
    private function parseDate(mixed $val): ?string
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
            } catch (\Throwable) {
                return null;
            }
        }
        try {
            return Carbon::parse($val)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Parse a single row from either 39-column (new) or 27-column (legacy) format.
     */
    private function parseRow(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $rowNum, bool $isNewFormat): ?array
    {
        $cell = fn(string $col) => $sheet->getCell($col . $rowNum)->getValue();

        $nama = trim((string) $cell('C'));
        if (empty($nama) || strtolower($nama) === 'nama') {
            return null;
        }

        if ($isNewFormat) {
            // New format (e.g. Jumlah Kasus Stunting Perbulan 2026 / Total Balita)
            // A=1: No, B=2: NIK, C=3: Nama, D=4: JK, E=5: Tgl Lahir, F=6: BB Lahir, G=7: TB Lahir, H=8: Nama Ortu
            // I=9: Prov, J=10: Kab/Kota, K=11: Kec, L=12: Puskesmas, M=13: Desa, N=14: Posyandu
            // O=15: RT, P=16: RW, Q=17: Alamat, R=18: Usia, S=19: Tgl Ukur, T=20: Berat, U=21: Tinggi, V=22: Cara Ukur
            // W=23: LiLA, X=24: BB/U, Y=25: ZS BB/U, Z=26: TB/U, AA=27: ZS TB/U, AB=28: BB/TB, AC=29: ZS BB/TB, AD=30: Naik BB
            // AE=31: Vit A, AF=32: KPSP, AG=33: KIA, AH=34: Kelas Ibu, AI=35: MBG, AJ=36: Detail
            // AK=37: Test Mantoux, AL=38: Test Hemoglobin, AM=39: Konsul Spesialis Anak
            $bbLahir = $this->parseNumCell($cell('F'));
            $tbLahir = $this->parseNumCell($cell('G'));
            $berat   = $this->parseNumCell($cell('T'));
            $tinggi  = $this->parseNumCell($cell('U'));
            $lila    = $this->parseNumCell($cell('W'));

            $vitA = $cell('AE');
            $vitA = is_numeric($vitA) ? (int)$vitA : null;

            return [
                'nik'                => trim((string) ($cell('B') ?? '')),
                'nama'               => $nama,
                'jenis_kelamin'      => trim((string) ($cell('D') ?? '')),
                'tanggal_lahir'      => $this->parseDate($cell('E')),
                'bb_lahir'           => $bbLahir,
                'tb_lahir'           => $tbLahir,
                'nama_ortu'          => trim((string) ($cell('H') ?? '')),
                'prov'               => trim((string) ($cell('I') ?? 'BANTEN')),
                'kab_kota'           => trim((string) ($cell('J') ?? 'KABUPATEN TANGERANG')),
                'kec'                => trim((string) ($cell('K') ?? 'PAGEDANGAN')),
                'puskesmas'          => trim((string) ($cell('L') ?? 'PAGEDANGAN')),
                'desa'               => trim((string) ($cell('M') ?? '')),
                'posyandu'           => trim((string) ($cell('N') ?? '')),
                'rt'                 => trim((string) ($cell('O') ?? '')),
                'rw'                 => trim((string) ($cell('P') ?? '')),
                'alamat'             => trim((string) ($cell('Q') ?? '')),
                'usia_saat_ukur'     => trim((string) ($cell('R') ?? '')),
                'tanggal_pengukuran' => $this->parseDate($cell('S')),
                'berat'              => $berat,
                'tinggi'             => $tinggi,
                'cara_ukur'          => trim((string) ($cell('V') ?? '')),
                'lila'               => $lila,
                'bbu_kategori'       => trim((string) ($cell('X') ?? '')),
                'bbu_zscore'         => $this->parseNumCell($cell('Y')),
                'tbu_kategori'       => trim((string) ($cell('Z') ?? '')),
                'tbu_zscore'         => $this->parseNumCell($cell('AA')),
                'bbtb_kategori'      => trim((string) ($cell('AB') ?? '')),
                'bbtb_zscore'        => $this->parseNumCell($cell('AC')),
                'naik_berat_badan'   => trim((string) ($cell('AD') ?? '')),
                'jml_vit_a'          => $vitA,
                'kpsp'               => trim((string) ($cell('AF') ?? '')),
                'kia'                => trim((string) ($cell('AG') ?? '')),
                'kelas_ibu'          => trim((string) ($cell('AH') ?? '')),
                'mbg'                => trim((string) ($cell('AI') ?? '')),
                'test_mantoux'       => trim((string) ($cell('AK') ?? 'Ya')),
                'test_hemoglobin'    => trim((string) ($cell('AL') ?? 'Ya')),
                'konsul_spa'         => trim((string) ($cell('AM') ?? 'Ya')),
            ];
        } else {
            // Legacy 27-column format
            $bbLahir = $this->parseNumCell($cell('F'));
            $tbLahir = $this->parseNumCell($cell('G'));
            $berat   = $this->parseNumCell($cell('Q'));
            $tinggi  = $this->parseNumCell($cell('R'));
            $lila    = $this->parseNumCell($cell('T'));

            return [
                'nik'                => trim((string) ($cell('B') ?? '')),
                'nama'               => $nama,
                'jenis_kelamin'      => trim((string) ($cell('D') ?? '')),
                'tanggal_lahir'      => $this->parseDate($cell('E')),
                'bb_lahir'           => $bbLahir,
                'tb_lahir'           => $tbLahir,
                'nama_ortu'          => trim((string) ($cell('H') ?? '')),
                'puskesmas'          => trim((string) ($cell('I') ?? '')),
                'desa'               => trim((string) ($cell('J') ?? '')),
                'posyandu'           => trim((string) ($cell('K') ?? '')),
                'rt'                 => trim((string) ($cell('L') ?? '')),
                'rw'                 => trim((string) ($cell('M') ?? '')),
                'alamat'             => trim((string) ($cell('N') ?? '')),
                'usia_saat_ukur'     => trim((string) ($cell('O') ?? '')),
                'tanggal_pengukuran' => $this->parseDate($cell('P')),
                'berat'              => $berat,
                'tinggi'             => $tinggi,
                'cara_ukur'          => trim((string) ($cell('S') ?? '')),
                'lila'               => $lila,
                'bbu_kategori'       => trim((string) ($cell('U') ?? '')),
                'bbu_zscore'         => $this->parseNumCell($cell('V')),
                'tbu_kategori'       => trim((string) ($cell('W') ?? '')),
                'tbu_zscore'         => $this->parseNumCell($cell('X')),
                'bbtb_kategori'      => trim((string) ($cell('Y') ?? '')),
                'bbtb_zscore'        => $this->parseNumCell($cell('Z')),
                'naik_berat_badan'   => trim((string) ($cell('AA') ?? '')),
                'test_mantoux'       => 'Ya',
                'test_hemoglobin'    => 'Ya',
                'konsul_spa'         => 'Ya',
            ];
        }
    }

    /**
     * Preview up to 10 rows from the Excel file across sheets.
     */
    public function previewFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $preview = [];
        $total   = 0;

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $startRow = 3;
            $isNewFormat = true;

            // Detect format: check if row 2 has "Desa/Kel" in col M (col 13)
            $colM = (string) $sheet->getCell('M2')->getValue();
            if (str_contains(strtolower($colM), 'desa')) {
                $isNewFormat = true;
                $startRow = 3;
            } else {
                // Check legacy row 5
                $colJ = (string) $sheet->getCell('J5')->getValue();
                if (str_contains(strtolower($colJ), 'desa')) {
                    $isNewFormat = false;
                    $startRow = 6;
                }
            }

            $highestRow = $sheet->getHighestRow();
            for ($row = $startRow; $row <= $highestRow; $row++) {
                $parsed = $this->parseRow($sheet, $row, $isNewFormat);
                if ($parsed !== null) {
                    $total++;
                    if (count($preview) < 10) {
                        $preview[] = $parsed;
                    }
                }
            }
        }

        return [
            'total_rows'      => $total,
            'preview_samples' => $preview,
        ];
    }

    /**
     * Import all rows from the Excel file across sheets.
     */
    public function importFile(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $inserted = 0;
        $updated  = 0;

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $startRow = 3;
            $isNewFormat = true;

            $colM = (string) $sheet->getCell('M2')->getValue();
            if (str_contains(strtolower($colM), 'desa')) {
                $isNewFormat = true;
                $startRow = 3;
            } else {
                $colJ = (string) $sheet->getCell('J5')->getValue();
                if (str_contains(strtolower($colJ), 'desa')) {
                    $isNewFormat = false;
                    $startRow = 6;
                }
            }

            $highestRow = $sheet->getHighestRow();
            for ($row = $startRow; $row <= $highestRow; $row++) {
                $parsed = $this->parseRow($sheet, $row, $isNewFormat);
                if ($parsed === null) {
                    continue;
                }

                // Upsert strategy: NIK + tanggal_pengukuran -> update or insert
                $query = StuntingPatient::query();
                if (!empty($parsed['nik'])) {
                    $query->where('nik', $parsed['nik']);
                } else {
                    $query->where('nama', $parsed['nama'])->where('desa', $parsed['desa']);
                }

                if (!empty($parsed['tanggal_pengukuran'])) {
                    $query->where('tanggal_pengukuran', $parsed['tanggal_pengukuran']);
                }

                $existing = $query->first();

                if ($existing) {
                    $existing->fill($parsed)->save();
                    $updated++;
                } else {
                    StuntingPatient::create($parsed);
                    $inserted++;
                }
            }
        }

        return [
            'total'    => $inserted + $updated,
            'inserted' => $inserted,
            'updated'  => $updated,
        ];
    }
}
