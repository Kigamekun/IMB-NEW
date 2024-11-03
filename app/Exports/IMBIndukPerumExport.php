<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\IMBIndukPerum;

class IMBIndukPerumExport implements ShouldAutoSize,FromCollection, WithCustomStartCell, WithEvents
{

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;





                $sheet->mergeCells('A1:A2');
                $sheet->setCellValue('A1', "No");

                $sheet->mergeCells('B1:B2');
                $sheet->setCellValue('B1', "Nama / Tempat Tinggal");

                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', "Letak Bangunan");

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', "Jenis Bangunan");

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', "Luas Bangunan");

                $sheet->mergeCells('F1:G1');
                $sheet->setCellValue('F1', "Jarak");

                $sheet->setCellValue('G2', "GSB");
                $sheet->setCellValue('F2', "GSP");

                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', "Surat Izin Nomor");

                $sheet->mergeCells('I1:I2');
                $sheet->setCellValue('I1', "Surat Izin Tanggal");

                $sheet->mergeCells('J1:M1');
                $sheet->setCellValue('J1', "Hitungan Pembayaran Bea");
                $sheet->setCellValue('J2', "Bea Pendirian");
                $sheet->setCellValue('K2', "Bea Pemeriksaan");
                $sheet->setCellValue('L2', "Daftar Leges");
                $sheet->setCellValue('M2', "Jumlah");


                $sheet->mergeCells('N1:O1');

                $sheet->setCellValue('N1', "Dibayar oleh yang berwajib");
                $sheet->setCellValue('N2', "Tanggal");
                $sheet->setCellValue('O2', "Kas No");

                $sheet->mergeCells('P1:P2');
                $sheet->setCellValue('P1', "Keterangan");


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
        return IMBIndukPerum::select(
            'id',
            'nama',
            'letak_bangunan',
            'jenis_bangunan',
            'luas_bangunan',
            'gsp',
            'gsb',
            'surat_izin_nomer',
            'surat_izin_tanggal',
            'bea_pendirian',
            'bea_pemeriksaan',
            'daftar_leges',
            'jumlah',
            'tanggal',
            'kas_no',
            'keterangan'
        )->get();
    }
}
