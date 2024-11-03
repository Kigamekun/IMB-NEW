<?php

namespace App\Http\Controllers;

use App\Models\IMBItem;
use Illuminate\Http\Request;
use App\Imports\ImportIMBIndukPerum;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IMBIndukPerum;
use \Yajra\DataTables\DataTables;
use App\Exports\IMBIndukPerumExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IMBIndukPerumController extends Controller
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
                        <div class="d-flex" style="gap:10px;">
                            <a href="' . route('IMBIndukPerum.edit', $row->id) . '" class="edit btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('IMBIndukPerum.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">Hapus</button>
                            </form>
                        </div>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('IMBIndukPerum.index');
    }

    public function create()
    {
        return view('IMBIndukPerum.create');
    }

    public function importData(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ImportIMBIndukPerum(), $file);
        return redirect()->route('IMBIndukPerum.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $jenisKegiatanArray = [];

            $indukPerumId = DB::table('imb_induk_perum')->insertGetId([
                'imb_induk' => $request->input('imb_induk'),
                'tgl_imb_induk' => $request->input('tgl_imb_induk'),
                'no_register' => $request->input('no_register'),
                'tgl_register' => $request->input('tgl_register'),
                'nama' => $request->input('nama'),
                'atas_nama' => $request->input('atas_nama'),
                'lokasi_perumahan' => $request->input('lokasi_perumahan'),
                'kecamatan' => $request->input('kecamatan'),
                'desa_kelurahan' => $request->input('kelurahan'),
            ]);

            $entryCount = $request->input('entryCount');
            for ($i = 0; $i < $entryCount; $i++) {
                $scanImbPath = null;
                if ($request->hasFile("scan_imb_$i")) {
                    $scanImbPath = $request->file("scan_imb_$i")->store('scans');
                }

                $jenisKegiatan = $request->input("jenis_kegiatan_$i");
                $jenisKegiatanArray[] = $jenisKegiatan;

                $kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $jenisKegiatan)->first()->id_jeniskeg;

                DB::table('item_imb_induk_perum')->insert([
                    'induk_perum_id' => $indukPerumId,
                    'jenis_kegiatan' => $kegiatan,
                    'fungsi_bangunan' => $request->input("fungsi_bangunan_$i"),
                    'type' => $request->input("type_$i"),
                    'luas_bangunan' => $request->input("luas_bangunan_$i"),
                    'jumlah_unit' => $request->input("jumlah_unit_$i"),
                    'keterangan' => $request->input("keterangan_$i"),
                    'scan_imb' => $scanImbPath,
                ]);
            }

            $jenisKegiatanGabungan = implode(' / ', array_unique($jenisKegiatanArray));

            // Cek apakah jenis_kegiatan_gabungan sudah ada di app_md_jeniskeg
            $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                ->where('name_jeniskeg', $jenisKegiatanGabungan)
                ->first();

            if (!$jenisKegiatanRecord) {
                // Jika belum ada, insert dan dapatkan ID baru
                $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                    'name_jeniskeg' => $jenisKegiatanGabungan
                ]);
            } else {
                // Jika sudah ada, gunakan ID yang ditemukan
                $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
            }

            // Update imb_induk_perum dengan jenis_kegiatan gabungan ID
            DB::table('imb_induk_perum')
                ->where('id', $indukPerumId)
                ->update(['jenis_kegiatan' => $jenisKegiatanId]);

            DB::commit();
            return redirect()->route('IMBIndukPerum.index')->with(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to save data: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data = IMBIndukPerum::join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_induk_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_induk_perum.id', $id)->first();
        $item = IMBItem::where('induk_perum_id', $id)->get();
        return view('IMBIndukPerum.edit', compact('data', 'item'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $jenisKegiatanArray = [];

            // Update imb_induk_perum
            DB::table('imb_induk_perum')->where('id', $id)->update([
                'imb_induk' => $request->input('imb_induk'),
                'tgl_imb_induk' => $request->input('tgl_imb_induk'),
                'no_register' => $request->input('no_register'),
                'tgl_register' => $request->input('tgl_register'),
                'nama' => $request->input('nama'),
                'atas_nama' => $request->input('atas_nama'),
                'lokasi_perumahan' => $request->input('lokasi_perumahan'),
                'kecamatan' => $request->input('kecamatan'),
                'desa_kelurahan' => $request->input('kelurahan'),
            ]);

            // Hapus item lama dan tambahkan yang baru
            DB::table('item_imb_induk_perum')->where('induk_perum_id', $id)->delete();

            $entryCount = $request->input('entryCount');
            for ($i = 0; $i < $entryCount; $i++) {
                $scanImbPath = null;
                if ($request->hasFile("scan_imb_$i")) {
                    $scanImbPath = $request->file("scan_imb_$i")->store('scans');
                }

                $jenisKegiatan = $request->input("jenis_kegiatan_$i");
                $jenisKegiatanArray[] = $jenisKegiatan;

                $kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $jenisKegiatan)->first()->id_jeniskeg;

                DB::table('item_imb_induk_perum')->insert([
                    'induk_perum_id' => $id,
                    'jenis_kegiatan' => $kegiatan,
                    'fungsi_bangunan' => $request->input("fungsi_bangunan_$i"),
                    'type' => $request->input("type_$i"),
                    'luas_bangunan' => $request->input("luas_bangunan_$i"),
                    'jumlah_unit' => $request->input("jumlah_unit_$i"),
                    'keterangan' => $request->input("keterangan_$i"),
                    'scan_imb' => $scanImbPath,
                ]);
            }

            $jenisKegiatanGabungan = implode(' / ', array_unique($jenisKegiatanArray));

            // Cek apakah jenis_kegiatan_gabungan sudah ada di app_md_jeniskeg
            $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                ->where('name_jeniskeg', $jenisKegiatanGabungan)
                ->first();

            if (!$jenisKegiatanRecord) {
                // Jika belum ada, insert dan dapatkan ID baru
                $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                    'name_jeniskeg' => $jenisKegiatanGabungan
                ]);
            } else {
                // Jika sudah ada, gunakan ID yang ditemukan
                $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
            }

            // Update imb_induk_perum dengan jenis_kegiatan gabungan ID
            DB::table('imb_induk_perum')
                ->where('id', $id)
                ->update(['jenis_kegiatan' => $jenisKegiatanId]);

            DB::commit();
            return redirect()->route('IMBIndukPerum.index')->with(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        IMBIndukPerum::destroy($id);
        IMBitem::where('induk_perum_id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function exportData()
    {
        try {
            return Excel::download(new IMBIndukPerumExport, 'IMBIndukPerum' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Throwable $th) {
            return redirect()->back()->with(['status' => 'errors', 'message' => 'Anda memiliki kesalahan format']);
        }
    }

    public function downloadTemplate()
    {
        $template = public_path('template/IMBIndukPerum.xlsx');
        return response()->download($template);
    }
}
