<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use \Yajra\DataTables\DataTables;
class RekapController extends Controller
{
    // Rekap 1
    public function RekapPenerbitan(Request $request)
    {

        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('YEAR(tgl_imb_induk) AS tahun, COUNT(*) AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                ->from('imb_induk_perum')
                ->groupByRaw('YEAR(tgl_imb_induk)')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('YEAR(tgl_imb_pecahan) AS tahun, 0 AS imb_induk_perumahan, COUNT(*) AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                        ->groupByRaw('YEAR(tgl_imb_pecahan)')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('YEAR(tgl_imb_perluasan) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, COUNT(*) AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                        ->groupByRaw('YEAR(tgl_imb_perluasan)')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('YEAR(tgl_imb_induk) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, COUNT(*) AS imb_induk_non_perumahan')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                );
        })
            ->selectRaw('tahun')
            ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
            ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
            ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
            ->selectRaw('SUM(imb_induk_non_perumahan) AS imb_induk_non_perumahan')
            ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_induk_non_perumahan) AS jumlah_imb')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get();

        $tahun = '2024';
        return view('rekap.rekap-imb.rekap-penerbitan', compact('tahun', 'data'));
    }

    // Rekap 1.1
    public function RekapPenerbitanDetail(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('YEAR(tgl_imb_induk) AS tahun, COUNT(*) AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_non_perusahaan, 0 AS imb_perorangan, 0 AS imb_sosbud, 0 AS imb_pemutihan, 0 AS imb_bersyarat, 0 AS imb_lainnya')
                ->from('imb_induk_perum')
                ->groupByRaw('YEAR(tgl_imb_induk)')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('YEAR(tgl_imb_pecahan) AS tahun, 0 AS imb_induk_perumahan, COUNT(*) AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_non_perusahaan, 0 AS imb_perorangan, 0 AS imb_sosbud, 0 AS imb_pemutihan, 0 AS imb_bersyarat, 0 AS imb_lainnya')
                        ->groupByRaw('YEAR(tgl_imb_pecahan)')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('YEAR(tgl_imb_perluasan) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, COUNT(*) AS imb_perluasan, 0 AS imb_non_perusahaan, 0 AS imb_perorangan, 0 AS imb_sosbud, 0 AS imb_pemutihan, 0 AS imb_bersyarat, 0 AS imb_lainnya')
                        ->groupByRaw('YEAR(tgl_imb_perluasan)')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw("
                        YEAR(tgl_imb_induk) AS tahun,
                        0 AS imb_induk_perumahan,
                        0 AS imb_pecahan,
                        0 AS imb_perluasan,
                        COUNT(CASE WHEN jenis = 1 THEN 1 END) AS imb_non_perusahaan,
                        COUNT(CASE WHEN jenis = 2 THEN 1 END) AS imb_perorangan,
                        COUNT(CASE WHEN jenis = 3 THEN 1 END) AS imb_sosbud,
                        COUNT(CASE WHEN jenis = 4 THEN 1 END) AS imb_pemutihan,
                        COUNT(CASE WHEN jenis = 5 THEN 1 END) AS imb_bersyarat,
                        COUNT(CASE WHEN jenis NOT IN (1, 2, 3, 4, 5) THEN 1 END) AS imb_lainnya
                    ")
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                );
        })
            ->selectRaw('tahun')
            ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
            ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
            ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
            ->selectRaw('SUM(imb_non_perusahaan) AS imb_non_perusahaan')
            ->selectRaw('SUM(imb_perorangan) AS imb_perorangan')
            ->selectRaw('SUM(imb_sosbud) AS imb_sosbud')
            ->selectRaw('SUM(imb_pemutihan) AS imb_pemutihan')
            ->selectRaw('SUM(imb_bersyarat) AS imb_bersyarat')
            ->selectRaw('SUM(imb_lainnya) AS imb_lainnya')
            ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_non_perusahaan + imb_perorangan + imb_sosbud + imb_pemutihan + imb_bersyarat + imb_lainnya) AS jumlah_imb')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get();


        $tahun = '2024';
        return view('rekap.rekap-imb.rekap-penerbitan-detail', compact('tahun', 'data'));
    }

    // Rekap 2
    public function RekapUnitDanFungsi(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('YEAR(tgl_imb_induk) as tahun, COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan, SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan,SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as campuran_unit')
                ->from('imb_induk_perum')
                ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                ->groupByRaw('YEAR(tgl_imb_induk)')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('YEAR(tgl_imb_induk) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, COUNT(*) as imb_pecahan, SUM(luas) as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan,SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (1, 6) THEN imb_pecahan.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 2 THEN imb_pecahan.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN imb_pecahan.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 9 THEN imb_pecahan.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 3 THEN imb_pecahan.jumlah_unit ELSE 0 END) as campuran_unit')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('YEAR(tgl_imb_perluasan) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, COUNT(*) as imb_perluasan, COALESCE(SUM(luas_bangunan_perluasan), 0) as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan,SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (1, 6) THEN imb_perluasan.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 2 THEN imb_perluasan.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN imb_perluasan.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 9 THEN imb_perluasan.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 3 THEN imb_perluasan.jumlah_unit ELSE 0 END) as campuran_unit')
                        ->groupByRaw('YEAR(tgl_imb_perluasan)')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('YEAR(tgl_imb_induk) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, COUNT(DISTINCT imb_induk_non_perum.id) as imb_non_perumahan, COALESCE(SUM(item_imb_induk_non_perum.jumlah_unit), 0) as unit_non_perumahan, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit')
                        ->leftJoin('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                );
        })
            ->selectRaw('tahun, SUM(imb_induk_perumahan) as imb_induk_perumahan, SUM(unit_induk_perumahan) as unit_induk_perumahan, SUM(imb_pecahan) as imb_pecahan, SUM(unit_pecahan) as unit_pecahan, SUM(imb_perluasan) as imb_perluasan, SUM(unit_perluasan) as unit_perluasan, SUM(imb_non_perumahan) as imb_non_perumahan, SUM(unit_non_perumahan) as unit_non_perumahan, SUM(hunian_imb) as hunian_imb, SUM(hunian_unit) as hunian_unit, SUM(usaha_imb) as usaha_imb, SUM(usaha_unit) as usaha_unit, SUM(sosbud_imb) as sosbud_imb, SUM(sosbud_unit) as sosbud_unit, SUM(khusus_imb) as khusus_imb, SUM(khusus_unit) as khusus_unit, SUM(campuran_imb) as campuran_imb, SUM(campuran_unit) as campuran_unit')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get();

        return view('rekap.rekap-imb.rekap-unit-dan-fungsi', compact('data'));

    }

    // Rekap 2.1
    public function RekapUnitDanFungsiDetail(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('
                YEAR(tgl_imb_induk) as tahun,
                COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan,
                SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan,
                0 as imb_pecahan, 0 as unit_pecahan,
                0 as imb_perluasan, 0 as unit_perluasan,
                0 as imb_non_perumahan_perusahaan,
                0 as unit_non_perumahan_perusahaan,
                0 as imb_non_perumahan_perorangan,
                0 as unit_non_perumahan_perorangan,
                0 as imb_non_perumahan_sosbud,
                0 as unit_non_perumahan_sosbud,
                0 as imb_pemutihan,
                0 as unit_pemutihan,
                0 as imb_bersyarat,
                0 as unit_bersyarat,
                0 as imb_lainnya,
                0 as unit_lainnya,
               SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as campuran_unit
            ')
                ->from('imb_induk_perum')
                ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                ->groupByRaw('YEAR(tgl_imb_induk)')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('
                        YEAR(tgl_imb_induk) as tahun,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        COUNT(*) as imb_pecahan, COUNT(*) as unit_pecahan,
                        0 as imb_perluasan, 0 as unit_perluasan,
                        0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                        0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                        0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                        0 as imb_pemutihan, 0 as unit_pemutihan,
                        0 as imb_bersyarat, 0 as unit_bersyarat,
                        0 as imb_lainnya, 0 as unit_lainnya,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                    ')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('
                        YEAR(tgl_imb_perluasan) as tahun,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        0 as imb_pecahan, 0 as unit_pecahan,
                        COUNT(*) as imb_perluasan, COUNT(*) as unit_perluasan,
                        0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                        0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                        0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                        0 as imb_pemutihan, 0 as unit_pemutihan,
                        0 as imb_bersyarat, 0 as unit_bersyarat,
                        0 as imb_lainnya, 0 as unit_lainnya,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                    ')
                        ->groupByRaw('YEAR(tgl_imb_perluasan)')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('
                        YEAR(tgl_imb_induk) as tahun,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        0 as imb_pecahan, 0 as unit_pecahan,
                        0 as imb_perluasan, 0 as unit_perluasan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN 1 ELSE 0 END) as imb_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN 1 ELSE 0 END) as imb_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN 1 ELSE 0 END) as imb_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN 1 ELSE 0 END) as imb_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN 1 ELSE 0 END) as imb_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN 1 ELSE 0 END) as imb_lainnya,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_lainnya,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit
                    ')
                        ->leftJoin('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                );
        })
            ->selectRaw('
            tahun,
            SUM(imb_induk_perumahan) as imb_induk_perumahan,
            SUM(unit_induk_perumahan) as unit_induk_perumahan,
            SUM(imb_pecahan) as imb_pecahan,
            SUM(unit_pecahan) as unit_pecahan,
            SUM(imb_perluasan) as imb_perluasan,
            SUM(unit_perluasan) as unit_perluasan,
            SUM(imb_non_perumahan_perusahaan) as imb_non_perumahan_perusahaan,
            SUM(unit_non_perumahan_perusahaan) as unit_non_perumahan_perusahaan,
            SUM(imb_non_perumahan_perorangan) as imb_non_perumahan_perorangan,
            SUM(unit_non_perumahan_perorangan) as unit_non_perumahan_perorangan,
            SUM(imb_non_perumahan_sosbud) as imb_non_perumahan_sosbud,
            SUM(unit_non_perumahan_sosbud) as unit_non_perumahan_sosbud,
            SUM(imb_pemutihan) as imb_pemutihan,
            SUM(unit_pemutihan) as unit_pemutihan,
            SUM(imb_bersyarat) as imb_bersyarat,
            SUM(unit_bersyarat) as unit_bersyarat,
            SUM(imb_lainnya) as imb_lainnya,
            SUM(unit_lainnya) as unit_lainnya,
            SUM(hunian_imb) as hunian_imb, SUM(hunian_unit) as hunian_unit,
            SUM(usaha_imb) as usaha_imb, SUM(usaha_unit) as usaha_unit,
            SUM(sosbud_imb) as sosbud_imb, SUM(sosbud_unit) as sosbud_unit,
            SUM(khusus_imb) as khusus_imb, SUM(khusus_unit) as khusus_unit,
            SUM(campuran_imb) as campuran_imb, SUM(campuran_unit) as campuran_unit
        ')
            ->groupBy('tahun')
            ->orderBy('tahun', 'DESC')
            ->get();


        return view('rekap.rekap-imb.rekap-unit-dan-fungsi-detail', compact('data'));
    }

    // Rekap 3
    public function RekapLokasi()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::select("
         WITH combined_data AS (
            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_induk_perum' as source,
                id
            FROM imb_induk_perum

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_pecahan' as source,
                id
            FROM imb_pecahan

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_perluasan' as source,
                id
            FROM imb_perluasan

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_non_perumahan' as source,
                id
            FROM imb_induk_non_perum
        )
        SELECT
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun,
            SUM(CASE WHEN source = 'imb_induk_perum' THEN 1 ELSE 0 END) as imb_induk_perum,
            SUM(CASE WHEN source = 'imb_pecahan' THEN 1 ELSE 0 END) as imb_pecahan,
            SUM(CASE WHEN source = 'imb_perluasan' THEN 1 ELSE 0 END) as imb_perluasan,
            SUM(CASE WHEN source = 'imb_non_perumahan' THEN 1 ELSE 0 END) as imb_non_perumahan,
            COUNT(*) as jumlah_imb
        FROM combined_data
        GROUP BY
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun
        ORDER BY
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun;
     ");

        return view('rekap.rekap-imb.rekap-lokasi', compact('data'));
    }

    // Rekap 3.1
    public function RekapLokasiDetail()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::select("
        WITH combined_data AS (
            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_induk_perum' as source,
                id,
                NULL as jenis
            FROM imb_induk_perum

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_pecahan' as source,
                id,
                NULL as jenis
            FROM imb_pecahan

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_perluasan' as source,
                id,
                NULL as jenis
            FROM imb_perluasan

            UNION ALL

            SELECT
                kabupaten,
                kecamatan,
                desa_kelurahan,
                YEAR(tgl_register) as tahun,
                'imb_non_perumahan' as source,
                id,
                jenis
            FROM imb_induk_non_perum
        )
        SELECT
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun,
            SUM(CASE WHEN source = 'imb_induk_perum' THEN 1 ELSE 0 END) as imb_induk_perum,
            SUM(CASE WHEN source = 'imb_pecahan' THEN 1 ELSE 0 END) as imb_pecahan,
            SUM(CASE WHEN source = 'imb_perluasan' THEN 1 ELSE 0 END) as imb_perluasan,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 1 THEN 1 ELSE 0 END) as imb_non_perusahaan,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 2 THEN 1 ELSE 0 END) as imb_non_perorangan,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 3 THEN 1 ELSE 0 END) as imb_non_sosial_budaya,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 4 THEN 1 ELSE 0 END) as imb_pemutihan,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 5 THEN 1 ELSE 0 END) as imb_bersyarat,
            SUM(CASE WHEN source = 'imb_non_perumahan' AND jenis = 6 THEN 1 ELSE 0 END) as imb_lainnya,
            COUNT(DISTINCT id) as jumlah_imb
        FROM combined_data
        GROUP BY
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun
        ORDER BY
            kabupaten,
            kecamatan,
            desa_kelurahan,
            tahun;
      ");

        return view('rekap.rekap-imb.rekap-lokasi-detail', compact('data'));
    }

    // Rekap 4
    public function RekapUnitFungsiDanLokasi()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('
                    YEAR(tgl_imb_induk) as tahun,
                    kabupaten,
                    kecamatan,
                    desa_kelurahan,
                    COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan,
                    SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan,
                    0 as imb_pecahan,
                    0 as unit_pecahan,
                    0 as imb_perluasan,
                    0 as unit_perluasan,
                    0 as imb_non_perumahan,
                    0 as unit_non_perumahan,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as campuran_unit
                ')
                ->from('imb_induk_perum')
                ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                ->groupByRaw('YEAR(tgl_imb_induk), kabupaten, kecamatan, desa_kelurahan')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('
                            YEAR(tgl_imb_induk) as tahun,
                            kabupaten,
                            kecamatan,
                            desa_kelurahan,
                            0 as imb_induk_perumahan,
                            0 as unit_induk_perumahan,
                            COUNT(*) as imb_pecahan,
                            SUM(luas) as unit_pecahan,
                            0 as imb_perluasan,
                            0 as unit_perluasan,
                            0 as imb_non_perumahan,
                            0 as unit_non_perumahan,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (1, 6) THEN imb_pecahan.jumlah_unit ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 2 THEN imb_pecahan.jumlah_unit ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN imb_pecahan.jumlah_unit ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 9 THEN imb_pecahan.jumlah_unit ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN imb_pecahan.fungsi_bangunan = 3 THEN imb_pecahan.jumlah_unit ELSE 0 END) as campuran_unit
                        ')
                        ->groupByRaw('YEAR(tgl_imb_induk), kabupaten, kecamatan, desa_kelurahan')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('
                            YEAR(tgl_imb_perluasan) as tahun,
                            kabupaten,
                            kecamatan,
                            desa_kelurahan,
                            0 as imb_induk_perumahan,
                            0 as unit_induk_perumahan,
                            0 as imb_pecahan,
                            0 as unit_pecahan,
                            COUNT(*) as imb_perluasan,
                            COALESCE(SUM(luas_bangunan_perluasan), 0) as unit_perluasan,
                            0 as imb_non_perumahan,
                            0 as unit_non_perumahan,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (1, 6) THEN imb_perluasan.jumlah_unit ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 2 THEN imb_perluasan.jumlah_unit ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN imb_perluasan.jumlah_unit ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 9 THEN imb_perluasan.jumlah_unit ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN imb_perluasan.fungsi_bangunan = 3 THEN imb_perluasan.jumlah_unit ELSE 0 END) as campuran_unit
                        ')
                        ->groupByRaw('YEAR(tgl_imb_perluasan), kabupaten, kecamatan, desa_kelurahan')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('
                            YEAR(tgl_imb_induk) as tahun,
                            kabupaten,
                            kecamatan,
                            desa_kelurahan,
                            0 as imb_induk_perumahan,
                            0 as unit_induk_perumahan,
                            0 as imb_pecahan,
                            0 as unit_pecahan,
                            0 as imb_perluasan,
                            0 as unit_perluasan,
                            COUNT(DISTINCT imb_induk_non_perum.id) as imb_non_perumahan,
                            COALESCE(SUM(item_imb_induk_non_perum.jumlah_unit), 0) as unit_non_perumahan,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit
                        ')
                        ->leftJoin('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
                        ->groupByRaw('YEAR(tgl_imb_induk), kabupaten, kecamatan, desa_kelurahan')
                );
        })
            ->selectRaw('
            tahun,
            kabupaten,
            kecamatan,
            desa_kelurahan,
            SUM(imb_induk_perumahan) as imb_induk_perumahan,
            SUM(unit_induk_perumahan) as unit_induk_perumahan,
            SUM(imb_pecahan) as imb_pecahan,
            SUM(unit_pecahan) as unit_pecahan,
            SUM(imb_perluasan) as imb_perluasan,
            SUM(unit_perluasan) as unit_perluasan,
            SUM(imb_non_perumahan) as imb_non_perumahan,
            SUM(unit_non_perumahan) as unit_non_perumahan,
            SUM(hunian_imb) as hunian_imb,
            SUM(hunian_unit) as hunian_unit,
            SUM(usaha_imb) as usaha_imb,
            SUM(usaha_unit) as usaha_unit,
            SUM(sosbud_imb) as sosbud_imb,
            SUM(sosbud_unit) as sosbud_unit,
            SUM(khusus_imb) as khusus_imb,
            SUM(khusus_unit) as khusus_unit,
            SUM(campuran_imb) as campuran_imb,
            SUM(campuran_unit) as campuran_unit
        ')
            ->groupBy('tahun', 'kabupaten', 'kecamatan', 'desa_kelurahan')
            ->orderBy('tahun', 'DESC')
            ->get();

        return view('rekap.rekap-imb.rekap-unit-fungsi-dan-lokasi', compact('data'));
    }

    // Rekap 4.1
    public function RekapUnitFungsiDanLokasiDetail()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table(function ($query) {
            $query->selectRaw('
                YEAR(tgl_imb_induk) as tahun,
                imb_induk_perum.kabupaten,
                imb_induk_perum.kecamatan,
                imb_induk_perum.desa_kelurahan,
                COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan,
                SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan,
                0 as imb_pecahan, 0 as unit_pecahan,
                0 as imb_perluasan, 0 as unit_perluasan,
                0 as imb_non_perumahan_perusahaan,
                0 as unit_non_perumahan_perusahaan,
                0 as imb_non_perumahan_perorangan,
                0 as unit_non_perumahan_perorangan,
                0 as imb_non_perumahan_sosbud,
                0 as unit_non_perumahan_sosbud,
                0 as imb_pemutihan,
                0 as unit_pemutihan,
                0 as imb_bersyarat,
                0 as unit_bersyarat,
                0 as imb_lainnya,
                0 as unit_lainnya,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as campuran_unit
            ')
                ->from('imb_induk_perum')
                ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                ->groupByRaw('YEAR(tgl_imb_induk), imb_induk_perum.kabupaten, imb_induk_perum.kecamatan, imb_induk_perum.desa_kelurahan')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('
                        YEAR(tgl_imb_induk) as tahun,
                        imb_pecahan.kabupaten,
                        imb_pecahan.kecamatan,
                        imb_pecahan.desa_kelurahan,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        COUNT(*) as imb_pecahan, COUNT(*) as unit_pecahan,
                        0 as imb_perluasan, 0 as unit_perluasan,
                        0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                        0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                        0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                        0 as imb_pemutihan, 0 as unit_pemutihan,
                        0 as imb_bersyarat, 0 as unit_bersyarat,
                        0 as imb_lainnya, 0 as unit_lainnya,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                    ')
                        ->groupByRaw('YEAR(tgl_imb_induk), imb_pecahan.kabupaten, imb_pecahan.kecamatan, imb_pecahan.desa_kelurahan')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('
                        YEAR(tgl_imb_perluasan) as tahun,
                        imb_perluasan.kabupaten,
                        imb_perluasan.kecamatan,
                        imb_perluasan.desa_kelurahan,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        0 as imb_pecahan, 0 as unit_pecahan,
                        COUNT(*) as imb_perluasan, COUNT(*) as unit_perluasan,
                        0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                        0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                        0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                        0 as imb_pemutihan, 0 as unit_pemutihan,
                        0 as imb_bersyarat, 0 as unit_bersyarat,
                        0 as imb_lainnya, 0 as unit_lainnya,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                    ')
                        ->groupByRaw('YEAR(tgl_imb_perluasan), imb_perluasan.kabupaten, imb_perluasan.kecamatan, imb_perluasan.desa_kelurahan')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('
                        YEAR(tgl_imb_induk) as tahun,
                        imb_induk_non_perum.kabupaten,
                        imb_induk_non_perum.kecamatan,
                        imb_induk_non_perum.desa_kelurahan,
                        0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                        0 as imb_pecahan, 0 as unit_pecahan,
                        0 as imb_perluasan, 0 as unit_perluasan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN 1 ELSE 0 END) as imb_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN 1 ELSE 0 END) as imb_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN 1 ELSE 0 END) as imb_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN 1 ELSE 0 END) as imb_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN 1 ELSE 0 END) as imb_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN 1 ELSE 0 END) as imb_lainnya,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_lainnya,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit
                    ')
                        ->leftJoin('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
                        ->groupByRaw('YEAR(tgl_imb_induk), imb_induk_non_perum.kabupaten, imb_induk_non_perum.kecamatan, imb_induk_non_perum.desa_kelurahan')
                );
        })
            ->selectRaw('
            tahun,
            kabupaten,
            kecamatan,
            desa_kelurahan,
            SUM(imb_induk_perumahan) as imb_induk_perumahan,
            SUM(unit_induk_perumahan) as unit_induk_perumahan,
            SUM(imb_pecahan) as imb_pecahan,
            SUM(unit_pecahan) as unit_pecahan,
            SUM(imb_perluasan) as imb_perluasan,
            SUM(unit_perluasan) as unit_perluasan,
            SUM(imb_non_perumahan_perusahaan) as imb_non_perumahan_perusahaan,
            SUM(unit_non_perumahan_perusahaan) as unit_non_perumahan_perusahaan,
            SUM(imb_non_perumahan_perorangan) as imb_non_perumahan_perorangan,
            SUM(unit_non_perumahan_perorangan) as unit_non_perumahan_perorangan,
            SUM(imb_non_perumahan_sosbud) as imb_non_perumahan_sosbud,
            SUM(unit_non_perumahan_sosbud) as unit_non_perumahan_sosbud,
            SUM(imb_pemutihan) as imb_pemutihan,
            SUM(unit_pemutihan) as unit_pemutihan,
            SUM(imb_bersyarat) as imb_bersyarat,
            SUM(unit_bersyarat) as unit_bersyarat,
            SUM(imb_lainnya) as imb_lainnya,
            SUM(unit_lainnya) as unit_lainnya,
            SUM(hunian_imb) as hunian_imb,
            SUM(hunian_unit) as hunian_unit,
            SUM(usaha_imb) as usaha_imb,
            SUM(usaha_unit) as usaha_unit,
            SUM(sosbud_imb) as sosbud_imb,
            SUM(sosbud_unit) as sosbud_unit,
            SUM(khusus_imb) as khusus_imb,
            SUM(khusus_unit) as khusus_unit,
            SUM(campuran_imb) as campuran_imb,
            SUM(campuran_unit) as campuran_unit
        ')
            ->groupBy('tahun', 'kabupaten', 'kecamatan', 'desa_kelurahan')
            ->orderBy('tahun', 'DESC')
            ->get();


        return view('rekap.rekap-imb.rekap-unit-fungsi-dan-lokasi-detail', compact('data'));
    }

    // REKAP 5
    public function DetailIMBInduk(Request $request)
    {
        return view('rekap.detail-imb-induk');
    }

    /* public function DetailIMBIndukList(Request $request)
     {
         if ($request->ajax()) {
             // $nama_pengembang = $_GET['nama_pengembang'] ?? null;
             // // $nama_perumahan = $_GET['nama_perumahan'] ?? null;
             $nama_pengembang = $request->input('nama_pengembang');
             $nama_perumahan = $request->input('nama_perumahan');
            // $startYear = $request->input('startYear');
            // $endYear = $request->input('endYear');
             $startYear = $_GET['startYear'] ?? null;
             $endYear = $_GET['endYear'] ?? null;

             $data = DB::table(function ($query) use ($nama_pengembang, $nama_perumahan) {
                 $baseQuery = $query->selectRaw('
             YEAR(tgl_imb_induk) AS tahun,
             nama,
             COUNT(*) AS imb_induk_perumahan,
             0 AS imb_pecahan,
             0 AS imb_perluasan,
             0 AS imb_non_perusahaan,
             0 AS imb_perorangan,
             0 AS imb_sosbud,
             0 AS imb_pemutihan,
             0 AS imb_bersyarat,
             0 AS imb_lainnya
         ')
                     ->from('imb_induk_perum');

                 // Tambahkan filter where jika parameter ada
                 if ($nama_pengembang) {
                     $baseQuery->where('nama', '=', $nama_pengembang);
                 }
                 if ($nama_perumahan) {
                     $baseQuery->where('lokasi_perumahan', '=', $nama_perumahan);
                 }
              // if ($startYear && $endYear) {
                 //   $baseQuery->where('tgl_imb_induk', '>=', $startYear);
                 //   $baseQuery->where('tgl_imb_induk', '<=', $endYear);
              //  }

                 $baseQuery->groupByRaw('YEAR(tgl_imb_induk), nama')
                     ->unionAll(
                         DB::table('imb_pecahan')
                             ->selectRaw('
                     YEAR(tgl_imb_pecahan) AS tahun,
                     nama,
                     0 AS imb_induk_perumahan,
                     COUNT(*) AS imb_pecahan,
                     0 AS imb_perluasan,
                     0 AS imb_non_perusahaan,
                     0 AS imb_perorangan,
                     0 AS imb_sosbud,
                     0 AS imb_pemutihan,
                     0 AS imb_bersyarat,
                     0 AS imb_lainnya
                 ')
                             ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                 $query->where('nama', '=', $nama_pengembang);
                             })
                             ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                 $query->where('lokasi_perumahan', '=', $nama_perumahan);
                             })
                             ->groupByRaw('YEAR(tgl_imb_pecahan), nama')
                     )
                     ->unionAll(
                         DB::table('imb_perluasan')
                             ->selectRaw('
                     YEAR(tgl_imb_perluasan) AS tahun,
                     nama,
                     0 AS imb_induk_perumahan,
                     0 AS imb_pecahan,
                     COUNT(*) AS imb_perluasan,
                     0 AS imb_non_perusahaan,
                     0 AS imb_perorangan,
                     0 AS imb_sosbud,
                     0 AS imb_pemutihan,
                     0 AS imb_bersyarat,
                     0 AS imb_lainnya
                 ')
                             ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                 $query->where('nama', '=', $nama_pengembang);
                             })
                             ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                 $query->where('lokasi_perumahan', '=', $nama_perumahan);
                             })
                             ->groupByRaw('YEAR(tgl_imb_perluasan), nama')
                     )
                     ->unionAll(
                         DB::table('imb_induk_non_perum')
                             ->selectRaw('
                     YEAR(tgl_imb_induk) AS tahun,
                     nama,
                     0 AS imb_induk_perumahan,
                     0 AS imb_pecahan,
                     0 AS imb_perluasan,
                     COUNT(CASE WHEN jenis = 1 THEN 1 END) AS imb_non_perusahaan,
                     COUNT(CASE WHEN jenis = 2 THEN 1 END) AS imb_perorangan,
                     COUNT(CASE WHEN jenis = 3 THEN 1 END) AS imb_sosbud,
                     COUNT(CASE WHEN jenis = 4 THEN 1 END) AS imb_pemutihan,
                     COUNT(CASE WHEN jenis = 5 THEN 1 END) AS imb_bersyarat,
                     COUNT(CASE WHEN jenis NOT IN (1, 2, 3, 4, 5) THEN 1 END) AS imb_lainnya
                 ')
                             ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                 $query->where('nama', '=', $nama_pengembang);
                             })
                             ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                 $query->where('lokasi_perumahan', '=', $nama_perumahan);
                             })
                             ->groupByRaw('YEAR(tgl_imb_induk), nama')
                     );
             })
                 ->selectRaw('tahun, nama')
                 ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
                 ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
                 ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
                 ->selectRaw('SUM(imb_non_perusahaan) AS imb_non_perusahaan')
                 ->selectRaw('SUM(imb_perorangan) AS imb_perorangan')
                 ->selectRaw('SUM(imb_sosbud) AS imb_sosbud')
                 ->selectRaw('SUM(imb_pemutihan) AS imb_pemutihan')
                 ->selectRaw('SUM(imb_bersyarat) AS imb_bersyarat')
                 ->selectRaw('SUM(imb_lainnya) AS imb_lainnya')
                 ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_non_perusahaan + imb_perorangan + imb_sosbud + imb_pemutihan + imb_bersyarat + imb_lainnya) AS jumlah_imb')
                 ->groupBy('tahun', 'nama')
                 ->orderBy('tahun', 'DESC')
                 ->get();

             return Datatables::of($data)

                 ->addColumn('NAMA_PENGEMBANG', function ($row) {
                     return
                         '<a href="' . route('rekap.DetailIMBIndukListNamaPemohon', $row->nama) . '">' . $row->nama . '</a>';
                 })
                 ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                 ->addIndexColumn()
                 ->make(true);
         }

         $submitType = $request->input('submit_type');
         $filters = $request->only([
             'nama_pengembang',
             'nama_perumahan',
             'tahun',
         ]);

         return view('rekap.detail-imb-induk-list');
     } */


    // new function
    public function DetailIMBIndukList(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {
            $nama_pengembang = $request->input('nama_pengembang');
            $nama_perumahan = $request->input('nama_perumahan');
            $startYear = $request->input('startYear');
            $endYear = $request->input('endYear');

            $data = DB::table(function ($query) use ($nama_pengembang, $nama_perumahan, $startYear, $endYear) {
                $baseQuery = $query->selectRaw('
                    YEAR(tgl_imb_induk) AS tahun,
                    nama,
                    COUNT(*) AS imb_induk_perumahan,
                    0 AS imb_pecahan,
                    0 AS imb_perluasan,
                    0 AS imb_non_perusahaan,
                    0 AS imb_perorangan,
                    0 AS imb_sosbud,
                    0 AS imb_pemutihan,
                    0 AS imb_bersyarat,
                    0 AS imb_lainnya
                ')
                    ->from('imb_induk_perum');

                if ($nama_pengembang) {
                    $baseQuery->where('nama', '=', $nama_pengembang);
                }
                if ($nama_perumahan) {
                    $baseQuery->where('lokasi_perumahan', '=', $nama_perumahan);
                }
                if ($startYear && $endYear) {
                    $baseQuery->whereBetween(DB::raw('YEAR(tgl_imb_induk)'), [$startYear, $endYear]);
                }

                $baseQuery->groupByRaw('YEAR(tgl_imb_induk), nama')
                    ->unionAll(
                        DB::table('imb_pecahan')
                            ->selectRaw('
                                YEAR(tgl_imb_pecahan) AS tahun,
                                nama,
                                0 AS imb_induk_perumahan,
                                COUNT(*) AS imb_pecahan,
                                0 AS imb_perluasan,
                                0 AS imb_non_perusahaan,
                                0 AS imb_perorangan,
                                0 AS imb_sosbud,
                                0 AS imb_pemutihan,
                                0 AS imb_bersyarat,
                                0 AS imb_lainnya
                            ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })
                            ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                $query->where('lokasi_perumahan', '=', $nama_perumahan);
                            })
                            ->when($startYear && $endYear, function ($query) use ($startYear, $endYear) {
                                $query->whereBetween(DB::raw('YEAR(tgl_imb_pecahan)'), [$startYear, $endYear]);
                            })
                            ->groupByRaw('YEAR(tgl_imb_pecahan), nama')
                    )
                    ->unionAll(
                        DB::table('imb_perluasan')
                            ->selectRaw('
                                YEAR(tgl_imb_perluasan) AS tahun,
                                nama,
                                0 AS imb_induk_perumahan,
                                0 AS imb_pecahan,
                                COUNT(*) AS imb_perluasan,
                                0 AS imb_non_perusahaan,
                                0 AS imb_perorangan,
                                0 AS imb_sosbud,
                                0 AS imb_pemutihan,
                                0 AS imb_bersyarat,
                                0 AS imb_lainnya
                            ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })
                            ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                $query->where('lokasi_perumahan', '=', $nama_perumahan);
                            })
                            ->when($startYear && $endYear, function ($query) use ($startYear, $endYear) {
                                $query->whereBetween(DB::raw('YEAR(tgl_imb_perluasan)'), [$startYear, $endYear]);
                            })
                            ->groupByRaw('YEAR(tgl_imb_perluasan), nama')
                    )
                    ->unionAll(
                        DB::table('imb_induk_non_perum')
                            ->selectRaw('
                                YEAR(tgl_imb_induk) AS tahun,
                                nama,
                                0 AS imb_induk_perumahan,
                                0 AS imb_pecahan,
                                0 AS imb_perluasan,
                                COUNT(CASE WHEN jenis = 1 THEN 1 END) AS imb_non_perusahaan,
                                COUNT(CASE WHEN jenis = 2 THEN 1 END) AS imb_perorangan,
                                COUNT(CASE WHEN jenis = 3 THEN 1 END) AS imb_sosbud,
                                COUNT(CASE WHEN jenis = 4 THEN 1 END) AS imb_pemutihan,
                                COUNT(CASE WHEN jenis = 5 THEN 1 END) AS imb_bersyarat,
                                COUNT(CASE WHEN jenis NOT IN (1, 2, 3, 4, 5) THEN 1 END) AS imb_lainnya
                            ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })
                            ->when($nama_perumahan, function ($query) use ($nama_perumahan) {
                                $query->where('lokasi_perumahan', '=', $nama_perumahan);
                            })
                            ->when($startYear && $endYear, function ($query) use ($startYear, $endYear) {
                                $query->whereBetween(DB::raw('YEAR(tgl_imb_induk)'), [$startYear, $endYear]);
                            })
                            ->groupByRaw('YEAR(tgl_imb_induk), nama')
                    );
            })
                ->selectRaw('tahun, nama')
                ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
                ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
                ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
                ->selectRaw('SUM(imb_non_perusahaan) AS imb_non_perusahaan')
                ->selectRaw('SUM(imb_perorangan) AS imb_perorangan')
                ->selectRaw('SUM(imb_sosbud) AS imb_sosbud')
                ->selectRaw('SUM(imb_pemutihan) AS imb_pemutihan')
                ->selectRaw('SUM(imb_bersyarat) AS imb_bersyarat')
                ->selectRaw('SUM(imb_lainnya) AS imb_lainnya')
                ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_non_perusahaan + imb_perorangan + imb_sosbud + imb_pemutihan + imb_bersyarat + imb_lainnya) AS jumlah_imb')
                ->groupBy('tahun', 'nama')
                ->orderBy('tahun', 'DESC')
                ->get();

            return Datatables::of($data)
                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return '<a href="' . route('rekap.DetailIMBIndukListNamaPemohon', $row->nama) . '">' . $row->nama . '</a>';
                })
                ->rawColumns(['NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        $submitType = $request->input('submit_type');
        $filters = $request->only([
            'nama_pengembang',
            'nama_perumahan',
            'tahun',
        ]);

        return view('rekap.detail-imb-induk-list');
    }



    public function DetailIMBIndukListNamaPemohon(Request $request, $nama_pemohon)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {

            $nama_pengembang = $nama_pemohon;
            $data = DB::table(function ($query) use ($nama_pengembang) {
                $baseQuery = $query->selectRaw('
            YEAR(tgl_imb_induk) AS tahun,
            nama,
            COUNT(*) AS imb_induk_perumahan,
            0 AS imb_pecahan,
            0 AS imb_perluasan,
            0 AS imb_non_perusahaan,
            0 AS imb_perorangan,
            0 AS imb_sosbud,
            0 AS imb_pemutihan,
            0 AS imb_bersyarat,
            0 AS imb_lainnya
        ')
                    ->from('imb_induk_perum');

                // Tambahkan filter where jika parameter ada
                if ($nama_pengembang) {
                    $baseQuery->where('nama', '=', $nama_pengembang);
                }

                $baseQuery->groupByRaw('YEAR(tgl_imb_induk), nama')
                    ->unionAll(
                        DB::table('imb_pecahan')
                            ->selectRaw('
                    YEAR(tgl_imb_pecahan) AS tahun,
                    nama,
                    0 AS imb_induk_perumahan,
                    COUNT(*) AS imb_pecahan,
                    0 AS imb_perluasan,
                    0 AS imb_non_perusahaan,
                    0 AS imb_perorangan,
                    0 AS imb_sosbud,
                    0 AS imb_pemutihan,
                    0 AS imb_bersyarat,
                    0 AS imb_lainnya
                ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })

                            ->groupByRaw('YEAR(tgl_imb_pecahan), nama')
                    )
                    ->unionAll(
                        DB::table('imb_perluasan')
                            ->selectRaw('
                    YEAR(tgl_imb_perluasan) AS tahun,
                    nama,
                    0 AS imb_induk_perumahan,
                    0 AS imb_pecahan,
                    COUNT(*) AS imb_perluasan,
                    0 AS imb_non_perusahaan,
                    0 AS imb_perorangan,
                    0 AS imb_sosbud,
                    0 AS imb_pemutihan,
                    0 AS imb_bersyarat,
                    0 AS imb_lainnya
                ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })

                            ->groupByRaw('YEAR(tgl_imb_perluasan), nama')
                    )
                    ->unionAll(
                        DB::table('imb_induk_non_perum')
                            ->selectRaw('
                    YEAR(tgl_imb_induk) AS tahun,
                    nama,
                    0 AS imb_induk_perumahan,
                    0 AS imb_pecahan,
                    0 AS imb_perluasan,
                    COUNT(CASE WHEN jenis = 1 THEN 1 END) AS imb_non_perusahaan,
                    COUNT(CASE WHEN jenis = 2 THEN 1 END) AS imb_perorangan,
                    COUNT(CASE WHEN jenis = 3 THEN 1 END) AS imb_sosbud,
                    COUNT(CASE WHEN jenis = 4 THEN 1 END) AS imb_pemutihan,
                    COUNT(CASE WHEN jenis = 5 THEN 1 END) AS imb_bersyarat,
                    COUNT(CASE WHEN jenis NOT IN (1, 2, 3, 4, 5) THEN 1 END) AS imb_lainnya
                ')
                            ->when($nama_pengembang, function ($query) use ($nama_pengembang) {
                                $query->where('nama', '=', $nama_pengembang);
                            })

                            ->groupByRaw('YEAR(tgl_imb_induk), nama')
                    );
            })
                ->selectRaw('tahun, nama')
                ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
                ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
                ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
                ->selectRaw('SUM(imb_non_perusahaan) AS imb_non_perusahaan')
                ->selectRaw('SUM(imb_perorangan) AS imb_perorangan')
                ->selectRaw('SUM(imb_sosbud) AS imb_sosbud')
                ->selectRaw('SUM(imb_pemutihan) AS imb_pemutihan')
                ->selectRaw('SUM(imb_bersyarat) AS imb_bersyarat')
                ->selectRaw('SUM(imb_lainnya) AS imb_lainnya')
                ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_non_perusahaan + imb_perorangan + imb_sosbud + imb_pemutihan + imb_bersyarat + imb_lainnya) AS jumlah_imb')
                ->groupBy('tahun', 'nama')
                ->orderBy('tahun', 'DESC')
                ->get();

            return Datatables::of($data)

                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return '';
                    // '<a href="' . route('rekap.RegisterIMBPerTahunNamaPemohon', $row->nama_pengembang) . '">' . $row->nama_pengembang . '</a>';
                })
                ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('rekap.detail-imb-induk-list-nama-pemohon', ['nama_pemohon' => $nama_pemohon]);

    }

    public function DetailIMBIndukNamaPemohon(Request $request, $nama_pemohon)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {
            $imbIndukQuery = DB::table('imb_induk_perum')
                ->selectRaw("
                ROW_NUMBER() OVER (ORDER BY imb_induk_perum.tgl_imb_induk) AS NO,
                'INDUK PERUMAHAN' AS JENIS_IMB,
                imb_induk_perum.nama AS NAMA,
                imb_induk_perum.imb_induk AS NO_IMB,
                imb_induk_perum.tgl_imb_induk AS TGL_IMB,
                imb_induk_perum.lokasi_perumahan AS LOKASI,
                kecamatan_induk.name AS KECAMATAN,
                kelurahan_induk.name AS DESA_KEL,
                jenis_kegiatan_induk.name_jeniskeg AS JENIS_KEGIATAN,
                COALESCE(
                    GROUP_CONCAT(DISTINCT app_md_fungsibang.name_fungsibang ORDER BY app_md_fungsibang.name_fungsibang SEPARATOR ', '),
                    'HUNIAN'
                ) AS FUNGSI_BANGUNAN,
                COALESCE(SUM(item_imb_induk_perum.jumlah_unit), 0) AS JUMLAH_UNIT
            ")
                ->leftJoin('master_district AS kecamatan_induk', 'kecamatan_induk.code', '=', 'imb_induk_perum.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_induk', 'kelurahan_induk.code', '=', 'imb_induk_perum.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_induk', 'jenis_kegiatan_induk.id_jeniskeg', '=', 'imb_induk_perum.jenis_kegiatan')
                ->leftJoin('item_imb_induk_perum', 'item_imb_induk_perum.induk_perum_id', '=', 'imb_induk_perum.id')
                ->leftJoin('app_md_fungsibang', 'app_md_fungsibang.id_fungsibang', '=', 'item_imb_induk_perum.fungsi_bangunan')
                ->where('imb_induk_perum.nama', $nama_pemohon)
                ->groupByRaw("
                imb_induk_perum.nama,
                imb_induk_perum.imb_induk,
                imb_induk_perum.tgl_imb_induk,
                imb_induk_perum.lokasi_perumahan,
                kecamatan_induk.name,
                kelurahan_induk.name,
                jenis_kegiatan_induk.name_jeniskeg
            ");
            $imbPecahanQuery = DB::table('imb_pecahan')
                ->selectRaw("
                   ROW_NUMBER() OVER (ORDER BY imb_pecahan.tgl_imb_pecahan) AS NO,
                   'PECAHAN' AS JENIS_IMB,
                   imb_pecahan.nama AS NAMA,
                   imb_pecahan.imb_pecahan AS NO_IMB,
                   imb_pecahan.tgl_imb_pecahan AS TGL_IMB,
                   imb_pecahan.lokasi_perumahan AS LOKASI,
                   kecamatan_pecahan.name AS KECAMATAN,
                   kelurahan_pecahan.name AS DESA_KEL,
                   jenis_kegiatan_pecahan.name_jeniskeg AS JENIS_KEGIATAN,
                   fungsi_bangunan_pecahan.name_fungsibang AS FUNGSI_BANGUNAN,
                   1
               ")
                ->leftJoin('master_district AS kecamatan_pecahan', 'kecamatan_pecahan.code', '=', 'imb_pecahan.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_pecahan', 'kelurahan_pecahan.code', '=', 'imb_pecahan.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_pecahan', 'jenis_kegiatan_pecahan.id_jeniskeg', '=', 'imb_pecahan.jenis_kegiatan')
                ->leftJoin('app_md_fungsibang AS fungsi_bangunan_pecahan', 'fungsi_bangunan_pecahan.id_fungsibang', '=', 'imb_pecahan.fungsi_bangunan')
                ->where('imb_pecahan.nama', $nama_pemohon);
            ;
            $imbPerluasanQuery = DB::table('imb_perluasan')
                ->selectRaw("
                   ROW_NUMBER() OVER (ORDER BY imb_perluasan.tgl_imb_perluasan) AS NO,
                   'PERLUASAN' AS JENIS_IMB,
                   imb_perluasan.nama AS NAMA,
                   imb_perluasan.imb_perluasan AS NO_IMB,
                   imb_perluasan.tgl_imb_perluasan AS TGL_IMB,
                   imb_perluasan.lokasi_perumahan AS LOKASI,
                   kecamatan_perluasan.name AS KECAMATAN,
                   kelurahan_perluasan.name AS DESA_KEL,
                   jenis_kegiatan_perluasan.name_jeniskeg AS JENIS_KEGIATAN,
                   fungsi_bangunan_perluasan.name_fungsibang AS FUNGSI_BANGUNAN,
                   1
               ")
                ->leftJoin('master_district AS kecamatan_perluasan', 'kecamatan_perluasan.code', '=', 'imb_perluasan.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_perluasan', 'kelurahan_perluasan.code', '=', 'imb_perluasan.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_perluasan', 'jenis_kegiatan_perluasan.id_jeniskeg', '=', 'imb_perluasan.jenis_kegiatan')
                ->leftJoin('app_md_fungsibang AS fungsi_bangunan_perluasan', 'fungsi_bangunan_perluasan.id_fungsibang', '=', 'imb_perluasan.fungsi_bangunan')
                ->where('imb_perluasan.nama', $nama_pemohon);
            ;
            $imbIndukNonPerumQuery = DB::table('imb_induk_non_perum')
                ->selectRaw("
                   ROW_NUMBER() OVER (ORDER BY imb_induk_non_perum.tgl_imb_induk) AS NO,
                   'INDUK NON PERUMAHAN' AS JENIS_IMB,
                   imb_induk_non_perum.nama AS NAMA,
                   imb_induk_non_perum.imb_induk AS NO_IMB,
                   imb_induk_non_perum.tgl_imb_induk AS TGL_IMB,
                   imb_induk_non_perum.lokasi_perumahan AS LOKASI,
                   kecamatan_non_perum.name AS KECAMATAN,
                   kelurahan_non_perum.name AS DESA_KEL,
                   jenis_kegiatan_non_perum.name_jeniskeg AS JENIS_KEGIATAN,
                    COALESCE(
                        GROUP_CONCAT(DISTINCT app_md_fungsibang.name_fungsibang ORDER BY app_md_fungsibang.name_fungsibang SEPARATOR ', '),
                        'HUNIAN'
                    ) AS FUNGSI_BANGUNAN,
                     COALESCE(SUM(item_imb_induk_non_perum.jumlah_unit), 0) AS JUMLAH_UNIT
               ")
                ->leftJoin('master_district AS kecamatan_non_perum', 'kecamatan_non_perum.code', '=', 'imb_induk_non_perum.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_non_perum', 'kelurahan_non_perum.code', '=', 'imb_induk_non_perum.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_non_perum', 'jenis_kegiatan_non_perum.id_jeniskeg', '=', 'imb_induk_non_perum.jenis_kegiatan')
                ->leftJoin('item_imb_induk_non_perum', 'item_imb_induk_non_perum.induk_perum_id', '=', 'imb_induk_non_perum.id')
                ->leftJoin('app_md_fungsibang', 'app_md_fungsibang.id_fungsibang', '=', 'item_imb_induk_non_perum.fungsi_bangunan')
                ->where('imb_induk_non_perum.nama', $nama_pemohon)
                ->groupByRaw("
                imb_induk_non_perum.nama,
                imb_induk_non_perum.imb_induk,
                imb_induk_non_perum.tgl_imb_induk,
                imb_induk_non_perum.lokasi_perumahan,
                kecamatan_non_perum.name,
                kelurahan_non_perum.name,
                jenis_kegiatan_non_perum.name_jeniskeg
            ");

            // Gabungkan semua query menggunakan UNION ALL
            $combinedSql = "
            ({$imbIndukQuery->toSql()})
            UNION ALL
            ({$imbPecahanQuery->toSql()})
            UNION ALL
            ({$imbPerluasanQuery->toSql()})
            UNION ALL
            ({$imbIndukNonPerumQuery->toSql()})
            ";

            // Gabungkan semua parameter binding
            $combinedBindings = array_merge(
                $imbIndukQuery->getBindings(),
                $imbPecahanQuery->getBindings(),
                $imbPerluasanQuery->getBindings(),
                $imbIndukNonPerumQuery->getBindings()
            );

            // Eksekusi query gabungan
            $results = DB::select($combinedSql, $combinedBindings);

            return Datatables::of($results)

                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return
                        '';
                })
                ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('rekap.5-2');

    }

    // REKAP 6
    public function DetailIMBPecahan(Request $request)
    {
        return view('rekap.detail-imb-pecahan');
    }

    public function DetailIMBPecahanList(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {
            $imbInduk = $_GET['imbInduk'];

            $data = DB::table('imb_induk_perum as induk')
                ->join('item_imb_induk_perum as item', 'induk.id', '=', 'item.induk_perum_id')
                ->leftJoin('imb_pecahan as pecahan', function ($join) {
                    $join->on('pecahan.imb_induk_id', '=', 'induk.imb_induk')
                        ->on('pecahan.type', '=', 'item.type');
                })
                ->selectRaw('
                    ROW_NUMBER() OVER (ORDER BY item.type) AS NO,
                    item.type AS TIPE,
                    SUM(item.jumlah_unit) AS UNIT,
                    COUNT(DISTINCT pecahan.id) AS SUDAH_PECAH
                ')
                ->where('induk.imb_induk', $imbInduk)
                ->groupBy('item.type')
                ->orderBy('item.type')
                ->get();

            return Datatables::of($data)
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }


        $submitType = $request->input('submit_type');
        $filters = $request->only([
            'nama_pengembang',
            'nama_perumahan',
            'tahun',
        ]);

        return view('rekap.detail-imb-pecahan-list');
    }
    public function DetailIMBPecahanNamaIMB(Request $request, $imb_induk)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {

            $data = IMBPecahan::
                where('imb_pecahan.imb_induk_id', $imb_induk)
                ->leftJoin('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->leftJoin('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
                ->leftJoin('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_pecahan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
                ->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                <div class="d-flex" style="gap:10px;display:flex;">
                    <a href="' . route('IMBPecahan.edit', $row->id) . '" class="edit btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                    <form action="' . route('IMBPecahan.destroy', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
                    </form>
                </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('rekap.detail-imb-pecahan-list-nama-imb', ['imb_induk' => $imb_induk]);
    }

    // REKAP 7
    public function DetailIMBPerluasan(Request $request)
    {
        return view('rekap.detail-imb-perluasan');
    }

    public function DetailIMBPerluasanList(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {

            $imbPecahan = $_GET['imbPecahan'];
            // $nama_perumahan = $_GET['nama_perumahan'];
            $data = IMBPerluasan::join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
                ->where('imb_perluasan.imb_pecahan', $imbPecahan) // Filter untuk imb_pecahan tertentu
                ->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                <div class="d-flex" style="gap:10px;display:flex">
                    <a href="' . route('IMBPerluasan.edit', $row->id) . '" class="edit btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                    <form action="' . route('IMBPerluasan.destroy', $row->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
                    </form>
                </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }


        return view('rekap.detail-imb-perluasan-list');
    }

    // REKAP 2.2
    public function RekapUnitDanFungsiPertahun(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $year = $request->input('year');

        $data = [];

        if ($year) {

            $startDate = "{$year}-01-01";
            $endDate = "{$year}-12-31";

            $data = DB::table(DB::raw("
        (
            SELECT
                'IMB INDUK PERUMAHAN' AS jenis_imb,
                COUNT(DISTINCT imb.id) AS jumlah_imb,
                SUM(item.jumlah_unit) AS jumlah_unit,
                SUM(CASE WHEN item.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_imb,
                SUM(CASE WHEN item.fungsi_bangunan IN (1, 6) THEN item.jumlah_unit ELSE 0 END) AS hunian_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 2 THEN item.jumlah_unit ELSE 0 END) AS usaha_unit,
                SUM(CASE WHEN item.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_imb,
                SUM(CASE WHEN item.fungsi_bangunan IN (4, 5, 7, 8) THEN item.jumlah_unit ELSE 0 END) AS sosial_budaya_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 9 THEN item.jumlah_unit ELSE 0 END) AS khusus_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 3 THEN item.jumlah_unit ELSE 0 END) AS campuran_unit
            FROM imb_induk_perum imb
            LEFT JOIN item_imb_induk_perum item ON item.induk_perum_id = imb.id
            WHERE imb.tgl_imb_induk BETWEEN '$startDate' AND '$endDate'
            GROUP BY jenis_imb
            UNION ALL
            SELECT
                'IMB PECAHAN' AS jenis_imb,
                COUNT(DISTINCT pecahan.id) AS jumlah_imb,
                COUNT(pecahan.id) AS jumlah_unit,
                SUM(CASE WHEN pecahan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_imb,
                SUM(CASE WHEN pecahan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_unit,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_imb,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_unit,
                SUM(CASE WHEN pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_imb,
                SUM(CASE WHEN pecahan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_unit,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_imb,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_unit,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_imb,
                SUM(CASE WHEN pecahan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_unit
            FROM imb_pecahan pecahan
            WHERE pecahan.tgl_imb_induk BETWEEN '$startDate' AND '$endDate'
            GROUP BY jenis_imb
            UNION ALL
           SELECT
            'IMB PERLUASAN' AS jenis_imb,
                COUNT(DISTINCT perluasan.id) AS jumlah_imb,
                COUNT(perluasan.id) AS jumlah_unit, -- Jumlah record = Jumlah unit
                SUM(CASE WHEN perluasan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_imb,
                SUM(CASE WHEN perluasan.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_unit, -- Sama dengan jumlah hunian IMB
                SUM(CASE WHEN perluasan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_imb,
                SUM(CASE WHEN perluasan.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_unit,
                SUM(CASE WHEN perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_imb,
                SUM(CASE WHEN perluasan.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_unit,
                SUM(CASE WHEN perluasan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_imb,
                SUM(CASE WHEN perluasan.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_unit,
                SUM(CASE WHEN perluasan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_imb,
                SUM(CASE WHEN perluasan.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_unit
            FROM imb_perluasan perluasan
            WHERE perluasan.tgl_imb_pecahan BETWEEN '$startDate' AND '$endDate'
            GROUP BY jenis_imb
            UNION ALL
            SELECT
                CASE
                    WHEN non_perum.jenis = '1' THEN 'IMB INDUK NON PERUMAHAN (PERUSAHAAN)'
                    WHEN non_perum.jenis = '2' THEN 'IMB INDUK NON PERUMAHAN (PERORANGAN)'
                    WHEN non_perum.jenis = '3' THEN 'IMB INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)'
                    WHEN non_perum.jenis = '4' THEN 'IMB PEMUTIHAN'
                    WHEN non_perum.jenis = '5' THEN 'IMB BERSYARAT'
                    ELSE 'IMB LAINNYA'
                END AS jenis_imb,
                COUNT(DISTINCT non_perum.id) AS jumlah_imb,
                 SUM(item.jumlah_unit) AS jumlah_unit,
                SUM(CASE WHEN item.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) AS hunian_imb,
                SUM(CASE WHEN item.fungsi_bangunan IN (1, 6) THEN item.jumlah_unit ELSE 0 END) AS hunian_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 2 THEN 1 ELSE 0 END) AS usaha_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 2 THEN item.jumlah_unit ELSE 0 END) AS usaha_unit,
                SUM(CASE WHEN item.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) AS sosial_budaya_imb,
                SUM(CASE WHEN item.fungsi_bangunan IN (4, 5, 7, 8) THEN item.jumlah_unit ELSE 0 END) AS sosial_budaya_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 9 THEN 1 ELSE 0 END) AS khusus_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 9 THEN item.jumlah_unit ELSE 0 END) AS khusus_unit,
                SUM(CASE WHEN item.fungsi_bangunan = 3 THEN 1 ELSE 0 END) AS campuran_imb,
                SUM(CASE WHEN item.fungsi_bangunan = 3 THEN item.jumlah_unit ELSE 0 END) AS campuran_unit
            FROM imb_induk_non_perum non_perum
            LEFT JOIN item_imb_induk_non_perum item ON item.induk_perum_id = non_perum.id
            WHERE non_perum.tgl_imb_induk BETWEEN '$startDate' AND '$endDate'
            GROUP BY jenis_imb
        ) AS data
    "))
                ->get();
        }

        return view('rekap.rekap-imb-pertahun.rekap-unit-dan-fungsi', compact('data'));

    }

    // Rekap 3.2
    // public function RekapLokasiPertahun(Request $request)
    // {
    //     $year = $request->input('year');
    //     $kabupaten = $request->input('kabupaten');
    //     $kecamatan = $request->input('kecamatan');
    //     $kelurahan = $request->input('kelurahan');
    //     $data = [];

    //     if ($year) {
    //         $startDate = "{$year}-01-01";
    //         $endDate = "{$year}-12-31";

    //         $data = DB::select("
    //             SELECT
    //                 base.kecamatan,
    //                 base.desa_kelurahan,
    //                 YEAR(base.tgl_register) AS tahun,
    //                 COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
    //                 COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
    //                 COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
    //                 -- IMB Non Perumahan dengan klasifikasi berdasarkan jenis
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 1 THEN imb_induk_non_perum.id END) AS imb_non_perusahaan,
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 2 THEN imb_induk_non_perum.id END) AS imb_non_perorangan,
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 3 THEN imb_induk_non_perum.id END) AS imb_non_sosial_budaya,
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 4 THEN imb_induk_non_perum.id END) AS imb_pemutihan,
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 5 THEN imb_induk_non_perum.id END) AS imb_bersyarat,
    //                 COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 6 THEN imb_induk_non_perum.id END) AS imb_lainnya,
    //                 (
    //                     COUNT(DISTINCT imb_induk_perum.id) +
    //                     COUNT(DISTINCT imb_pecahan.id) +
    //                     COUNT(DISTINCT imb_perluasan.id) +
    //                     COUNT(DISTINCT imb_induk_non_perum.id)
    //                 ) AS jumlah_imb
    //             FROM
    //                 (
    //                     SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_perum WHERE tgl_register BETWEEN ? AND ?
    //                     UNION ALL
    //                     SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_pecahan WHERE tgl_register BETWEEN ? AND ?
    //                     UNION ALL
    //                     SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_perluasan WHERE tgl_register BETWEEN ? AND ?
    //                     UNION ALL
    //                     SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_non_perum WHERE tgl_register BETWEEN ? AND ?
    //                 ) AS base
    //             LEFT JOIN imb_induk_perum ON base.kecamatan = imb_induk_perum.kecamatan AND base.desa_kelurahan = imb_induk_perum.desa_kelurahan
    //             LEFT JOIN imb_pecahan ON base.kecamatan = imb_pecahan.kecamatan AND base.desa_kelurahan = imb_pecahan.desa_kelurahan
    //             LEFT JOIN imb_perluasan ON base.kecamatan = imb_perluasan.kecamatan AND base.desa_kelurahan = imb_perluasan.desa_kelurahan
    //             LEFT JOIN imb_induk_non_perum ON base.kecamatan = imb_induk_non_perum.kecamatan AND base.desa_kelurahan = imb_induk_non_perum.desa_kelurahan
    //             GROUP BY base.kecamatan, base.desa_kelurahan, YEAR(base.tgl_register)
    //             ORDER BY base.kecamatan, base.desa_kelurahan, tahun;
    //         ", [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);
    //     }

    //     return view('rekap.rekap-imb-pertahun.rekap-lokasi', compact('data', 'year'));
    // }
    public function RekapLokasiPertahun(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $year = $request->input('year');
        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $data = [];
        $bindings = [];

        if ($request && ($year || $kabupaten || $kecamatan || $kelurahan)) {
            $sql = "
                SELECT
                    base.kabupaten,
                    base.kecamatan,
                    base.desa_kelurahan,
                    YEAR(base.tgl_register) AS tahun,
                    COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
                    COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
                    COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 1 THEN imb_induk_non_perum.id END) AS imb_non_perusahaan,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 2 THEN imb_induk_non_perum.id END) AS imb_non_perorangan,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 3 THEN imb_induk_non_perum.id END) AS imb_non_sosial_budaya,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 4 THEN imb_induk_non_perum.id END) AS imb_pemutihan,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 5 THEN imb_induk_non_perum.id END) AS imb_bersyarat,
                    COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 6 THEN imb_induk_non_perum.id END) AS imb_lainnya,
                    (
                        COUNT(DISTINCT imb_induk_perum.id) +
                        COUNT(DISTINCT imb_pecahan.id) +
                        COUNT(DISTINCT imb_perluasan.id) +
                        COUNT(DISTINCT imb_induk_non_perum.id)
                    ) AS jumlah_imb
                FROM
                    (
                        SELECT kabupaten, kecamatan, desa_kelurahan, tgl_register FROM imb_induk_perum WHERE tgl_register BETWEEN ? AND ?
                        UNION ALL
                        SELECT kabupaten, kecamatan, desa_kelurahan, tgl_register FROM imb_pecahan WHERE tgl_register BETWEEN ? AND ?
                        UNION ALL
                        SELECT kabupaten, kecamatan, desa_kelurahan, tgl_register FROM imb_perluasan WHERE tgl_register BETWEEN ? AND ?
                        UNION ALL
                        SELECT kabupaten, kecamatan, desa_kelurahan, tgl_register FROM imb_induk_non_perum WHERE tgl_register BETWEEN ? AND ?
                    ) AS base
                LEFT JOIN imb_induk_perum ON base.kabupaten = imb_induk_perum.kabupaten AND base.kecamatan = imb_induk_perum.kecamatan AND base.desa_kelurahan = imb_induk_perum.desa_kelurahan
                LEFT JOIN imb_pecahan ON base.kabupaten = imb_pecahan.kabupaten AND base.kecamatan = imb_pecahan.kecamatan AND base.desa_kelurahan = imb_pecahan.desa_kelurahan
                LEFT JOIN imb_perluasan ON base.kabupaten = imb_perluasan.kabupaten AND base.kecamatan = imb_perluasan.kecamatan AND base.desa_kelurahan = imb_perluasan.desa_kelurahan
                LEFT JOIN imb_induk_non_perum ON base.kabupaten = imb_induk_non_perum.kabupaten AND base.kecamatan = imb_induk_non_perum.kecamatan AND base.desa_kelurahan = imb_induk_non_perum.desa_kelurahan
                WHERE 1 = 1
            ";

            if ($year) {
                $startDate = "{$year}-01-01";
                $endDate = "{$year}-12-31";

                // Array untuk parameter binding
                $bindings = [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate];
            } else {
                $startDate = "1000-01-01";
                $endDate = "3000-01-01";

                $bindings = [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate];
            }
            if ($kabupaten) {
                $sql .= " AND base.kabupaten = ?";
                $bindings[] = DB::table('master_regency')->where('name', $kabupaten)->value('code');
            }
            if ($kecamatan) {
                $sql .= " AND base.kecamatan = ?";
                $bindings[] = DB::table('master_district')->where('name', $kecamatan)->value('code');
            }
            if ($kelurahan) {
                $sql .= " AND base.desa_kelurahan = ?";
                $bindings[] = DB::table('master_subdistrict')->where('code', $kelurahan)->value('code');
            }

            $sql .= "
            GROUP BY base.kabupaten, base.kecamatan, base.desa_kelurahan, YEAR(base.tgl_register)
            ORDER BY base.kabupaten, base.kecamatan, base.desa_kelurahan, tahun;
            ";

            // dd($data);
            // Eksekusi query
            $data = DB::select($sql, $bindings);
        }

        return view('rekap.rekap-imb-pertahun.rekap-lokasi', compact('data'));
    }


    // Rekap 4.2
    public function RekapUnitFungsiDanLokasiPertahun(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $year = $request->input('year');
        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $data = [];
        $bindings = [];

        if ($request && ($year || $kabupaten || $kecamatan || $kelurahan)) {
            if ($year) {
                $startDate = "{$year}-01-01";
                $endDate = "{$year}-12-31";
            } else {
                $startDate = "1000-01-01";
                $endDate = "3000-01-01";
            }

            $data = DB::table(function ($query) use ($startDate, $endDate, $kabupaten, $kecamatan, $kelurahan) {
                $query->selectRaw('
                    YEAR(tgl_imb_induk) as tahun,
                    imb_induk_perum.kabupaten,
                    imb_induk_perum.kecamatan,
                    imb_induk_perum.desa_kelurahan,
                    COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan,
                    SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan,
                    0 as imb_pecahan, 0 as unit_pecahan,
                    0 as imb_perluasan, 0 as unit_perluasan,
                    0 as imb_non_perumahan_perusahaan,
                    0 as unit_non_perumahan_perusahaan,
                    0 as imb_non_perumahan_perorangan,
                    0 as unit_non_perumahan_perorangan,
                    0 as imb_non_perumahan_sosbud,
                    0 as unit_non_perumahan_sosbud,
                    0 as imb_pemutihan,
                    0 as unit_pemutihan,
                    0 as imb_bersyarat,
                    0 as unit_bersyarat,
                    0 as imb_lainnya,
                    0 as unit_lainnya,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 2 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 9 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                    SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 3 THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) as campuran_unit
                ')
                    ->from('imb_induk_perum')
                    ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                    ->whereBetween('tgl_imb_induk', [$startDate, $endDate])
                    ->groupByRaw('YEAR(tgl_imb_induk), imb_induk_perum.kabupaten, imb_induk_perum.kecamatan, imb_induk_perum.desa_kelurahan')
                    ->unionAll(
                        DB::table('imb_pecahan')
                            ->selectRaw('
                            YEAR(tgl_imb_induk) as tahun,
                            imb_pecahan.kabupaten,
                            imb_pecahan.kecamatan,
                            imb_pecahan.desa_kelurahan,
                            0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                            COUNT(*) as imb_pecahan, COUNT(*) as unit_pecahan,
                            0 as imb_perluasan, 0 as unit_perluasan,
                            0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                            0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                            0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                            0 as imb_pemutihan, 0 as unit_pemutihan,
                            0 as imb_bersyarat, 0 as unit_bersyarat,
                            0 as imb_lainnya, 0 as unit_lainnya,
                            SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                        ')
                            ->whereBetween('tgl_imb_pecahan', [$startDate, $endDate])
                            ->groupByRaw('YEAR(tgl_imb_induk), imb_pecahan.kabupaten, imb_pecahan.kecamatan, imb_pecahan.desa_kelurahan')
                    )
                    ->unionAll(
                        DB::table('imb_perluasan')
                            ->selectRaw('
                            YEAR(tgl_imb_perluasan) as tahun,
                            imb_perluasan.kabupaten,
                            imb_perluasan.kecamatan,
                            imb_perluasan.desa_kelurahan,
                            0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                            0 as imb_pecahan, 0 as unit_pecahan,
                            COUNT(*) as imb_perluasan, COUNT(*) as unit_perluasan,
                            0 as imb_non_perumahan_perusahaan, 0 as unit_non_perumahan_perusahaan,
                            0 as imb_non_perumahan_perorangan, 0 as unit_non_perumahan_perorangan,
                            0 as imb_non_perumahan_sosbud, 0 as unit_non_perumahan_sosbud,
                            0 as imb_pemutihan, 0 as unit_pemutihan,
                            0 as imb_bersyarat, 0 as unit_bersyarat,
                            0 as imb_lainnya, 0 as unit_lainnya,
                            SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_unit
                        ')
                            ->whereBetween('tgl_imb_perluasan', [$startDate, $endDate])

                            ->groupByRaw('YEAR(tgl_imb_perluasan), imb_perluasan.kabupaten, imb_perluasan.kecamatan, imb_perluasan.desa_kelurahan')
                    )
                    ->unionAll(
                        DB::table('imb_induk_non_perum')
                            ->selectRaw('
                            YEAR(tgl_imb_induk) as tahun,
                            imb_induk_non_perum.kabupaten,
                            imb_induk_non_perum.kecamatan,
                            imb_induk_non_perum.desa_kelurahan,
                            0 as imb_induk_perumahan, 0 as unit_induk_perumahan,
                            0 as imb_pecahan, 0 as unit_pecahan,
                            0 as imb_perluasan, 0 as unit_perluasan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN 1 ELSE 0 END) as imb_non_perumahan_perusahaan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perusahaan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN 1 ELSE 0 END) as imb_non_perumahan_perorangan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perorangan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN 1 ELSE 0 END) as imb_non_perumahan_sosbud,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_sosbud,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN 1 ELSE 0 END) as imb_pemutihan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_pemutihan,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN 1 ELSE 0 END) as imb_bersyarat,
                            SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_bersyarat,
                            SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN 1 ELSE 0 END) as imb_lainnya,
                            SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_lainnya,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN 1 ELSE 0 END) as hunian_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (1, 6) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN 1 ELSE 0 END) as sosbud_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan IN (4, 5, 7, 8) THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN 1 ELSE 0 END) as khusus_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 9 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as campuran_imb,
                            SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit
                        ')
                            ->leftJoin('item_imb_induk_non_perum', 'imb_induk_non_perum.id', '=', 'item_imb_induk_non_perum.induk_perum_id')
                            ->whereBetween('tgl_imb_induk', [$startDate, $endDate])

                            ->groupByRaw('YEAR(tgl_imb_induk), imb_induk_non_perum.kabupaten, imb_induk_non_perum.kecamatan, imb_induk_non_perum.desa_kelurahan')
                    );
            })
                ->selectRaw('
                tahun,
                kabupaten,
                kecamatan,
                desa_kelurahan,
                SUM(imb_induk_perumahan) as imb_induk_perumahan,
                SUM(unit_induk_perumahan) as unit_induk_perumahan,
                SUM(imb_pecahan) as imb_pecahan,
                SUM(unit_pecahan) as unit_pecahan,
                SUM(imb_perluasan) as imb_perluasan,
                SUM(unit_perluasan) as unit_perluasan,
                SUM(imb_non_perumahan_perusahaan) as imb_non_perumahan_perusahaan,
                SUM(unit_non_perumahan_perusahaan) as unit_non_perumahan_perusahaan,
                SUM(imb_non_perumahan_perorangan) as imb_non_perumahan_perorangan,
                SUM(unit_non_perumahan_perorangan) as unit_non_perumahan_perorangan,
                SUM(imb_non_perumahan_sosbud) as imb_non_perumahan_sosbud,
                SUM(unit_non_perumahan_sosbud) as unit_non_perumahan_sosbud,
                SUM(imb_pemutihan) as imb_pemutihan,
                SUM(unit_pemutihan) as unit_pemutihan,
                SUM(imb_bersyarat) as imb_bersyarat,
                SUM(unit_bersyarat) as unit_bersyarat,
                SUM(imb_lainnya) as imb_lainnya,
                SUM(unit_lainnya) as unit_lainnya,
                SUM(hunian_imb) as hunian_imb,
                SUM(hunian_unit) as hunian_unit,
                SUM(usaha_imb) as usaha_imb,
                SUM(usaha_unit) as usaha_unit,
                SUM(sosbud_imb) as sosbud_imb,
                SUM(sosbud_unit) as sosbud_unit,
                SUM(khusus_imb) as khusus_imb,
                SUM(khusus_unit) as khusus_unit,
                SUM(campuran_imb) as campuran_imb,
                SUM(campuran_unit) as campuran_unit
            ')
                ->when($kabupaten, function ($query, $kabupaten) {
                    return $query->where('kabupaten', DB::table('master_regency')->where('name', $kabupaten)->value('code'));
                })
                ->when($kecamatan, function ($query, $kecamatan) {
                    // dd(DB::table('master_district')->where('name', $kecamatan)->value('code'));
                    return $query->where('kecamatan', DB::table('master_district')->where('name', $kecamatan)->value('code'));
                })
                ->when($kelurahan, function ($query, $kelurahan) {
                    // dd($kelurahan);
                    // dd(DB::table('master_subdistrict')->where('code', $kelurahan)->value('code'));
                    return $query->where('desa_kelurahan', DB::table('master_subdistrict')->where('code', $kelurahan)->value('code'));
                })
                ->groupBy('tahun', 'kabupaten', 'kecamatan', 'desa_kelurahan')
                ->orderBy('tahun', 'DESC')
                ->get();

        }
        return view('rekap.rekap-imb-pertahun.rekap-unit-fungsi-dan-lokasi', compact('data'));
    }

    // Rekap 4.1
    public function RekapSKIMBGPerbulan()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::select("
        SELECT
            base.kecamatan AS kecamatan,
            base.desa_kelurahan AS desa_kelurahan,
            YEAR(base.tgl_register) AS tahun,
            COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
            COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
            COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
            -- IMB Non-Perumahan dengan klasifikasi berdasarkan jenis
            SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN 1 ELSE 0 END) AS imb_non_perusahaan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN 1 ELSE 0 END) AS imb_non_perorangan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN 1 ELSE 0 END) AS imb_non_sosial_budaya,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN 1 ELSE 0 END) AS imb_pemutihan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN 1 ELSE 0 END) AS imb_bersyarat,
            SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN 1 ELSE 0 END) AS imb_lainnya,
            -- Jumlah Unit
            SUM(item_imb_induk_perum.jumlah_unit) AS jumlah_unit,
            -- Fungsi Bangunan
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'HUNIAN' THEN 1 ELSE 0 END) AS hunian_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'HUNIAN' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS hunian_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'USAHA' THEN 1 ELSE 0 END) AS usaha_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'USAHA' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS usaha_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'SOSIAL DAN BUDAYA' THEN 1 ELSE 0 END) AS sosbud_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'SOSIAL DAN BUDAYA' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS sosbud_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'KHUSUS' THEN 1 ELSE 0 END) AS khusus_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'KHUSUS' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS khusus_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'CAMPURAN' THEN 1 ELSE 0 END) AS campuran_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'CAMPURAN' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS campuran_unit
        FROM
            (
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_perum
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_pecahan
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_perluasan
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_non_perum
            ) AS base
        LEFT JOIN imb_induk_perum ON base.kecamatan = imb_induk_perum.kecamatan AND base.desa_kelurahan = imb_induk_perum.desa_kelurahan
        LEFT JOIN item_imb_induk_perum ON imb_induk_perum.id = item_imb_induk_perum.induk_perum_id
        LEFT JOIN imb_pecahan ON base.kecamatan = imb_pecahan.kecamatan AND base.desa_kelurahan = imb_pecahan.desa_kelurahan
        LEFT JOIN imb_perluasan ON base.kecamatan = imb_perluasan.kecamatan AND base.desa_kelurahan = imb_perluasan.desa_kelurahan
        LEFT JOIN imb_induk_non_perum ON base.kecamatan = imb_induk_non_perum.kecamatan AND base.desa_kelurahan = imb_induk_non_perum.desa_kelurahan
        GROUP BY base.kecamatan, base.desa_kelurahan, YEAR(base.tgl_register)
        ORDER BY base.kecamatan, base.desa_kelurahan, tahun;


          ");


        return view('rekap.rekap-sk-imbg.rekap-sk-imbg-perbulan', compact('data'));
    }





    // Rekap 4.1
    public function RekapSKIMBGPertahun()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::select("
        SELECT
            base.kecamatan AS kecamatan,
            base.desa_kelurahan AS desa_kelurahan,
            YEAR(base.tgl_register) AS tahun,
            COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
            COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
            COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
            -- IMB Non-Perumahan dengan klasifikasi berdasarkan jenis
            SUM(CASE WHEN imb_induk_non_perum.jenis = 1 THEN 1 ELSE 0 END) AS imb_non_perusahaan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 2 THEN 1 ELSE 0 END) AS imb_non_perorangan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 3 THEN 1 ELSE 0 END) AS imb_non_sosial_budaya,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 4 THEN 1 ELSE 0 END) AS imb_pemutihan,
            SUM(CASE WHEN imb_induk_non_perum.jenis = 5 THEN 1 ELSE 0 END) AS imb_bersyarat,
            SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN 1 ELSE 0 END) AS imb_lainnya,
            -- Jumlah Unit
            SUM(item_imb_induk_perum.jumlah_unit) AS jumlah_unit,
            -- Fungsi Bangunan
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'HUNIAN' THEN 1 ELSE 0 END) AS hunian_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'HUNIAN' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS hunian_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'USAHA' THEN 1 ELSE 0 END) AS usaha_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'USAHA' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS usaha_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'SOSIAL DAN BUDAYA' THEN 1 ELSE 0 END) AS sosbud_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'SOSIAL DAN BUDAYA' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS sosbud_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'KHUSUS' THEN 1 ELSE 0 END) AS khusus_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'KHUSUS' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS khusus_unit,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'CAMPURAN' THEN 1 ELSE 0 END) AS campuran_imb,
            SUM(CASE WHEN item_imb_induk_perum.fungsi_bangunan = 'CAMPURAN' THEN item_imb_induk_perum.jumlah_unit ELSE 0 END) AS campuran_unit
        FROM
            (
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_perum
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_pecahan
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_perluasan
                UNION ALL
                SELECT kecamatan, desa_kelurahan, tgl_register FROM imb_induk_non_perum
            ) AS base
        LEFT JOIN imb_induk_perum ON base.kecamatan = imb_induk_perum.kecamatan AND base.desa_kelurahan = imb_induk_perum.desa_kelurahan
        LEFT JOIN item_imb_induk_perum ON imb_induk_perum.id = item_imb_induk_perum.induk_perum_id
        LEFT JOIN imb_pecahan ON base.kecamatan = imb_pecahan.kecamatan AND base.desa_kelurahan = imb_pecahan.desa_kelurahan
        LEFT JOIN imb_perluasan ON base.kecamatan = imb_perluasan.kecamatan AND base.desa_kelurahan = imb_perluasan.desa_kelurahan
        LEFT JOIN imb_induk_non_perum ON base.kecamatan = imb_induk_non_perum.kecamatan AND base.desa_kelurahan = imb_induk_non_perum.desa_kelurahan
        GROUP BY base.kecamatan, base.desa_kelurahan, YEAR(base.tgl_register)
        ORDER BY base.kecamatan, base.desa_kelurahan, tahun;


          ");


        return view('rekap.rekap-sk-imbg.rekap-sk-imbg-pertahun', compact('data'));
    }


























    public function RegisterIMBPerTahunStore(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {

            $nama_pengembang = $_GET['nama_pengembang'];
            $nama_perumahan = $_GET['nama_perumahan'];
            $no_imb_induk = $_GET['no_imb_induk'];


            $data = DB::table('imb_induk_perum as imb_induk')
                ->selectRaw('
                ROW_NUMBER() OVER (ORDER BY EXTRACT(YEAR FROM imb_induk.created_at)) AS NO,
                EXTRACT(YEAR FROM imb_induk.created_at) AS tahun,
                imb_induk.nama AS nama_pengembang,
                COUNT(DISTINCT imb_induk.id) AS JUMLAH_IMB,
                COUNT(DISTINCT CASE WHEN imb_induk.imb_induk IS NOT NULL THEN imb_induk.id END) AS IMB_INDUK_PERUMAHAN,
                COUNT(DISTINCT CASE WHEN imb_pecahan.imb_pecahan IS NOT NULL THEN imb_pecahan.id END) AS IMB_PECAHAN,
                COUNT(DISTINCT CASE WHEN imb_perluasan.imb_perluasan IS NOT NULL THEN imb_perluasan.id END) AS IMB_PERLUASAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 1 THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_PERUSAHAAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 2 THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_PERORANGAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis IN (3, 4) THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_SOSIAL_BUDAYA,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 5 THEN imb_induk_non_perum.id END) AS IMB_BERSYARAT,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN imb_induk_non_perum.id END) AS IMB_LAINNYA
            ')
                ->leftJoin('imb_pecahan', 'imb_pecahan.imb_induk_id', '=', 'imb_induk.id')
                ->leftJoin('imb_perluasan', 'imb_perluasan.imb_pecahan', '=', 'imb_pecahan.imb_pecahan')
                ->leftJoin('imb_induk_non_perum', 'imb_induk_non_perum.imb_induk', '=', 'imb_induk.imb_induk')
                ->groupBy('tahun', 'nama_pengembang')
                ->orderBy('tahun');

            if ($nama_pengembang) {
                $data->where('imb_induk.nama', 'like', '%' . $nama_pengembang . '%');
            }

            if ($nama_perumahan) {
                $data->where('imb_induk.nama_perumahan', 'like', '%' . $nama_perumahan . '%');
            }

            if ($no_imb_induk) {
                $data->where('imb_induk.no_imb_induk', 'like', '%' . $no_imb_induk . '%');
            }

            $data = $data->get();

            return Datatables::of($data)

                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return
                        '<a href="' . route('rekap.RegisterIMBPerTahunNamaPemohon', $row->nama_pengembang) . '">' . $row->nama_pengembang . '</a>';
                })
                ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        $submitType = $request->input('submit_type');
        $filters = $request->only([
            'nama_pengembang',
            'nama_perumahan',
            'no_imb_induk',
            'no_imb_pecahan',
            'no_imb_perluasan',
            'tahun',
        ]);

        switch ($submitType) {
            case 'induk':
                return view('rekap.5');
            case 'pecahan':
                return view('rekap.6');
            case 'perluasan':
                return view('rekap.7');
            default:
                return redirect()->back()->with('error', 'Jenis submit tidak valid');
        }
    }

    public function RegisterIMBPerTahunNamaPemohon(Request $request, $nama_pemohon)
    {

        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        if ($request->ajax()) {
            $data = DB::table('imb_induk_perum as imb_induk')
                ->selectRaw('
                ROW_NUMBER() OVER (ORDER BY EXTRACT(YEAR FROM imb_induk.created_at)) AS NO,
                EXTRACT(YEAR FROM imb_induk.created_at) AS tahun,
                imb_induk.nama AS nama_pengembang,
                COUNT(DISTINCT imb_induk.id) AS JUMLAH_IMB,
                COUNT(DISTINCT CASE WHEN imb_induk.imb_induk IS NOT NULL THEN imb_induk.id END) AS IMB_INDUK_PERUMAHAN,
                COUNT(DISTINCT CASE WHEN imb_pecahan.imb_pecahan IS NOT NULL THEN imb_pecahan.id END) AS IMB_PECAHAN,
                COUNT(DISTINCT CASE WHEN imb_perluasan.imb_perluasan IS NOT NULL THEN imb_perluasan.id END) AS IMB_PERLUASAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 1 THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_PERUSAHAAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 2 THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_PERORANGAN,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis IN (3, 4) THEN imb_induk_non_perum.id END) AS IMB_NON_PERUMAHAN_SOSIAL_BUDAYA,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis = 5 THEN imb_induk_non_perum.id END) AS IMB_BERSYARAT,
                COUNT(DISTINCT CASE WHEN imb_induk_non_perum.jenis NOT IN (1, 2, 3, 4, 5) THEN imb_induk_non_perum.id END) AS IMB_LAINNYA
            ')
                ->where('imb_induk.nama', $nama_pemohon)
                ->leftJoin('imb_pecahan', 'imb_pecahan.imb_induk_id', '=', 'imb_induk.id')
                ->leftJoin('imb_perluasan', 'imb_perluasan.imb_pecahan', '=', 'imb_pecahan.imb_pecahan')
                ->leftJoin('imb_induk_non_perum', 'imb_induk_non_perum.imb_induk', '=', 'imb_induk.imb_induk')
                ->groupBy('tahun', 'nama_pengembang')
                ->orderBy('tahun')
                ->get();
            return Datatables::of($data)

                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return
                        '<a href="' . route('rekap.RegisterIMBPerTahunNamaPemohon', $row->nama_pengembang) . '">' . $row->nama_pengembang . '</a>';
                })
                ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('rekap.5-1', ['nama_pemohon' => $nama_pemohon]);

    }









    public function IMBsubmit(Request $request)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $tahun = $request->input('tahun');
        $type = $request->input('type');

        switch ($type) {
            case '1':

                $data = DB::table(function ($query) {
                    $query->selectRaw('YEAR(tgl_imb_induk) AS tahun, COUNT(*) AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                        ->from('imb_induk_perum')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                        ->unionAll(
                            DB::table('imb_pecahan')
                                ->selectRaw('YEAR(tgl_imb_pecahan) AS tahun, 0 AS imb_induk_perumahan, COUNT(*) AS imb_pecahan, 0 AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                                ->groupByRaw('YEAR(tgl_imb_pecahan)')
                        )
                        ->unionAll(
                            DB::table('imb_perluasan')
                                ->selectRaw('YEAR(tgl_imb_perluasan) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, COUNT(*) AS imb_perluasan, 0 AS imb_induk_non_perumahan')
                                ->groupByRaw('YEAR(tgl_imb_perluasan)')
                        )
                        ->unionAll(
                            DB::table('imb_induk_non_perum')
                                ->selectRaw('YEAR(tgl_imb_induk) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, COUNT(*) AS imb_induk_non_perumahan')
                                ->groupByRaw('YEAR(tgl_imb_induk)')
                        );
                })
                    ->selectRaw('tahun')
                    ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
                    ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
                    ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
                    ->selectRaw('SUM(imb_induk_non_perumahan) AS imb_induk_non_perumahan')
                    ->selectRaw('SUM(imb_induk_perumahan + imb_pecahan + imb_perluasan + imb_induk_non_perumahan) AS jumlah_imb')
                    ->groupBy('tahun')
                    ->orderBy('tahun', 'DESC')
                    ->get();


                return view('rekap.1', compact('tahun', 'data'));
            case '1.1':
                $data = DB::table(function ($query) {
                    $query->selectRaw('YEAR(tgl_imb_induk) AS tahun, COUNT(*) AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan, 0 AS perusahaan, 0 AS perorangan, 0 AS sosial_budaya, 0 AS pemutihan, 0 AS bersyarat, 0 AS lainnya')
                        ->from('imb_induk_perum')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                        ->unionAll(
                            DB::table('imb_pecahan')
                                ->selectRaw('YEAR(tgl_imb_pecahan) AS tahun, 0 AS imb_induk_perumahan, COUNT(*) AS imb_pecahan, 0 AS imb_perluasan, 0 AS perusahaan, 0 AS perorangan, 0 AS sosial_budaya, 0 AS pemutihan, 0 AS bersyarat, 0 AS lainnya')
                                ->groupByRaw('YEAR(tgl_imb_pecahan)')
                        )
                        ->unionAll(
                            DB::table('imb_perluasan')
                                ->selectRaw('YEAR(tgl_imb_perluasan) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, COUNT(*) AS imb_perluasan, 0 AS perusahaan, 0 AS perorangan, 0 AS sosial_budaya, 0 AS pemutihan, 0 AS bersyarat, 0 AS lainnya')
                                ->groupByRaw('YEAR(tgl_imb_perluasan)')
                        )
                        ->unionAll(
                            DB::table('imb_induk_non_perum')
                                ->selectRaw('YEAR(tgl_imb_induk) AS tahun, 0 AS imb_induk_perumahan, 0 AS imb_pecahan, 0 AS imb_perluasan,
                                             SUM(CASE WHEN jenis = "PERUSAHAAN" THEN 1 ELSE 0 END) AS perusahaan,
                                             SUM(CASE WHEN jenis = "PERORANGAN" THEN 1 ELSE 0 END) AS perorangan,
                                             SUM(CASE WHEN jenis = "SOSIAL DAN BUDAYA" THEN 1 ELSE 0 END) AS sosial_budaya,
                                             SUM(CASE WHEN jenis = "PEMUTIHAN" THEN 1 ELSE 0 END) AS pemutihan,
                                             SUM(CASE WHEN jenis = "BERSYARAT" THEN 1 ELSE 0 END) AS bersyarat,
                                             SUM(CASE WHEN jenis NOT IN ("PERUSAHAAN", "PERORANGAN", "SOSIAL DAN BUDAYA", "PEMUTIHAN", "BERSYARAT") THEN 1 ELSE 0 END) AS lainnya
                                             ')
                                ->groupByRaw('YEAR(tgl_imb_induk)')
                        );
                })
                    ->selectRaw('tahun')
                    ->selectRaw('SUM(imb_induk_perumahan) AS imb_induk_perumahan')
                    ->selectRaw('SUM(imb_pecahan) AS imb_pecahan')
                    ->selectRaw('SUM(imb_perluasan) AS imb_perluasan')
                    ->selectRaw('SUM(perusahaan) AS perusahaan')
                    ->selectRaw('SUM(perorangan) AS perorangan')
                    ->selectRaw('SUM(sosial_budaya) AS sosial_budaya')
                    ->selectRaw('SUM(pemutihan) AS pemutihan')
                    ->selectRaw('SUM(bersyarat) AS bersyarat')
                    ->selectRaw('SUM(lainnya) AS lainnya')
                    ->groupBy('tahun')
                    ->orderBy('tahun', 'DESC')
                    ->get();

                // dd($data);
                return view('rekap.1-1', compact('tahun'));
            case '2':
                return view('rekap.2', compact('tahun'));
            case '2.1':
                return view('rekap.2-1', compact('tahun'));
            case '2.2':
                return view('rekap.2-2', compact('tahun'));
            case '3':
                return view('rekap.3', compact('tahun'));
            case '3.1':
                return view('rekap.3-1', compact('tahun'));
            case '3.2':
                return view('rekap.3-2', compact('tahun'));
            case '4':
                return view('rekap.4', compact('tahun'));
            case '4.1':
                return view('rekap.4-1', compact('tahun'));
            case '4.2':
                return view('rekap.4-2', compact('tahun'));
            case '5':
                return view('rekap.5', compact('tahun'));
            case '5.1':
                return view('rekap.5-1', compact('tahun'));
            case '6':
                return view('rekap.6', compact('tahun'));
            case '6.1':
                return view('rekap.6-1', compact('tahun'));
            case '7.1':
                return view('rekap.7', compact('tahun'));
            case '8.1':
                return view('rekap.8-1', compact('tahun'));
            case '9.1':
                return view('rekap.9', compact('tahun'));
            case '9.2':
                return view('rekap.9-1', compact('tahun'));
            case '10':
                return view('rekap.10', compact('tahun'));
            case '10.1':
                return view('rekap.10-1', compact('tahun'));
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe tidak dikenali',
                ], 400);
        }
    }

    public function RekapSuratPerbulan($tahun)
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table('dbsimpol.surat')
            ->selectRaw('
                YEAR(tanggalSurat) AS TAHUN,
                MONTH(tanggalSurat) AS BULAN,
                COUNT(*) AS BERKAS_MASUK,
                COUNT(*) AS JUMLAH_SURAT,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "PEMUTAKHIRAN DATA" THEN 1 ELSE 0
                END) AS PEMUTAKHIRAN_DATA,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "BALIK NAMA" THEN 1 ELSE 0
                END) AS BALIK_NAMA,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "PENGGANTI SK IMBG HILANG" THEN 1 ELSE 0
                END) AS PENGGANTI_SK_IMBG_HILANG,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "BALIK NAMA & PENGGANTI SK IMBG HILANG" THEN 1 ELSE 0
                END) AS BALIK_NAMA_PENGGANTI_SK_IMBG_HILANG,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "PECAH & BALIK NAMA" THEN 1 ELSE 0
                END) AS PECAH_BALIK_NAMA,
                SUM(CASE
                    WHEN jenisSurat IN ("format-1", "format-2", "format-3") AND tujuanSurat = "LEGALISIR" THEN 1 ELSE 0
                END) AS LEGALISIR,
                SUM(CASE
                    WHEN jenisSurat = "format-4"  AND kabupaten = "3201" THEN 1 ELSE 0
                END) AS FORMAT_BARU,
                SUM(CASE
                    WHEN jenisSurat = "format-4"  AND kabupaten != "3201" THEN 1 ELSE 0
                END) AS LINTAS
            ')
            ->whereYear('tanggalSurat', $tahun)
            ->groupByRaw('YEAR(tanggalSurat), MONTH(tanggalSurat)')
            ->orderByRaw('YEAR(tanggalSurat), MONTH(tanggalSurat)')
            ->get();

        return view('rekap.rekap-sk-imbg.rekap-sk-imbg-perbulan', compact('data'));
    }

    public function RekapSuratPertahun()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

        $data = DB::table('dbsimpol.surat')
            ->selectRaw('
            tahun AS TAHUN,
            COUNT(*) AS BERKAS_MASUK,
            COUNT(*) AS JUMLAH_SURAT,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "PEMUTAKHIRAN DATA" THEN 1 ELSE 0
            END) AS PEMUTAKHIRAN_DATA,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "BALIK NAMA" THEN 1 ELSE 0
            END) AS BALIK_NAMA,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "PENGGANTI SK IMBG HILANG" THEN 1 ELSE 0
            END) AS PENGGANTI_SK_IMBG_HILANG,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "BALIK NAMA & PENGGANTI SK IMBG HILANG" THEN 1 ELSE 0
            END) AS BALIK_NAMA_PENGGANTI_SK_IMBG_HILANG,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "PECAH & BALIK NAMA" THEN 1 ELSE 0
            END) AS PECAH_BALIK_NAMA,
            SUM(CASE
                WHEN jenisSurat IN ("format-1","format-2","format-3") AND tujuanSurat = "LEGALISIR" THEN 1 ELSE 0
            END) AS LEGALISIR,
            SUM(CASE
                WHEN jenisSurat = "format-4" AND kabupaten = "3201" THEN 1 ELSE 0
            END) AS FORMAT_BARU,
            SUM(CASE
                WHEN jenisSurat = "format-4" AND kabupaten != "3201" THEN 1 ELSE 0
            END) AS LINTAS
        ')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        return view('rekap.rekap-sk-imbg.rekap-sk-imbg-pertahun', compact('data'));
    }
}
