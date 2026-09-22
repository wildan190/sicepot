<?php

namespace App\Modules\Stunting\Services;

use App\Modules\Stunting\Models\StuntingPatient;
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
        $desa   = $request->input('desa');
        $bulan  = $request->input('bulan');
        $tahun  = $request->input('tahun');
        $search = $request->input('search');

        $query = StuntingPatient::query();

        if (!empty($desa))   { $query->where('desa', $desa); }
        if (!empty($bulan))  { $query->whereMonth('tanggal_pengukuran', $bulan); }
        if (!empty($tahun))  { $query->whereYear('tanggal_pengukuran', $tahun); }
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('desa')->orderBy('nama')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Balita Stunting');

        // ── Title Header ──────────────────────────────────────────────────────
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'REKAPITULASI DATA BALITA STUNTING — GERCEP PENTING');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:N2');
        $periodeStr = 'Filter Desa: ' . ($desa ?: 'Semua Desa')
            . ' | Bulan: ' . ($bulan ?: '-')
            . ' | Tahun: ' . ($tahun ?: '-')
            . ' | Diunduh: ' . now()->translatedFormat('d F Y H:i');
        $sheet->setCellValue('A2', $periodeStr);
        $sheet->getStyle('A2')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ── Column Headers ────────────────────────────────────────────────────
        $headers = [
            'A4' => 'NO',
            'B4' => 'NIK',
            'C4' => 'NAMA',
            'D4' => 'JK',
            'E4' => 'TGL LAHIR',
            'F4' => 'DESA',
            'G4' => 'POSYANDU',
            'H4' => 'BB LAHIR (kg)',
            'I4' => 'BERAT (kg)',
            'J4' => 'TINGGI (cm)',
            'K4' => 'TB/U',
            'L4' => 'BB/U',
            'M4' => 'BB/TB',
            'N4' => 'TGL PENGUKURAN',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF16A34A']], // green-600
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBBF7D0']]],
        ];
        $sheet->getStyle('A4:N4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // ── Data Rows ─────────────────────────────────────────────────────────
        $rowNum = 5;
        foreach ($patients as $idx => $p) {
            $sheet->setCellValue('A' . $rowNum, $idx + 1);
            $sheet->setCellValueExplicit('B' . $rowNum, (string) $p->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $p->nama);
            $sheet->setCellValue('D' . $rowNum, $p->jenis_kelamin ?: '-');
            $sheet->setCellValue('E' . $rowNum, $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-');
            $sheet->setCellValue('F' . $rowNum, $p->desa ?: '-');
            $sheet->setCellValue('G' . $rowNum, $p->posyandu ?: '-');
            $sheet->setCellValue('H' . $rowNum, $p->bb_lahir ? number_format((float) $p->bb_lahir, 2) : '-');
            $sheet->setCellValue('I' . $rowNum, $p->berat ? number_format((float) $p->berat, 2) : '-');
            $sheet->setCellValue('J' . $rowNum, $p->tinggi ? number_format((float) $p->tinggi, 1) : '-');
            $sheet->setCellValue('K' . $rowNum, $p->tbu_kategori ?: '-');
            $sheet->setCellValue('L' . $rowNum, $p->bbu_kategori ?: '-');
            $sheet->setCellValue('M' . $rowNum, $p->bbtb_kategori ?: '-');
            $sheet->setCellValue('N' . $rowNum, $p->tanggal_pengukuran ? $p->tanggal_pengukuran->format('d/m/Y') : '-');

            $dataRowStyle = [
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFE2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ];
            if ($rowNum % 2 === 0) {
                $dataRowStyle['fill'] = ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0FDF4']];
            }
            $sheet->getStyle('A' . $rowNum . ':N' . $rowNum)->applyFromArray($dataRowStyle);
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($rowNum)->setRowHeight(20);
            $rowNum++;
        }

        // ── Auto width ────────────────────────────────────────────────────────
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $tempPath = storage_path('app/temp_exports');
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $filename = 'Laporan_Stunting_' . date('Ymd_His') . '.xlsx';
        $fullPath = $tempPath . '/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($fullPath);

        return $fullPath;
    }
}
