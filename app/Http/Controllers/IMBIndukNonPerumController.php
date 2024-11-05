<?php

namespace App\Http\Controllers;

use App\Models\IMBIndukNonPerum;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IMBIndukNonPerumExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Imports\ImportIMBIndukNonPerum;
use \Yajra\DataTables\DataTables;

class IMBIndukNonPerumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_subdistrict.name as kelurahan')
                ->get();
            return Datatables::of($data)
                ->addColumn('jenis', function ($row) {
                    $master = DB::table('master_jenis_non_perum')->where('id', $row->contoh_jenis)->first();
                    return '<span class="badge badge-primary">' . $master->name . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;">
                            <a href="' . route('IMBIndukNonPerum.edit', $row->id) . '" class="edit btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('IMBIndukNonPerum.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>';
                })
                ->rawColumns(['action', 'jenis'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('IMBIndukNonPerum.index');
    }

    public function create()
    {
        return view('IMBIndukNonPerum.create');
    }

    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportIMBIndukNonPerum(), $file);
        return redirect()->route('IMBIndukNonPerum.index');
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'contoh_jenis' => 'required',
            'imb_induk_non_perum' => 'required',
            'tgl_imb_induk_non_perum' => 'required',
            'no_register' => 'required',
            'tgl_register' => 'required',
            'nama' => 'required',
            'atas_nama' => 'required',
            'lokasi_perumahan' => 'required',
            'kecamatan' => 'required',
            'desa_kelurahan' => 'required',
            'jenis_kegiatan' => 'required',
            'luas' => 'required',
            'detail_luas_bangunan' => 'required',
            'keterangan' => 'required',
            'scan_imb' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jenis_buku' => 'required',
            'fungsi_bangunan' => 'required',
        ]);

        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }

        DB::table('imb_induk_non_perum')->insert([
            'contoh_jenis' => $validatedData['contoh_jenis'],
            'imb_induk_non_perum' => $validatedData['imb_induk_non_perum'],
            'tgl_imb_induk_non_perum' => $validatedData['tgl_imb_induk_non_perum'],

            'no_register' => $validatedData['no_register'],
            'tgl_register' => $validatedData['tgl_register'],
            'nama' => $validatedData['nama'],
            'atas_nama' => $validatedData['atas_nama'],
            'lokasi' => $validatedData['lokasi_perumahan'],
            'kecamatan' => $validatedData['kecamatan'],
            'desa_kelurahan' => $validatedData['desa_kelurahan'],
            'jenis_kegiatan' => $validatedData['jenis_kegiatan'],
            'luas_bangunan' => $validatedData['luas'],
            'detail_luas_bangunan' => $validatedData['detail_luas_bangunan'],
            'keterangan' => $validatedData['keterangan'],
            'scan_imb' => $scanImbPath,
            'jenis_buku' => $validatedData['jenis_buku'],
            'fungsi_bangunan' => $validatedData['fungsi_bangunan'],


        ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
    }
    public function edit($id)
    {
        $data = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_induk_non_perum.id', $id)->first();

        return view('IMBIndukNonPerum.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'contoh_jenis' => 'required',
            'imb_induk_non_perum' => 'required',
            'tgl_imb_induk_non_perum' => 'required',
            'no_register' => 'required',
            'tgl_register' => 'required',
            'nama' => 'required',
            'atas_nama' => 'required',
            'lokasi_perumahan' => 'required',
            'kecamatan' => 'required',
            'desa_kelurahan' => 'required',
            'jenis_kegiatan' => 'required',
            'luas' => 'required',
            'detail_luas_bangunan' => 'required',
            'keterangan' => 'required',
            'scan_imb' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jenis_buku' => 'required',
            'fungsi_bangunan' => 'required',
        ]);

        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }

        DB::table('imb_induk_non_perum')
            ->where('id', $id)
            ->update([
                'contoh_jenis' => $validatedData['contoh_jenis'],
                'imb_induk_non_perum' => $validatedData['imb_induk_non_perum'],
                'tgl_imb_induk_non_perum' => $validatedData['tgl_imb_induk_non_perum'],
                'no_register' => $validatedData['no_register'],
                'tgl_register' => $validatedData['tgl_register'],
                'nama' => $validatedData['nama'],
                'atas_nama' => $validatedData['atas_nama'],
                'lokasi' => $validatedData['lokasi_perumahan'],
                'kecamatan' => $validatedData['kecamatan'],
                'desa_kelurahan' => $validatedData['desa_kelurahan'],
                'jenis_kegiatan' => $validatedData['jenis_kegiatan'],
                'luas_bangunan' => $validatedData['luas'],
                'detail_luas_bangunan' => $validatedData['detail_luas_bangunan'],
                'keterangan' => $validatedData['keterangan'],
                'scan_imb' => $scanImbPath,
                'jenis_buku' => $validatedData['jenis_buku'],
                'fungsi_bangunan' => $validatedData['fungsi_bangunan'],
            ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil diubah']);

    }


    public function destroy($id)
    {
        IMBIndukNonPerum::destroy($id);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function exportData()
    {
        try {
            return Excel::download(new IMBIndukNonPerumExport, 'IMBIndukNonPerum' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['status' => 'errors', 'message' => 'Anda memiliki kesalahan format']);
        }
    }

    public function downloadTemplate()
    {
        $template = public_path('template/IMBIndukNonPerum.xlsx');
        return response()->download($template);
    }
}
