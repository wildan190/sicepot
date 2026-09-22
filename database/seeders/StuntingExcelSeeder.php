<?php

namespace Database\Seeders;

use App\Modules\Stunting\Models\StuntingPatient;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class StuntingExcelSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('data/Jumlah Kasus Stunting Perbulan Puskesmas Pagedangan 2026.xlsx');
        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan: {$filePath}");
            return;
        }

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $inserted = 0;
        $updated  = 0;

        // Kosongkan data lama agar data benar-benar sesuai dengan Excel baru
        StuntingPatient::truncate();

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $highestRow = $sheet->getHighestRow();

            $this->command->info("Memproses Sheet: {$sheetName} (hingga baris {$highestRow})...");

            for ($row = 3; $row <= $highestRow; $row++) {
                $nama = trim((string) $sheet->getCell("C{$row}")->getValue());
                if (empty($nama)) {
                    continue;
                }

                $nik = trim((string) $sheet->getCell("B{$row}")->getValue());
                $jk  = trim((string) $sheet->getCell("D{$row}")->getValue());

                // Tanggal Lahir
                $tglLahirRaw = $sheet->getCell("E{$row}")->getValue();
                $tglLahir = $this->parseDate($tglLahirRaw);

                // BB Lahir & TB Lahir
                $bbLahir = $this->parseNumeric($sheet->getCell("F{$row}")->getValue());
                $tbLahir = $this->parseNumeric($sheet->getCell("G{$row}")->getValue());

                $ortu     = trim((string) $sheet->getCell("H{$row}")->getValue());
                $prov     = trim((string) $sheet->getCell("I{$row}")->getValue());
                $kabKota  = trim((string) $sheet->getCell("J{$row}")->getValue());
                $kec      = trim((string) $sheet->getCell("K{$row}")->getValue());
                $pkm      = trim((string) $sheet->getCell("L{$row}")->getValue());
                $desa     = trim((string) $sheet->getCell("M{$row}")->getValue());
                $posyandu = trim((string) $sheet->getCell("N{$row}")->getValue());
                $rt       = trim((string) $sheet->getCell("O{$row}")->getValue());
                $rw       = trim((string) $sheet->getCell("P{$row}")->getValue());
                $alamat   = trim((string) $sheet->getCell("Q{$row}")->getValue());
                $usia     = trim((string) $sheet->getCell("R{$row}")->getValue());

                // Tanggal Pengukuran
                $tglUkurRaw = $sheet->getCell("S{$row}")->getValue();
                $tglUkur = $this->parseDate($tglUkurRaw);

                $berat    = $this->parseNumeric($sheet->getCell("T{$row}")->getValue());
                $tinggi   = $this->parseNumeric($sheet->getCell("U{$row}")->getValue());
                $caraUkur = trim((string) $sheet->getCell("V{$row}")->getValue());
                $lila     = $this->parseNumeric($sheet->getCell("W{$row}")->getValue());

                $bbuKategori = trim((string) $sheet->getCell("X{$row}")->getValue());
                $bbuZscore   = $this->parseNumeric($sheet->getCell("Y{$row}")->getValue());

                $tbuKategori = trim((string) $sheet->getCell("Z{$row}")->getValue());
                $tbuZscore   = $this->parseNumeric($sheet->getCell("AA{$row}")->getValue());

                $bbtbKategori = trim((string) $sheet->getCell("AB{$row}")->getValue());
                $bbtbZscore   = $this->parseNumeric($sheet->getCell("AC{$row}")->getValue());

                $naikBB = trim((string) $sheet->getCell("AD{$row}")->getValue());
                $vitA   = $sheet->getCell("AE{$row}")->getValue();
                $vitA   = is_numeric($vitA) ? (int)$vitA : null;

                $kpsp     = trim((string) $sheet->getCell("AF{$row}")->getValue());
                $kia      = trim((string) $sheet->getCell("AG{$row}")->getValue());
                $kelasIbu = trim((string) $sheet->getCell("AH{$row}")->getValue());
                $mbg      = trim((string) $sheet->getCell("AI{$row}")->getValue());

                $mantoux = trim((string) $sheet->getCell("AK{$row}")->getValue()); // Col 37
                $hb      = trim((string) $sheet->getCell("AL{$row}")->getValue()); // Col 38
                $konsul  = trim((string) $sheet->getCell("AM{$row}")->getValue()); // Col 39

                StuntingPatient::create([
                    'nik'                => $nik ?: null,
                    'nama'               => $nama,
                    'jenis_kelamin'      => $jk ?: null,
                    'tanggal_lahir'      => $tglLahir,
                    'bb_lahir'           => $bbLahir,
                    'tb_lahir'           => $tbLahir,
                    'nama_ortu'          => $ortu ?: null,
                    'prov'               => $prov ?: 'BANTEN',
                    'kab_kota'           => $kabKota ?: 'KABUPATEN TANGERANG',
                    'kec'                => $kec ?: 'PAGEDANGAN',
                    'puskesmas'          => $pkm ?: 'PAGEDANGAN',
                    'desa'               => $desa ?: null,
                    'posyandu'           => $posyandu ?: null,
                    'rt'                 => $rt ?: null,
                    'rw'                 => $rw ?: null,
                    'alamat'             => $alamat ?: null,
                    'usia_saat_ukur'     => $usia ?: null,
                    'tanggal_pengukuran' => $tglUkur,
                    'berat'              => $berat,
                    'tinggi'             => $tinggi,
                    'cara_ukur'          => $caraUkur ?: null,
                    'lila'               => $lila,
                    'bbu_kategori'       => $bbuKategori ?: null,
                    'bbu_zscore'         => $bbuZscore,
                    'tbu_kategori'       => $tbuKategori ?: null,
                    'tbu_zscore'         => $tbuZscore,
                    'bbtb_kategori'      => $bbtbKategori ?: null,
                    'bbtb_zscore'        => $bbtbZscore,
                    'naik_berat_badan'   => $naikBB ?: null,
                    'jml_vit_a'          => $vitA,
                    'kpsp'               => $kpsp ?: null,
                    'kia'                => $kia ?: null,
                    'kelas_ibu'          => $kelasIbu ?: null,
                    'mbg'                => $mbg ?: null,
                    'test_mantoux'       => $mantoux ?: 'Ya',
                    'test_hemoglobin'    => $hb ?: 'Ya',
                    'konsul_spa'         => $konsul ?: 'Ya',
                ]);

                $inserted++;
            }
        }

        $this->command->info("Selesai! Total {$inserted} rekaman balita stunting berhasil di-import dari 8 bulan data 2026.");
    }

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

    private function parseNumeric(mixed $val): ?float
    {
        if ($val === null || $val === '') {
            return null;
        }
        if (is_numeric($val)) {
            return (float) $val;
        }
        $str = trim((string) $val);
        // Clean commas to dot
        $str = str_replace(',', '.', $str);
        if (is_numeric($str)) {
            return (float) $str;
        }
        return null;
    }
}
