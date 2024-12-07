<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use \Yajra\DataTables\DataTables;
class RekapController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBIndukPerum::join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_induk_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_subdistrict.name as kelurahan')
                ->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;display:flex;">
                            <a href="' . route('IMBIndukPerum.edit', $row->id) . '" class="edit btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                            <form action="' . route('IMBIndukPerum.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        // return view('rekap.1');
        // return view('rekap.1-1');
        // return view('rekap.2');
        // return view('rekap.2-1');
        // return view('rekap.2-2');
        // return view('rekap.3');
        // return view('rekap.3-1');
        // return view('rekap.3-2');
        // return view('rekap.4');
        // return view('rekap.4-1');
        // return view('rekap.4-2');
        // return view('rekap.5');
        // return view('rekap.5-1');
        // return view('rekap.6');
        // return view('rekap.6-1');
        return view('rekap.index');
    }

    public function RegisterIMBPerTahun(Request $request)
    {
        return view('rekap.register-imb-per-tahun');
    }

    // REKAP 5
    public function DetailIMBInduk(Request $request)
    {
        return view('rekap.detail-imb-induk');
    }

    public function DetailIMBIndukList(Request $request)
    {
        if ($request->ajax()) {
            $nama_pengembang = $_GET['nama_pengembang'];
            $nama_perumahan = $_GET['nama_perumahan'];
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

            $data = $data->get();

            return Datatables::of($data)

                ->addColumn('NAMA_PENGEMBANG', function ($row) {
                    return
                        '<a href="' . route('rekap.DetailIMBIndukListNamaPemohon', $row->nama_pengembang) . '">' . $row->nama_pengembang . '</a>';
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
    }

    public function DetailIMBIndukListNamaPemohon(Request $request, $nama_pemohon)
    {
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
                    return '';
                    // '<a href="' . route('rekap.RegisterIMBPerTahunNamaPemohon', $row->nama_pengembang) . '">' . $row->nama_pengembang . '</a>';
                })
                ->rawColumns(['action', 'NAMA_PENGEMBANG'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('rekap.detail-imb-induk-list-nama-pemohon', ['nama_pemohon' => $nama_pemohon]);

    }


    // REKAP 6
    public function DetailIMBPecahan(Request $request)
    {
        return view('rekap.detail-imb-pecahan');
    }

    public function DetailIMBPecahanList(Request $request)
    {
        if ($request->ajax()) {

            $imbInduk = $_GET['imbInduk'];
            // $nama_perumahan = $_GET['nama_perumahan'];
            $data = DB::table('imb_induk_perum as induk')
                ->join('item_imb_induk_perum as item', 'induk.id', '=', 'item.induk_perum_id')
                ->leftJoin('imb_pecahan as pecahan', function ($join) {
                    $join->on('pecahan.imb_induk_id', '=', 'induk.imb_induk')
                        ->on('pecahan.type', '=', 'item.type');
                })
                ->selectRaw('
                ROW_NUMBER() OVER (ORDER BY item.type) AS NO,
                item.type AS TIPE,
                SUM(item.jumlah_unit) + COUNT(pecahan.id) AS UNIT,
                COUNT(pecahan.id) AS SUDAH_PECAH
            ')
                ->where('induk.imb_induk', $imbInduk) // Filter untuk imb_induk tertentu
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

    // Rekap 1
    public function RekapPenerbitan(Request $request)
    {
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

    public function RekapPenerbitanDetail(Request $request)
    {
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
        $data = DB::table(function ($query) {
            $query->selectRaw('YEAR(tgl_imb_induk) as tahun, COUNT(DISTINCT imb_induk_perum.id) as imb_induk_perumahan, SUM(item_imb_induk_perum.jumlah_unit) as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan, 0 as hunian_imb, 0 as hunian_unit, 0 as usaha_imb, 0 as usaha_unit, 0 as sosbud_imb, 0 as sosbud_unit, 0 as khusus_imb, 0 as khusus_unit, 0 as campuran_imb, 0 as campuran_unit')
                ->from('imb_induk_perum')
                ->leftJoin('item_imb_induk_perum', 'imb_induk_perum.id', '=', 'item_imb_induk_perum.induk_perum_id')
                ->groupByRaw('YEAR(tgl_imb_induk)')
                ->unionAll(
                    DB::table('imb_pecahan')
                        ->selectRaw('YEAR(tgl_imb_induk) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, COUNT(*) as imb_pecahan, SUM(luas) as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan, 0 as hunian_imb, 0 as hunian_unit, 0 as usaha_imb, 0 as usaha_unit, 0 as sosbud_imb, 0 as sosbud_unit, 0 as khusus_imb, 0 as khusus_unit, 0 as campuran_imb, 0 as campuran_unit')
                        ->groupByRaw('YEAR(tgl_imb_induk)')
                )
                ->unionAll(
                    DB::table('imb_perluasan')
                        ->selectRaw('YEAR(tgl_imb_perluasan) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, COUNT(*) as imb_perluasan, COALESCE(SUM(luas_bangunan_perluasan), 0) as unit_perluasan, 0 as imb_non_perumahan, 0 as unit_non_perumahan, 0 as hunian_imb, 0 as hunian_unit, 0 as usaha_imb, 0 as usaha_unit, 0 as sosbud_imb, 0 as sosbud_unit, 0 as khusus_imb, 0 as khusus_unit, 0 as campuran_imb, 0 as campuran_unit')
                        ->groupByRaw('YEAR(tgl_imb_perluasan)')
                )
                ->unionAll(
                    DB::table('imb_induk_non_perum')
                        ->selectRaw('YEAR(tgl_imb_induk) as tahun, 0 as imb_induk_perumahan, 0 as unit_induk_perumahan, 0 as imb_pecahan, 0 as unit_pecahan, 0 as imb_perluasan, 0 as unit_perluasan, COUNT(DISTINCT imb_induk_non_perum.id) as imb_non_perumahan, COALESCE(SUM(item_imb_induk_non_perum.jumlah_unit), 0) as unit_non_perumahan, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 1 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 4 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_imb, SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 5 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit')
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

    public function RekapUnitDanFungsiDetail(Request $request)
    {
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
                0 as hunian_imb, 0 as hunian_unit,
                0 as usaha_imb, 0 as usaha_unit,
                0 as sosbud_imb, 0 as sosbud_unit,
                0 as khusus_imb, 0 as khusus_unit,
                0 as campuran_imb, 0 as campuran_unit
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
                        SUM(CASE WHEN fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_unit
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
                        SUM(CASE WHEN fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_unit
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
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "perusahaan" THEN 1 ELSE 0 END) as imb_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "perusahaan" THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perusahaan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "perorangan" THEN 1 ELSE 0 END) as imb_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "perorangan" THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_perorangan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "sosbud" THEN 1 ELSE 0 END) as imb_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "sosbud" THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_non_perumahan_sosbud,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "pemutihan" THEN 1 ELSE 0 END) as imb_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "pemutihan" THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_pemutihan,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "bersyarat" THEN 1 ELSE 0 END) as imb_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis = "bersyarat" THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_bersyarat,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN ("perusahaan", "perorangan", "sosbud", "pemutihan", "bersyarat") THEN 1 ELSE 0 END) as imb_lainnya,
                        SUM(CASE WHEN imb_induk_non_perum.jenis NOT IN ("perusahaan", "perorangan", "sosbud", "pemutihan", "bersyarat") THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as unit_lainnya,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 1 THEN 1 ELSE 0 END) as hunian_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 1 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as hunian_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN 1 ELSE 0 END) as usaha_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 2 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as usaha_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN 1 ELSE 0 END) as sosbud_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 3 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as sosbud_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 4 THEN 1 ELSE 0 END) as khusus_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 4 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as khusus_unit,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 5 THEN 1 ELSE 0 END) as campuran_imb,
                        SUM(CASE WHEN item_imb_induk_non_perum.fungsi_bangunan = 5 THEN item_imb_induk_non_perum.jumlah_unit ELSE 0 END) as campuran_unit
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
        $data = DB::select("
            SELECT
                base.kecamatan,
                base.desa_kelurahan,
                YEAR(base.tgl_register) AS tahun,
                COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
                COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
                COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
                COUNT(DISTINCT imb_induk_non_perum.id) AS imb_non_perumahan,
                (
                    COUNT(DISTINCT imb_induk_perum.id) +
                    COUNT(DISTINCT imb_pecahan.id) +
                    COUNT(DISTINCT imb_perluasan.id) +
                    COUNT(DISTINCT imb_induk_non_perum.id)
                ) AS jumlah_imb
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
            LEFT JOIN imb_pecahan ON base.kecamatan = imb_pecahan.kecamatan AND base.desa_kelurahan = imb_pecahan.desa_kelurahan
            LEFT JOIN imb_perluasan ON base.kecamatan = imb_perluasan.kecamatan AND base.desa_kelurahan = imb_perluasan.desa_kelurahan
            LEFT JOIN imb_induk_non_perum ON base.kecamatan = imb_induk_non_perum.kecamatan AND base.desa_kelurahan = imb_induk_non_perum.desa_kelurahan
            GROUP BY base.kecamatan, base.desa_kelurahan, YEAR(base.tgl_register)
            ORDER BY base.kecamatan, base.desa_kelurahan, tahun;
        ");


        return view('rekap.rekap-imb.rekap-lokasi', compact('data'));
    }




    // Rekap 4
    public function RekapUnitFungsiDanLokasi()
    {
        $data = DB::select("
           SELECT
                base.kecamatan AS kecamatan,
                base.desa_kelurahan AS desa_kelurahan,
                YEAR(base.tgl_register) AS tahun,
                COUNT(DISTINCT imb_induk_perum.id) AS imb_induk_perum,
                COUNT(DISTINCT imb_pecahan.id) AS imb_pecahan,
                COUNT(DISTINCT imb_perluasan.id) AS imb_perluasan,
                COUNT(DISTINCT imb_induk_non_perum.id) AS imb_non_perumahan,
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

        return view('rekap.rekap-imb.rekap-unit-fungsi-dan-lokasi', compact('data'));
    }


































    public function RegisterIMBPerTahunStore(Request $request)
    {
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

    public function DetailIMBIndukNamaPemohon(Request $request, $nama_pemohon)
    {
        if ($request->ajax()) {
            // Query untuk IMB Induk
            // Query untuk IMB Induk
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
                   'HUNIAN' AS FUNGSI_BANGUNAN,
                   1000 AS JUMLAH_UNIT
               ")
                ->leftJoin('master_district AS kecamatan_induk', 'kecamatan_induk.id', '=', 'imb_induk_perum.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_induk', 'kelurahan_induk.id', '=', 'imb_induk_perum.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_induk', 'jenis_kegiatan_induk.id_jeniskeg', '=', 'imb_induk_perum.jenis_kegiatan')
                ->where('imb_induk_perum.nama', $nama_pemohon);

            // Query untuk IMB Pecahan
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
                   imb_pecahan.luas AS JUMLAH_UNIT
               ")
                ->leftJoin('master_district AS kecamatan_pecahan', 'kecamatan_pecahan.id', '=', 'imb_pecahan.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_pecahan', 'kelurahan_pecahan.id', '=', 'imb_pecahan.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_pecahan', 'jenis_kegiatan_pecahan.id_jeniskeg', '=', 'imb_pecahan.jenis_kegiatan')
                ->leftJoin('app_md_fungsibang AS fungsi_bangunan_pecahan', 'fungsi_bangunan_pecahan.id_fungsibang', '=', 'imb_pecahan.fungsi_bangunan')
                ->where('imb_pecahan.nama', $nama_pemohon);
            ;

            // Query untuk IMB Perluasan
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
                   imb_perluasan.luas_bangunan_perluasan AS JUMLAH_UNIT
               ")
                ->leftJoin('master_district AS kecamatan_perluasan', 'kecamatan_perluasan.id', '=', 'imb_perluasan.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_perluasan', 'kelurahan_perluasan.id', '=', 'imb_perluasan.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_perluasan', 'jenis_kegiatan_perluasan.id_jeniskeg', '=', 'imb_perluasan.jenis_kegiatan')
                ->leftJoin('app_md_fungsibang AS fungsi_bangunan_perluasan', 'fungsi_bangunan_perluasan.id_fungsibang', '=', 'imb_perluasan.fungsi_bangunan')
                ->where('imb_perluasan.nama', $nama_pemohon);
            ;

            // Query untuk IMB Induk Non Perumahan
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
                   'USAHA' AS FUNGSI_BANGUNAN,
                   1 AS JUMLAH_UNIT
               ")
                ->leftJoin('master_district AS kecamatan_non_perum', 'kecamatan_non_perum.id', '=', 'imb_induk_non_perum.kecamatan')
                ->leftJoin('master_subdistrict AS kelurahan_non_perum', 'kelurahan_non_perum.id', '=', 'imb_induk_non_perum.desa_kelurahan')
                ->leftJoin('app_md_jeniskeg AS jenis_kegiatan_non_perum', 'jenis_kegiatan_non_perum.id_jeniskeg', '=', 'imb_induk_non_perum.jenis_kegiatan')
                ->where('imb_induk_non_perum.nama', $nama_pemohon)
            ;

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







    public function IMBsubmit(Request $request)
    {
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
}
