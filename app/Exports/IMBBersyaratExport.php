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
use App\Models\IMBBersyarat;

class IMBBersyaratExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
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
                $sheet->setCellValue('D1', "Letak Bangunan");

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', "Luas Bangunan");

                $sheet->mergeCells('F1:F2');
                $sheet->setCellValue('F1', "Jenis Bangunan");

                $sheet->mergeCells('G1:H1');
                $sheet->setCellValue('G1', "Ketentuan Jarak");
                $sheet->setCellValue('G2', "GSP");
                $sheet->setCellValue('H2', "GSB");

                $sheet->mergeCells('I1:J1');
                $sheet->setCellValue('I1', "Keadaan Jarak");
                $sheet->setCellValue('I2', "GSP");
                $sheet->setCellValue('J2', "GSB");

                $sheet->mergeCells('K1:L1');
                $sheet->setCellValue('K1', "Surat Izin");
                $sheet->setCellValue('K2', "Surat Izin Nomor");
                $sheet->setCellValue('L2', "Surat Izin Tanggal");

                $sheet->mergeCells('M1:M2');
                $sheet->setCellValue('M1', "Jangka Waktu");


                $sheet->mergeCells('N1:P1');
                $sheet->setCellValue('N1', "Harus dibongkar batas waktu s/d");
                $sheet->setCellValue('N2', "Tanggal");
                $sheet->setCellValue('O2', "Bulan");
                $sheet->setCellValue('P2', "Tahun");

                $sheet->mergeCells('Q1:Q2');
                $sheet->setCellValue('Q1', "Keterangan");


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
        return IMBBersyarat::select(
            'id',
            'nama',
            'alamat',
            'letak_bangunan',
            'luas_bangunan',
            'jenis_bangunan',
            'ketentuan_jarak_gsp',
            'ketentuan_jarak_gsb',
            'keadaan_jarak_gsp',
            'keadaan_jarak_gsb',
            'surat_izin_nomer',
            'surat_izin_tanggal',
            'jangka_waktu',
            'tanggal',
            'bulan',
            'tahun',
            'keterangan',
        )->get();
    }
}
