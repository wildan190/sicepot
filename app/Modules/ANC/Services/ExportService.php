<?php

namespace App\Modules\ANC\Services;

use App\Modules\ANC\Models\AncPatient;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportService
{
    public function exportExcel(Request $request): string
    {
        $kabupaten = $request->input('kabupaten');
        $kelurahan = $request->input('kelurahan');
        $bulan     = $request->input('bulan');
        $search    = $request->input('search');

        $query = AncPatient::query();
        if (!empty($kabupaten)) { \App\Services\RegionHelper::filterKabupaten($query, $kabupaten); }
        if (!empty($kelurahan)) { \App\Services\RegionHelper::filterKelurahan($query, $kelurahan); }
        if (!empty($bulan)) {
            $query->where(function ($q) use ($bulan) {
                $q->where('bulan_kunjungan', $bulan)
                  ->orWhere(function ($q2) use ($bulan) {
                      $q2->whereNull('bulan_kunjungan')
                         ->orWhere('bulan_kunjungan', '')
                         ->where('bulan', $bulan);
                  });
            });
        }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_rekam_medis', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('kabupaten')->orderBy('kelurahan')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Kohort Ibu Hamil (ANC)');

        // Title Header
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'REKAPITULASI PELAYANAN KESEHATAN IBU HAMIL (KOHORT ANC & P4K)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:O2');
        $periodeStr = 'Wilayah: ' . ($kabupaten ?: 'Semua') . ' | Periode: ' . ($bulan ?: 'Semua Bulan') . ' | Tanggal Unduh: ' . now()->translatedFormat('d F Y H:i');
        $sheet->setCellValue('A2', $periodeStr);
        $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Column Headers
        $headers = [
            'A4' => 'NO',
            'B4' => 'NAMA LENGKAP',
            'C4' => 'NAMA SUAMI',
            'D4' => 'NIK',
            'E4' => 'NO. TELP / WA',
            'F4' => 'UMUR',
            'G4' => 'G-P-A',
            'H4' => 'USIA HAMIL / KUNJUNGAN',
            'I4' => 'HPL / TP',
            'J4' => 'LiLA (CM)',
            'K4' => 'FAKTOR RISIKO / KEK',
            'L4' => 'SKOR POEDJI ROCHJATI',
            'M4' => 'KATEGORI RISIKO',
            'N4' => 'KADAR HB / ANEMIA',
            'O4' => 'WILAYAH (KEL / KAB)'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDB2777']], // Pink / Rose
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFBCFE8']]],
        ];
        $sheet->getStyle('A4:O4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $rowNum = 5;
        foreach ($patients as $idx => $p) {
            $gpaStr = 'G' . ($p->gravida ?: 1) . 'P' . ($p->para ?: 0) . 'A' . ($p->abortus ?: 0);
            $usiaKunjungan = ($p->usia_kehamilan ? $p->usia_kehamilan . ' mgg' : '-') . ' (' . ($p->kunjungan_ke ?: '-') . ')';
            $hplStr = $p->hpl ? date('d/m/Y', strtotime((string)$p->hpl)) : '-';
            $skorPR = $p->skor_poedji_rochjati ?: 2;
            $kategoriPR = $p->kategori_poedji_rochjati ?: 'KRR';
            $hbStr = ($p->hb ? $p->hb . ' g/dL' : '-') . ' (' . ($p->status_anemia ?: 'Normal') . ')';

            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $p->nama_lengkap);
            $sheet->setCellValue('C' . $rowNum, $p->nama_suami ?: '-');
            $sheet->setCellValueExplicit('D' . $rowNum, (string)$p->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $rowNum, (string)($p->no_telepon ?: '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $rowNum, $p->umur ?: '-');
            $sheet->setCellValue('G' . $rowNum, $gpaStr);
            $sheet->setCellValue('H' . $rowNum, $usiaKunjungan);
            $sheet->setCellValue('I' . $rowNum, $hplStr);
            $sheet->setCellValue('J' . $rowNum, $p->lila ? $p->lila . ' cm' : '-');
            $sheet->setCellValue('K' . $rowNum, $p->faktor_risiko ?: ($p->status_risti ?: 'Normal'));
            $sheet->setCellValue('L' . $rowNum, $skorPR);
            $sheet->setCellValue('M' . $rowNum, $kategoriPR);
            $sheet->setCellValue('N' . $rowNum, $hbStr);
            $sheet->setCellValue('O' . $rowNum, ($p->kelurahan ?: '-') . ', ' . ($p->kabupaten ?: '-'));

            $dataRowStyle = [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ];
            if ($rowNum % 2 == 0) {
                $dataRowStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFDF2F8']];
            }
            $sheet->getStyle('A' . $rowNum . ':O' . $rowNum)->applyFromArray($dataRowStyle);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        // Auto width
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempPath = storage_path('app/temp_exports');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $filename = 'Kohort_ANC_IbuHamil_' . date('Ymd_His') . '.xlsx';
        $fullPath = $tempPath . '/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($fullPath);

        return $fullPath;
    }
}
