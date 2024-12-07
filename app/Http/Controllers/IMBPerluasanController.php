<?php

namespace App\Http\Controllers;

use App\Imports\ImportIMBPerluasan;
use App\Models\IMBPerluasan;
use Illuminate\Http\Request;
use \Yajra\DataTables\DataTables;
use App\Exports\IMBPerluasanExport;
use App\Models\IMBPecahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class IMBPerluasanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBPerluasan::join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')->get();
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
        return view('IMBPerluasan.index');
    }

    public function create()
    {
        return view('IMBPerluasan.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'imb_pecahan' => 'required|string|max:50',
            'tgl_imb_pecahan' => 'required|date',
            'no_imb_perluasan' => 'required|string|max:50',
            'tgl_imb_perluasan' => 'required|date',
            'no_register' => 'nullable|string|max:50',
            'tgl_register' => 'nullable|date',
            'nama' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:50',
            'jenis_kegiatan' => 'required|string|max:50',
            'fungsi_bangunan' => 'nullable|string|max:50',
            'lokasi_perumahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:50',
            'desa_kelurahan' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'luas_bangunan_lama' => 'nullable|string',
            'luas_bangunan_perluasan' => 'nullable|string',
            'blok' => 'nullable|string|max:20',
            'no_blok' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'scan_imb' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }

        $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
            ->where('name_jeniskeg', $validatedData['jenis_kegiatan'])
            ->first();

        if (!$jenisKegiatanRecord) {
            $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                'name_jeniskeg' => $validatedData['jenis_kegiatan']
            ]);
        } else {
            $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
        }

        $imbPecahan = explode(' | ', $validatedData['imb_pecahan']);

        DB::table('imb_perluasan')->insert([
            'imb_pecahan' => $imbPecahan[0],
            'tgl_imb_pecahan' => $validatedData['tgl_imb_pecahan'],
            'imb_perluasan' => $validatedData['no_imb_perluasan'],
            'tgl_imb_perluasan' => $validatedData['tgl_imb_perluasan'],
            'no_register' => $validatedData['no_register'],
            'tgl_register' => $validatedData['tgl_register'],
            'nama' => $validatedData['nama'],
            'atas_nama' => $validatedData['atas_nama'],
            'jenis_kegiatan' => $jenisKegiatanId,
            'fungsi_bangunan' => $validatedData['fungsi_bangunan'],
            'lokasi_perumahan' => $validatedData['lokasi_perumahan'],
            'kecamatan' => $validatedData['kecamatan'],
            'desa_kelurahan' => $validatedData['desa_kelurahan'],
            'type' => $validatedData['type'],
            'luas_bangunan_lama' => $validatedData['luas_bangunan_lama'],
            'luas_bangunan_perluasan' => $validatedData['luas_bangunan_perluasan'],
            'blok' => $validatedData['blok'],
            'no_blok' => $validatedData['no_blok'],
            'keterangan' => $validatedData['keterangan'],
            'scan_imb' => $scanImbPath,
        ]);

        return redirect()->route('IMBPerluasan.index')->with(['status' => 'success', 'message' => 'IMBPerluasan created successfully!']);
    }

    public function edit($id)
    {
        $data = IMBPerluasan::join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_perluasan.id', $id)->first();

        $imbPecahan = IMBPecahan::where('imb_pecahan', $data->imb_pecahan)->first();


        return view('IMBPerluasan.edit', compact('data', 'imbPecahan'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'imb_pecahan' => 'required|string|max:50',
            'tgl_imb_pecahan' => 'required|date',
            'imb_perluasan' => 'required|string|max:50',
            'tgl_imb_perluasan' => 'required|date',
            'no_register' => 'nullable|string|max:50',
            'tgl_register' => 'nullable|date',
            'nama' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:50',
            'jenis_kegiatan' => 'required|string|max:50',
            'lokasi_perumahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:50',
            'desa_kelurahan' => 'required|string|max:50',
            'type' => 'nullable|string|max:50',
            'luas_bangunan_lama' => 'nullable|string',
            'luas_bangunan_perluasan' => 'nullable|string',
            'blok' => 'nullable|string|max:20',
            'no_blok' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'scan_imb' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }

        $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
            ->where('name_jeniskeg', $validatedData['jenis_kegiatan'])
            ->first();

        if (!$jenisKegiatanRecord) {
            // Jika belum ada, insert dan dapatkan ID baru
            $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                'name_jeniskeg' => $validatedData['jenis_kegiatan']
            ]);
        } else {
            // Jika sudah ada, gunakan ID yang ditemukan
            $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
        }

        if (!is_null($scanImbPath)) {
            DB::table('imb_perluasan')->where('id', $id)->update([
                'imb_pecahan' => $validatedData['imb_pecahan'],
                'tgl_imb_pecahan' => $validatedData['tgl_imb_pecahan'],
                'imb_perluasan' => $validatedData['imb_perluasan'],
                'tgl_imb_perluasan' => $validatedData['tgl_imb_perluasan'],
                'no_register' => $validatedData['no_register'],
                'tgl_register' => $validatedData['tgl_register'],
                'nama' => $validatedData['nama'],
                'atas_nama' => $validatedData['atas_nama'],
                'jenis_kegiatan' => $jenisKegiatanId,
                'lokasi_perumahan' => $validatedData['lokasi_perumahan'],
                'kecamatan' => $validatedData['kecamatan'],
                'desa_kelurahan' => $validatedData['desa_kelurahan'],
                'type' => $validatedData['type'],
                'luas_bangunan_lama' => $validatedData['luas_bangunan_lama'],
                'luas_bangunan_perluasan' => $validatedData['luas_bangunan_perluasan'],
                'blok' => $validatedData['blok'],
                'no_blok' => $validatedData['no_blok'],
                'keterangan' => $validatedData['keterangan'],
                'scan_imb' => $scanImbPath,
            ]);
        } else {
            DB::table('imb_perluasan')->where('id', $id)->update([
                'imb_pecahan' => $validatedData['imb_pecahan'],
                'tgl_imb_pecahan' => $validatedData['tgl_imb_pecahan'],
                'imb_perluasan' => $validatedData['imb_perluasan'],
                'tgl_imb_perluasan' => $validatedData['tgl_imb_perluasan'],
                'no_register' => $validatedData['no_register'],
                'tgl_register' => $validatedData['tgl_register'],
                'nama' => $validatedData['nama'],
                'atas_nama' => $validatedData['atas_nama'],
                'jenis_kegiatan' => $jenisKegiatanId,
                'lokasi_perumahan' => $validatedData['lokasi_perumahan'],
                'kecamatan' => $validatedData['kecamatan'],
                'desa_kelurahan' => $validatedData['desa_kelurahan'],
                'type' => $validatedData['type'],
                'luas_bangunan_lama' => $validatedData['luas_bangunan_lama'],
                'luas_bangunan_perluasan' => $validatedData['luas_bangunan_perluasan'],
                'blok' => $validatedData['blok'],
                'no_blok' => $validatedData['no_blok'],
                'keterangan' => $validatedData['keterangan'],
            ]);
        }

        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil diupdate']);

    }

    public function destroy($id)
    {
        DB::table('imb_perluasan')->where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportIMBPerluasan(), $file);
        return redirect()->route('IMBPerluasan.index');
    }

    public function exportData()
    {
        return Excel::download(new IMBPerluasanExport, 'IMBPerluasan' . Carbon::now()->timestamp . '.xlsx');
    }

    public function downloadTemplate()
    {
        $template = public_path('template/imb_perluasan_contoh.xlsx');
        return response()->download($template);
    }
}
