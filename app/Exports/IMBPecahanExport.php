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
use App\Models\IMBPecahan;

class IMBPecahanExport implements ShouldAutoSize,FromCollection, WithCustomStartCell, WithEvents
{

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;


                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'ID IMB Induk');
                $sheet->setCellValue('C1', 'Tanggal IMB Induk');
                $sheet->setCellValue('D1', 'IMB Pecahan');
                $sheet->setCellValue('E1', 'Tanggal IMB Pecahan');
                $sheet->setCellValue('F1', 'No Register');
                $sheet->setCellValue('G1', 'Tanggal Register');
                $sheet->setCellValue('H1', 'Nama');
                $sheet->setCellValue('I1', 'Atas Nama');
                $sheet->setCellValue('J1', 'Jenis Kegiatan');
                $sheet->setCellValue('K1', 'Fungsi Bangunan');
                $sheet->setCellValue('L1', 'Lokasi Perumahan');
                $sheet->setCellValue('M1', 'Kecamatan');
                $sheet->setCellValue('N1', 'Desa Kelurahan');
                $sheet->setCellValue('O1', 'Type');
                $sheet->setCellValue('P1', 'Luas');
                $sheet->setCellValue('Q1', 'Blok');
                $sheet->setCellValue('R1', 'No Blok');
                $sheet->setCellValue('S1', 'Keterangan');

            },
        ];
    }

    public function collection()
    {
        $data = \DB::table('imb_pecahan')
        ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
        ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
        ->join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
        ->join('app_md_fungsibang', 'imb_pecahan.fungsi_bangunan', '=', 'app_md_fungsibang.id_fungsibang')
        ->select(
            'imb_pecahan.id',
            'imb_pecahan.imb_induk_id',
            'imb_pecahan.tgl_imb_induk',
            'imb_pecahan.imb_pecahan',
            'imb_pecahan.tgl_imb_pecahan',
            'imb_pecahan.no_register',
            'imb_pecahan.tgl_register',
            'imb_pecahan.nama',
            'imb_pecahan.atas_nama',
            'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
            'app_md_fungsibang.name_fungsibang as fungsi_bangunan',
            'imb_pecahan.lokasi_perumahan',
            'master_district.name as kecamatan',
            'master_subdistrict.name as desa_kelurahan',
            'imb_pecahan.type',
            'imb_pecahan.luas',
            'imb_pecahan.blok',
            'imb_pecahan.no_blok',
            'imb_pecahan.keterangan'
        )
        ->get();

        $numberedData = $data->map(function ($item, $index) {
            // $item->no = $index + 1; // Start from 1
            return (array) $item;
        });

        return collect($numberedData);
    }
}
