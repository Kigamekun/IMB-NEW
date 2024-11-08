<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IMBIndukPerum;
use App\Imports\ImportIMBPecahan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IMBPecahan;
use \Yajra\DataTables\DataTables;


use App\Exports\IMBPecahanExport;
use Carbon\Carbon;

use Maatwebsite\Excel\Excel as ExcelType;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\IMBPerluasan;
use App\Models\IMBIndukNonPerum;


class SinkronisasiLokasiIMBController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {

            switch ($_GET['type']) {
                case '':
                    $data = IMBIndukPerum::join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                        ->where([
                            ['imb_induk_perum.kecamatan', '=', null],
                            ['imb_induk_perum.desa_kelurahan', '=', null]
                        ])
                        ->select('imb_induk_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan')->get();
                    break;
                case 'pecahan':
                    $data = IMBPecahan::join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                        ->where([
                            ['imb_pecahan.kecamatan', '=', null],
                            ['imb_pecahan.desa_kelurahan', '=', null]
                        ])
                        ->select('imb_pecahan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan')->get();
                    break;
                case 'perluasan':
                    $data = IMBPerluasan::join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                        ->where([
                            ['imb_perluasan.kecamatan', '=', null],
                            ['imb_perluasan.desa_kelurahan', '=', null]
                        ])
                        ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan')->get();
                    break;

                case 'non_perum':
                    $data = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                        ->where([
                            ['imb_induk_non_perum.kecamatan', '=', null],
                            ['imb_induk_non_perum.desa_kelurahan', '=', null]
                        ])
                        ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan')->get();
                    break;
                default:
                    # code...
                    break;
            }
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex" style="gap:10px;">
                        <a href="' . route('IMBPecahan.edit', $row->id) . '" class="edit btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('IMBPecahan.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">Hapus</button>
                        </form>
                    </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('SinkronisasiLokasiIMB.index');
    }



    public function hubungkan(Request $request)
    {
        return view('SinkronisasiLokasiIMB.hubungkan');
    }

    public function hubungkanStore(Request $request)
    {
        if (isset($_GET['type'])) {
            switch ($_GET['type']) {
                case '':
                    DB::table('imb_induk_perum')
                        ->where([
                            'kecamatan' => $request->kecamatan_lama,
                            'desa_kelurahan' => $request->kelurahan_lama
                        ])
                        ->update([
                            'kecamatan' => $request->kecamatan,
                            'desa_kelurahan' => $request->desa_kelurahan
                        ]);
                    break;
                case 'pecahan':
                    DB::table('imb_pecahan')
                        ->where([
                            'kecamatan_lama' => $request->kecamatan_lama,
                            'kelurahan_lama' => $request->kelurahan_lama
                        ])
                        ->update([
                            'kecamatan' => $request->kecamatan,
                            'desa_kelurahan' => $request->desa_kelurahan
                        ]);

                    break;
                case 'perluasan':
                    DB::table('imb_perluasan')
                        ->where([
                            'kecamatan_lama' => $request->kecamatan_lama,
                            'kelurahan_lama' => $request->kelurahan_lama
                        ])
                        ->update([
                            'kecamatan' => $request->kecamatan,
                            'desa_kelurahan' => $request->desa_kelurahan
                        ]);
                    break;
                case 'non_perum':
                    DB::table('imb_induk_non_perum')
                        ->where([
                            'kecamatan_lama' => $request->kecamatan_lama,
                            'kelurahan_lama' => $request->kelurahan_lama
                        ])
                        ->update([
                            'kecamatan' => $request->kecamatan,
                            'desa_kelurahan' => $request->desa_kelurahan
                        ]);
                    break;

                default:
                    # code...
                    break;
            }
        } else {
            DB::table('imb_induk_perum')
                ->where([
                    'kecamatan_lama' => $request->kecamatan_lama,
                    'kelurahan_lama' => $request->kelurahan_lama
                ])
                ->update([
                    'kecamatan' => $request->kecamatan,
                    'desa_kelurahan' => $request->desa_kelurahan
                ]);
        }

        $type = request()->query('type'); // Get the 'type' parameter from the query string

        // Build the redirect route with the optional 'type' parameter
        $route = route('SinkronisasiLokasiIMB.index', $type ? ['type' => $type] : []);

        return redirect($route)->with([
            'success' => 'Data berhasil dipairkan',
            'status' => 'success',
            'message' => 'Data berhasil dipairkan'
        ]);

    }




}
