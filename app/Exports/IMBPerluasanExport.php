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

class IMBPerluasanExport implements ShouldAutoSize, FromCollection, WithCustomStartCell, WithEvents
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
                // `imb_pecahan`, `tgl_imb_pecahan`, `imb_perluasan`, `tgl_imb_perluasan`, `no_register`, `tgl_register`, `nama`, `atas_nama`, `jenis_kegiatan`, `fungsi_bangunan`, `lokasi_perumahan`, `kecamatan`, `desa_kelurahan`, `type`, `luas_bangunan_lama`, `luas_bangunan_perluasan`, `blok`, `no_blok`, `keterangan`, `scan_imb`
                $sheet->setCellValue('A1', 'NO');
                $sheet->setCellValue('B1', 'IMB Pecahan');
                $sheet->setCellValue('C1', 'Tanggal IMB Pecahan');
                $sheet->setCellValue('D1', 'IMB Perluasan');
                $sheet->setCellValue('E1', 'Tanggal IMB Perluasan');
                $sheet->setCellValue('F1', 'No Register');
                $sheet->setCellValue('G1', 'Tanggal Register');
                $sheet->setCellValue('H1', 'Nama');
                $sheet->setCellValue('I1', 'Atas Nama');
                $sheet->setCellValue('J1', 'Jenis Kegiatan');
                $sheet->setCellValue('K1', 'Fungsi Bangunan');
                $sheet->setCellValue('L1', 'Lokasi Perumahan');
                $sheet->setCellValue('M1', 'Kabupaten/Kota');
                $sheet->setCellValue('N1', 'Kecamatan');
                $sheet->setCellValue('O1', 'Desa Kelurahan');
                $sheet->setCellValue('P1', 'Type');
                $sheet->setCellValue('Q1', 'Luas Bangunan Lama');
                $sheet->setCellValue('R1', 'Luas Bangunan Perluasan');
                $sheet->setCellValue('S1', 'Blok');
                $sheet->setCellValue('T1', 'No Blok');
                $sheet->setCellValue('U1', 'Keterangan');

            },
        ];
    }

    public function collection()
    {
        $data = \DB::table('imb_perluasan')
            ->join('master_regency', 'imb_perluasan.kabupaten', '=', 'master_regency.code')
            ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
            ->join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('app_md_fungsibang', 'imb_perluasan.fungsi_bangunan', '=', 'app_md_fungsibang.id_fungsibang')
            ->select(
                'imb_perluasan.id',
                'imb_perluasan.imb_pecahan',
                'imb_perluasan.tgl_imb_pecahan',
                'imb_perluasan.imb_perluasan',
                'imb_perluasan.tgl_imb_perluasan',
                'imb_perluasan.no_register',
                'imb_perluasan.tgl_register',
                'imb_perluasan.nama',
                'imb_perluasan.atas_nama',
                'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
                'app_md_fungsibang.name_fungsibang as fungsi_bangunan',
                'imb_perluasan.lokasi_perumahan',
                'master_regency.name as kabupaten',
                'master_district.name as kecamatan',
                'master_subdistrict.name as desa_kelurahan',
                'imb_perluasan.type',
                'imb_perluasan.luas_bangunan_lama',
                'imb_perluasan.luas_bangunan_perluasan',
                'imb_perluasan.blok',
                'imb_perluasan.no_blok',
                'imb_perluasan.keterangan',
            )
            ->get();

        $numberedData = $data->map(function ($item, $index) {
            $item = (array) $item;
            unset($item['id']);
            // dd($item);
            return array_merge(['NO' => $index + 1], (array) $item);
        });

        return collect($numberedData);
    }
}
