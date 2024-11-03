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
use App\Models\IMBPelunasan;

class IMBPelunasanExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
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
                $sheet->setCellValue('B1', "Nama Pemohon");

                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', "Alamat");

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', "Lokasi Bangunan");

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', "Jenis Bangunan");

                $sheet->mergeCells('F1:G1');
                $sheet->setCellValue('F1', "Luas Bangunan");
                $sheet->setCellValue('F2', "Lama");
                $sheet->setCellValue('G2', "Perluasan");


                $sheet->mergeCells('H1:I1');
                $sheet->setCellValue('H1', "SK IMB Lama");
                $sheet->setCellValue('H2', "Nomor");
                $sheet->setCellValue('I2', "Tanggal");


                $sheet->mergeCells('J1:K1');
                $sheet->setCellValue('J1', "SK IMB Perluasan");
                $sheet->setCellValue('J2', "Nomor");
                $sheet->setCellValue('K2', "Tanggal");

                $sheet->mergeCells('L1:M1');
                $sheet->setCellValue('L1', "Retribusi");
                $sheet->setCellValue('L2', "Jumlah Rp.");
                $sheet->setCellValue('M2', "Tanggal");

                $sheet->mergeCells('N1:N2');
                $sheet->setCellValue('N1', "Keterangan");


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
        return IMBPelunasan::select(
            'id',
            'nama_pemohon',
            'alamat',
            'lokasi_bangunan',
            'jenis_bangunan',
            'luas_bangunan_lama',
            'luas_bangunan_perlunasan',
            'sk_imb_lama_nomer',
            'sk_imb_lama_tanggal',
            'sk_imb_pelunasan_nomer',
            'sk_imb_pelunasan_tanggal',
            'retribusi_jumlah',
            'retribusi_tanggal',
            'keterangan',
        )->get();
    }
}
