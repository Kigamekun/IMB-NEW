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

class IMBIndukPerumExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
{

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'IMB Induk');
                $sheet->setCellValue('C1', 'Tanggal IMB Induk');
                $sheet->setCellValue('D1', 'No Register');
                $sheet->setCellValue('E1', 'Tanggal Register');
                $sheet->setCellValue('F1', 'Nama');
                $sheet->setCellValue('G1', 'Atas Nama');
                $sheet->setCellValue('H1', 'Lokasi Perumahan');
                $sheet->setCellValue('I1', 'Kecamatan');
                $sheet->setCellValue('J1', 'Desa Kelurahan');

            },
        ];
    }

    public function collection()
    {
        return  \DB::table('imb_induk_perum')
        ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
        ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
        ->select(
            'imb_induk_perum.id',
            'imb_induk',
            'tgl_imb_induk',
            'no_register',
            'tgl_register',
            'nama',
            'atas_nama',
            'lokasi_perumahan',
            'master_district.name as kecamatan',
            'master_subdistrict.name as desa_kelurahan',
        )->get();
    }
}
