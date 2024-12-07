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
use Rap2hpoutre\FastExcel\FastExcel;

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
        return view('IMBPecahan.index');
    }

    public function import()
    {
        return view('IMBPecahan.import');
    }

    public function importData(Request $request)
    {
        ini_set('max_execution_time', 0); // Unlimited execution time
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $file = $request->file('file');
        $failures = [];
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $baris = 1;
        $users = (new FastExcel)->import($file, function ($line) use (&$failures, &$baris, $jenisKegiatanList, $fungsiBangunanList) {
            $fail = 0;
            $rowJenisKegiatan = strtolower($line['Jenis Kegiatan']);
            $rowFungsiBangunan = strtolower($line['Fungsi Bangunan']);
            $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
            $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
            $rowDistrict = strtolower($line['Kecamatan']);
            $rowSubdistrict = strtolower($line['Desa / Kelurahan']);
            $districts = DB::table('master_district')
                ->where(DB::raw('LOWER(name)'), $rowDistrict)
                ->pluck('code')
                ->toArray();
            if (empty($districts)) {
                $fail = 1;
                IMBPecahan::create([
                    'imb_induk_id' => $line['No. IMB Induk'],
                    'tgl_imb_induk' => null,
                    'imb_pecahan' => $line['No. IMB Pecahan / Rincikan'],
                    'tgl_imb_pecahan' => null,
                    'no_register' => $line['No. Register'],
                    'tgl_register' => null,
                    'nama' => $line['Nama'],
                    'atas_nama' => $line['Atas Nama'],
                    'jenis_kegiatan' => $jenis_kegiatan,
                    'fungsi_bangunan' => $fungsi_bangunan,
                    'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                    'kecamatan_lama' => $line['Kecamatan'],
                    'kelurahan_lama' => $line['Desa / Kelurahan'],
                    'type' => $line['Type'],
                    'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                    'blok' => $line['Blok'],
                    'no_blok' => $line['NO BLOK'],
                    'keterangan' => $line['Keterangan']
                ]);
                $failures[] = [
                    'message' => 'Kecamatan ' . $line['Kecamatan'] . ' tidak ditemukan',
                    'baris' => $baris,
                ];
            } else {
                $village = DB::table('master_subdistrict')
                    ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
                    ->whereIn('district_code', $districts)
                    ->first();
                if (!$village) {
                    $fail = 1;
                    IMBPecahan::create([
                        'imb_induk_id' => $line['No. IMB Induk'],
                        'tgl_imb_induk' => null,
                        'imb_pecahan' => $line['No. IMB Pecahan / Rincikan'],
                        'tgl_imb_pecahan' => null,
                        'no_register' => $line['No. Register'],
                        'tgl_register' => null,
                        'nama' => $line['Nama'],
                        'atas_nama' => $line['Atas Nama'],
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                        'kecamatan_lama' => $line['Kecamatan'],
                        'kelurahan_lama' => $line['Desa / Kelurahan'],
                        'type' => $line['Type'],
                        'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                        'blok' => $line['Blok'],
                        'no_blok' => $line['NO BLOK'],
                        'keterangan' => $line['Keterangan']
                    ]);
                    $failures[] = [
                        'message' => 'Desa/Kelurahan ' . $line['Desa / Kelurahan'] . ' tidak ditemukan di kecamatan ' . $line['Kecamatan'],
                        'baris' => $baris,
                    ];
                }
            }
            $baris++;
            if ($fail == 0) {
                IMBPecahan::create([
                    'imb_induk_id' => $line['No. IMB Induk'],
                    'tgl_imb_induk' => null,
                    'imb_pecahan' => $line['No. IMB Pecahan / Rincikan'],
                    'tgl_imb_pecahan' => null,
                    'no_register' => $line['No. Register'],
                    'tgl_register' => null,
                    'nama' => $line['Nama'],
                    'atas_nama' => $line['Atas Nama'],
                    'jenis_kegiatan' => $jenis_kegiatan,
                    'fungsi_bangunan' => $fungsi_bangunan,
                    'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                    'kecamatan' => $village->district_code,
                    'desa_kelurahan' => $village->code,
                    'type' => $line['Type'],
                    'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                    'blok' => $line['Blok'],
                    'no_blok' => $line['NO BLOK'],
                    'keterangan' => $line['Keterangan']
                ]);
            }
        });
        if (count($failures) > 0) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Import data selesai, namun terdapat kesalahan. Silahkan download file log untuk melihat detail kesalahan.'])->with('failures', $failures);
        } else {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Import data berhasil']);
        }
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
        return redirect()->route('IMBPecahan.index')->with(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
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

       if (!is_null($scanImbPath)) {
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
       } else {
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
        ]);
       }
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
