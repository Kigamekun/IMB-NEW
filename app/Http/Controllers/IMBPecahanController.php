<?php

namespace App\Http\Controllers;

use App\Models\IMBIndukPerum;
use Illuminate\Http\Request;
use App\Imports\ImportIMBPecahan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IMBPecahan;
use \Yajra\DataTables\DataTables;

use App\Exports\IMBPecahanExport;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IMBPecahanController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBPecahan::join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_pecahan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')->get();
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
        return view('IMBPecahan.index');
    }

    public function import()
    {
        return view('IMBPecahan.import');
    }

    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportIMBPecahan(), $file);
        return redirect()->route('IMBPecahan.index');
    }

    public function create()
    {
        return view('IMBPecahan.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_imb_induk' => 'required|string',
            'tgl_imb_induk' => 'required|date',
            'no_imb_pecahan' => 'required|string',
            'tgl_pecahan' => 'required|date',
            'no_register' => 'nullable|string',
            'tgl_register' => 'nullable|date',
            'nama' => 'required|string',
            'atas_nama' => 'nullable|string',
            'jenis_kegiatan' => 'required|string',
            'fungsi_bangunan' => 'required|string',
            'lokasi_perumahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'desa_kelurahan' => 'nullable|string',
            'type' => 'nullable|string',
            'luas' => 'nullable|numeric',
            'blok' => 'nullable|string',
            'no_blok' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'scan_imb' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }
        $imbInduk = explode(' | ', $validatedData['no_imb_induk']);
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

        DB::table('imb_pecahan')->insert([
            'imb_induk_id' => $imbInduk[0],
            'tgl_imb_induk' => $validatedData['tgl_imb_induk'],
            'imb_pecahan' => $validatedData['no_imb_pecahan'],
            'tgl_imb_pecahan' => $validatedData['tgl_pecahan'],
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
            'luas' => $validatedData['luas'],
            'blok' => $validatedData['blok'],
            'no_blok' => $validatedData['no_blok'],
            'keterangan' => $validatedData['keterangan'],
            'scan_imb' => $scanImbPath,
        ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $data = IMBPecahan::join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_pecahan.*', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_pecahan.id', $id)->first();

        $imbInduk = IMBIndukPerum::where('imb_induk', $data->imb_induk_id)->first();
        return view('IMBPecahan.edit', compact('data', 'imbInduk'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'no_imb_induk' => 'required|string',
            'tgl_imb_induk' => 'required|date',
            'no_imb_pecahan' => 'required|string',
            'tgl_pecahan' => 'required|date',
            'no_register' => 'nullable|string',
            'tgl_register' => 'nullable|date',
            'nama' => 'required|string',
            'atas_nama' => 'nullable|string',
            'jenis_kegiatan' => 'required|string',
            'fungsi_bangunan' => 'required|string',
            'lokasi_perumahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'desa_kelurahan' => 'nullable|string',
            'type' => 'nullable|string',
            'luas' => 'nullable|numeric',
            'blok' => 'nullable|string',
            'no_blok' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'scan_imb' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $scanImbPath = null;
        if ($request->hasFile('scan_imb')) {
            $scanImbPath = $request->file('scan_imb')->store('scans');
        }

        $imbInduk = explode(' | ', $validatedData['no_imb_induk']);

        $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
            ->where('name_jeniskeg', $validatedData['jenis_kegiatan'])
            ->first();

        if (!$jenisKegiatanRecord) {
            dd($validatedData['jenis_kegiatan']);
            // Jika belum ada, insert dan dapatkan ID baru
            $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                'name_jeniskeg' => $validatedData['jenis_kegiatan']
            ]);
        } else {
            // Jika sudah ada, gunakan ID yang ditemukan
            $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
        }

        DB::table('imb_pecahan')->where('id', $id)->update([
            'imb_induk_id' => $imbInduk[0],
            'tgl_imb_induk' => $validatedData['tgl_imb_induk'],
            'imb_pecahan' => $validatedData['no_imb_pecahan'],
            'tgl_imb_pecahan' => $validatedData['tgl_pecahan'],
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
            'luas' => $validatedData['luas'],
            'blok' => $validatedData['blok'],
            'no_blok' => $validatedData['no_blok'],
            'keterangan' => $validatedData['keterangan'],
            'scan_imb' => $scanImbPath,
        ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil diubah']);
    }

    public function destroy($id)
    {
        IMBPecahan::destroy($id);
        return redirect()->back()->with(['success' => 'Data berhasil dihapus']);
    }

    public function exportData()
    {
        return Excel::download(new IMBPecahanExport, 'IMBPecahan' . Carbon::now()->timestamp . '.xlsx');
    }

    public function downloadTemplate()
    {
        $template = public_path('template/imb_pecahan_contoh.xlsx');
        return response()->download($template);
    }
}
