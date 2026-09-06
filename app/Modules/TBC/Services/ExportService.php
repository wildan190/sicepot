<?php

namespace App\Modules\TBC\Services;

use App\Modules\TBC\Models\TbPatient;
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
        $kabupaten  = $request->input('kabupaten');
        $kelurahan  = $request->input('kelurahan');
        $reportType = $request->input('report_type');
        $search     = $request->input('search');

        $query = TbPatient::query();
        if (!empty($kabupaten))  { \App\Services\RegionHelper::filterKabupaten($query, $kabupaten); }
        if (!empty($kelurahan))  { \App\Services\RegionHelper::filterKelurahan($query, $kelurahan); }
        if (!empty($reportType)) { $query->where('report_type', $reportType); }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_reg_sitb', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('kabupaten')->orderBy('kelurahan')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Pasien TBC');

        // Title Header
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'REKAPITULASI LAPORAN DATA PASIEN & TERDUGA TBC (SITB)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:J2');
        $periodeStr = 'Filter Wilayah: ' . ($kabupaten ?: 'Semua Kabupaten') . ' | ' . ($kelurahan ?: 'Semua Kelurahan') . ' | Tanggal Unduh: ' . now()->translatedFormat('d F Y H:i');
        $sheet->setCellValue('A2', $periodeStr);
        $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Column Headers
        $headers = [
            'A4' => 'NO',
            'B4' => 'TIPE REGISTER',
            'C4' => 'NAMA LENGKAP',
            'D4' => 'NIK',
            'E4' => 'NO. REG SITB / TERDUGA',
            'F4' => 'L/P',
            'G4' => 'UMUR (TH)',
            'H4' => 'KABUPATEN',
            'I4' => 'KELURAHAN / DESA',
            'J4' => 'DIAGNOSIS & HASIL PENGOBATAN'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']], // Indigo
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
        ];
        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $rowNum = 5;
        foreach ($patients as $idx => $p) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValue('B' . $rowNum, $p->report_type === 'tb_03' ? 'TB-03 SO (Pasien TBC)' : 'TB-06 (Terduga)');
            $sheet->setCellValue('C' . $rowNum, $p->nama_lengkap);
            $sheet->setCellValueExplicit('D' . $rowNum, (string)$p->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $rowNum, (string)($p->no_reg_sitb ?: $p->no_reg_terduga ?: '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $rowNum, $p->jenis_kelamin ?: '-');
            $sheet->setCellValue('G' . $rowNum, $p->umur ?: '-');
            $sheet->setCellValue('H' . $rowNum, $p->kabupaten ?: '-');
            $sheet->setCellValue('I' . $rowNum, $p->kelurahan ?: '-');
            $statusStr = ($p->hasil_diagnosis ?: '-') . ' | ' . ($p->hasil_akhir_pengobatan ?: 'Dalam Pengobatan');
            $sheet->setCellValue('J' . $rowNum, $statusStr);

            $dataRowStyle = [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ];
            if ($rowNum % 2 == 0) {
                $dataRowStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']];
            }
            $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->applyFromArray($dataRowStyle);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        // Auto width for columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempPath = storage_path('app/temp_exports');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $filename = 'Laporan_TBC_' . date('Ymd_His') . '.xlsx';
        $fullPath = $tempPath . '/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($fullPath);

        return $fullPath;
    }
}
