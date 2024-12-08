<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;

class IMBIndukNonPerumExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
{
    /**
     * Define the starting cell for the export.
     */
    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * Register events for custom formatting and merging cells.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set header
                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'IMB Induk');
                $sheet->setCellValue('C1', 'Tanggal IMB Induk');
                $sheet->setCellValue('D1', 'No Register');
                $sheet->setCellValue('E1', 'Tanggal Register');
                $sheet->setCellValue('F1', 'Nama');
                $sheet->setCellValue('G1', 'Atas Nama');
                $sheet->setCellValue('H1', 'Lokasi Perumahan');
                $sheet->setCellValue('I1', 'Kecamatan');
                $sheet->setCellValue('J1', 'Desa / Kelurahan');
                $sheet->setCellValue('K1', 'Jenis Kegiatan');
                $sheet->setCellValue('L1', 'Fungsi Bangunan');
                $sheet->setCellValue('M1', 'Type');
                $sheet->setCellValue('N1', 'Luas Bangunan');
                $sheet->setCellValue('O1', 'Jumlah Unit');
                $sheet->setCellValue('P1', 'Keterangan');
                $sheet->setCellValue('Q1', 'Scan IMB');

                // Fetch data
                $data = $this->getMergedData();
                $currentRow = 2; // Start from the second row (after header)

                foreach ($data as $key => $row) {
                    $startRow = $currentRow;
                    $endRow = $currentRow + count($row['items']) - 1;

                    // Set merged values for parent columns
                    $sheet->setCellValue("A{$startRow}", $key + 1); // NO
                    $sheet->setCellValue("B{$startRow}", $row['main']['imb_induk']);
                    $sheet->setCellValue("C{$startRow}", $row['main']['tgl_imb_induk']);
                    $sheet->setCellValue("D{$startRow}", $row['main']['no_register']);
                    $sheet->setCellValue("E{$startRow}", $row['main']['tgl_register']);
                    $sheet->setCellValue("F{$startRow}", $row['main']['nama']);
                    $sheet->setCellValue("G{$startRow}", $row['main']['atas_nama']);
                    $sheet->setCellValue("H{$startRow}", $row['main']['lokasi_perumahan']);
                    $sheet->setCellValue("I{$startRow}", $row['main']['kecamatan']);
                    $sheet->setCellValue("J{$startRow}", $row['main']['desa_kelurahan']);

                    // Merge cells for parent data if there are multiple items
                    if (count($row['items']) > 1) {
                        $sheet->mergeCells("A{$startRow}:A{$endRow}");
                        $sheet->mergeCells("B{$startRow}:B{$endRow}");
                        $sheet->mergeCells("C{$startRow}:C{$endRow}");
                        $sheet->mergeCells("D{$startRow}:D{$endRow}");
                        $sheet->mergeCells("E{$startRow}:E{$endRow}");
                        $sheet->mergeCells("F{$startRow}:F{$endRow}");
                        $sheet->mergeCells("G{$startRow}:G{$endRow}");
                        $sheet->mergeCells("H{$startRow}:H{$endRow}");
                        $sheet->mergeCells("I{$startRow}:I{$endRow}");
                        $sheet->mergeCells("J{$startRow}:J{$endRow}");
                    }

                    // Add item rows
                    foreach ($row['items'] as $item) {
                        $sheet->setCellValue("K{$currentRow}", $item['jenis_kegiatan']);
                        $sheet->setCellValue("L{$currentRow}", $item['fungsi_bangunan']);
                        $sheet->setCellValue("M{$currentRow}", $item['type']);
                        $sheet->setCellValue("N{$currentRow}", $item['luas_bangunan']);
                        $sheet->setCellValue("O{$currentRow}", $item['jumlah_unit']);
                        $sheet->setCellValue("P{$currentRow}", $item['keterangan']);
                        $sheet->setCellValue("Q{$currentRow}", $item['scan_imb']);

                        $currentRow++;
                    }
                }
            },
        ];
    }

    /**
     * Fetch data to be exported as a collection.
     */
    public function collection()
    {
        return collect($this->getMergedData());
    }

    /**
     * Get the merged data from the database.
     */
    private function getMergedData()
    {
        // Fetch main data with items
        $data = DB::table('imb_induk_non_perum')
            ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
            ->join('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
            ->select(
                'imb_induk_non_perum.id',
                'imb_induk',
                'tgl_imb_induk',
                'no_register',
                'tgl_register',
                'nama',
                'atas_nama',
                'lokasi_perumahan',
                'master_district.name as kecamatan',
                'master_subdistrict.name as desa_kelurahan',
                'item_imb_induk_non_perum.jenis_kegiatan',
                'item_imb_induk_non_perum.fungsi_bangunan',
                'item_imb_induk_non_perum.type',
                'item_imb_induk_non_perum.luas_bangunan',
                'item_imb_induk_non_perum.jumlah_unit',
                'item_imb_induk_non_perum.keterangan',
                'item_imb_induk_non_perum.scan_imb'
            )
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return [
                    'main' => (array)$group->first(), // Convert stdClass to array
                    'items' => $group->map(fn ($item) => (array)$item)->values() // Convert each item to array
                ];
            })
            ->values()
            ->toArray(); // Convert entire collection to array

        return $data;
    }
}
