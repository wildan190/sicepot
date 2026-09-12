<?php

namespace App\Modules\Stunting\Services;

use App\Modules\Stunting\Models\StuntingPatient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportService
{
    /**
     * Parse a numeric cell that may be stored as a time fraction in Excel.
     * Excel sometimes stores weight/height values as time fractions
     * (e.g., 9h 7min = 0.3798... for 9.07 kg).
     * If value < 1 → treat as time fraction, convert to H.MM decimal.
     * If value >= 1 → use as-is.
     */
    private function parseNumCell(mixed $val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }
        if (!is_numeric($val)) {
            return null;
        }
        $val = (float) $val;
        if ($val < 1) {
            // Time fraction: 0.3798... × 1440 = 547 min = 9h 7m → 9.07
            $totalMins = (int) round($val * 1440);
            $hours     = (int) floor($totalMins / 60);
            $mins      = $totalMins % 60;
            return (float) ($hours . '.' . str_pad((string) $mins, 2, '0', STR_PAD_LEFT));
        }
        return $val;
    }

    /**
     * Parse an Excel date serial into a Y-m-d string.
     */
    private function parseDate(mixed $val): ?string
    {
        if ($val === null || $val === '') {
            return null;
        }
        if (!is_numeric($val)) {
            // Try direct date string
            try {
                return \Carbon\Carbon::parse($val)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }
        try {
            return ExcelDate::excelToDateTimeObject((float) $val)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Parse a single spreadsheet row into an importable array.
     * Column mapping (1-indexed):
     *  A=1  No
     *  B=2  NIK
     *  C=3  Nama
     *  D=4  JK
     *  E=5  Tgl Lahir
     *  F=6  BB Lahir
     *  G=7  TB Lahir
     *  H=8  Nama Ortu
     *  I=9  Puskesmas
     *  J=10 Desa/Kel
     *  K=11 Posyandu
     *  L=12 RT
     *  M=13 RW
     *  N=14 Alamat
     *  O=15 Usia Saat Ukur
     *  P=16 Tanggal Pengukuran
     *  Q=17 Berat
     *  R=18 Tinggi
     *  S=19 Cara Ukur
     *  T=20 LiLA
     *  U=21 BB/U (kategori)
     *  V=22 ZS BB/U
     *  W=23 TB/U (kategori)
     *  X=24 ZS TB/U
     *  Y=25 BB/TB (kategori)
     *  Z=26 ZS BB/TB
     *  AA=27 Naik Berat Badan
     */
    private function parseRow(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $rowNum): ?array
    {
        $cell = fn(string $col) => $sheet->getCell($col . $rowNum)->getValue();

        $nama = trim((string) $cell('C'));
        if (empty($nama)) {
            return null;
        }

        $bbLahirRaw = $cell('F');
        $berat      = $this->parseNumCell($cell('Q'));
        $tinggiRaw  = $cell('R');

        // TB Lahir: sometimes stored as time fraction (e.g., col G value 0.168... = 4.05 cm? unlikely)
        // Usually TB Lahir is a direct integer like 48 or 50 cm. Handle edge case:
        $tbLahirRaw = $cell('G');
        $tbLahir    = null;
        if (is_numeric($tbLahirRaw)) {
            $tbLahir = (float)$tbLahirRaw < 1
                ? $this->parseNumCell($tbLahirRaw)  // unusual fraction
                : (float)$tbLahirRaw;
        }

        // Tinggi: < 5 → time fraction (heights are 40-130 cm), >= 5 → direct
        $tinggi = null;
        if (is_numeric($tinggiRaw)) {
            $tinggi = (float)$tinggiRaw < 5
                ? $this->parseNumCell($tinggiRaw)
                : (float)$tinggiRaw;
        }

        // ZS values might be stored as time fractions (e.g., 0.052... = 1 min → 0.01 which is wrong)
        // ZS are small decimals like -2.05, 0.5. They're stored as real numbers NOT fractions.
        // Only apply time conversion if the value looks like a typical ZS value stored as fraction
        $parseZs = function(mixed $v): ?float {
            if ($v === null || !is_numeric($v)) return null;
            return (float)$v;  // ZS values: already real numbers
        };

        return [
            'nik'                => trim((string) ($cell('B') ?? '')),
            'nama'               => $nama,
            'jenis_kelamin'      => trim((string) ($cell('D') ?? '')),
            'tanggal_lahir'      => $this->parseDate($cell('E')),
            'bb_lahir'           => $this->parseNumCell($bbLahirRaw),
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
            'lila'               => is_numeric($cell('T')) ? (float)$cell('T') : null,
            'bbu_kategori'       => trim((string) ($cell('U') ?? '')),
            'bbu_zscore'         => $parseZs($cell('V')),
            'tbu_kategori'       => trim((string) ($cell('W') ?? '')),
            'tbu_zscore'         => $parseZs($cell('X')),
            'bbtb_kategori'      => trim((string) ($cell('Y') ?? '')),
            'bbtb_zscore'        => $parseZs($cell('Z')),
            'naik_berat_badan'   => trim((string) ($cell('AA') ?? '')),
        ];
    }

    /**
     * Preview up to 10 rows from the Excel file.
     */
    public function previewFile(string $filePath): array
    {
        $sheet      = $this->loadSheet($filePath);
        $highestRow = $sheet->getHighestRow();
        $preview    = [];
        $total      = 0;

        for ($row = 6; $row <= $highestRow; $row++) {
            $parsed = $this->parseRow($sheet, $row);
            if ($parsed !== null) {
                $total++;
                if (count($preview) < 10) {
                    $preview[] = $parsed;
                }
            }
        }

        return [
            'total_rows'      => $total,
            'preview_samples' => $preview,
        ];
    }

    /**
     * Import all rows from the Excel file (upsert by NIK or nama+desa).
     */
    public function importFile(string $filePath): array
    {
        $sheet      = $this->loadSheet($filePath);
        $highestRow = $sheet->getHighestRow();
        $inserted   = 0;
        $updated    = 0;

        for ($row = 6; $row <= $highestRow; $row++) {
            $parsed = $this->parseRow($sheet, $row);
            if ($parsed === null) {
                continue;
            }

            // Upsert strategy: NIK → nama+desa
            $existing = null;
            if (!empty($parsed['nik'])) {
                $existing = StuntingPatient::where('nik', $parsed['nik'])->first();
            }
            if (!$existing && !empty($parsed['nama']) && !empty($parsed['desa'])) {
                $existing = StuntingPatient::where('nama', $parsed['nama'])
                    ->where('desa', $parsed['desa'])
                    ->first();
            }

            if ($existing) {
                $existing->fill($parsed)->save();
                $updated++;
            } else {
                StuntingPatient::create($parsed);
                $inserted++;
            }
        }

        return [
            'total'    => $inserted + $updated,
            'inserted' => $inserted,
            'updated'  => $updated,
        ];
    }

    /**
     * Load the active worksheet from an Excel file.
     */
    private function loadSheet(string $filePath): \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(false);
        $spreadsheet = $reader->load($filePath);
        return $spreadsheet->getActiveSheet();
    }
}
