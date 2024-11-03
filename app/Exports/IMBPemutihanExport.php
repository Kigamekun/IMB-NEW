<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Sheet;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\IMBPemutihan;

class IMBPemutihanExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
{
    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;





                $sheet->mergeCells('A1:A2');
                $sheet->setCellValue('A1', "No");

                $sheet->mergeCells('B1:B2');
                $sheet->setCellValue('B1', "Nama ");

                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', "Alamat");

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', "Jenis Bangunan");

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', "Letak Bangunan");

                $sheet->mergeCells('F1:F2');
                $sheet->setCellValue('F1', "Luas Bangunan");

                $sheet->mergeCells('G1:H1');
                $sheet->setCellValue('G1', "Jarak");
                $sheet->setCellValue('G2', "G.S.P");
                $sheet->setCellValue('H2', "G.S.B");


                $sheet->mergeCells('I1:J1');
                $sheet->setCellValue('I1', "Surat Izin");
                $sheet->setCellValue('I2', "Nomor");
                $sheet->setCellValue('J2', "Tanggal");

                $sheet->mergeCells('K1:K2');
                $sheet->setCellValue('K1', "Keterangan");


                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $cellRange = 'A1:P1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $cellRange = 'A2:P2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function collection()
    {
        return IMBPemutihan::select(
            'id',
            'nama',
            'alamat',
            'jenis_bangunan',
            'letak_bangunan',
            'luas_bangunan',
            'gsp',
            'gsb',
            'surat_izin_nomer',
            'surat_izin_tanggal',
            'keterangan',
        )->get();
    }
}
