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
use Rap2hpoutre\FastExcel\FastExcel;
class IMBIndukPerumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IMBIndukPerum::join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_induk_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_district.name as kecamatan', 'master_subdistrict.name as kelurahan')
                ->orderBy('imb_induk_perum.created_at', 'desc')
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
        return view('IMBIndukPerum.index');
    }
    public function create()
    {
        return view('IMBIndukPerum.create');
    }
    public function items(Request $request)
    {
        $data = IMBItem::join('app_md_jeniskeg', 'item_imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('app_md_fungsibang', 'item_imb_induk_perum.fungsi_bangunan', '=', 'app_md_fungsibang.id_fungsibang')
            ->select('item_imb_induk_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'app_md_fungsibang.name_fungsibang as fungsi_bangunan')
            ->where('induk_perum_id', $_GET['id'])->get();
        return response()->json($data, 200);
    }
    // public function importData(Request $request)
    // {
    //     $file = $request->file('file');
    //     Excel::import(new ImportIMBIndukPerum(), $file);
    //     return redirect()->route('IMBIndukPerum.index');
    // }

    public function importData(Request $request)
    {
        ini_set('max_execution_time', 0); // Unlimited execution time
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $file = $request->file('file');
        $imbInduk = null;
        $failures = [];
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $jenis_kegiatan_array = [];
        $baris = 1;
        $totalRows = (new FastExcel)->import($file)->count();
        $users = (new FastExcel)->import($file, function ($line) use ($jenisKegiatanList, $fungsiBangunanList, &$failures, &$baris, &$imbInduk, &$jenis_kegiatan_array, $totalRows) {
            if ($line["IMB Induk"] != null) {
                $rowDistrict = strtolower($line["Kecamatan"]);
                $rowSubdistrict = strtolower($line["Desa / Kelurahan"]);
                $districts = DB::table('master_district')
                    ->where(DB::raw('LOWER(name)'), $rowDistrict)
                    ->pluck('code')
                    ->toArray();
                if (empty($districts)) {
                    $failures[$baris] = [
                        'message' => 'Kecamatan ' . $line["Kecamatan"] . ' tidak ditemukan',
                        'baris' => $baris,
                    ];
                    $imbInduk = IMBIndukPerum::create([
                        'imb_induk' => $line["IMB Induk"],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                        'no_register' => $line["No. Register"],
                        'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                        'nama' => $line["Nama"],
                        'atas_nama' => $line["Atas Nama"],
                        'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                        'kecamatan_lama' => $line["Kecamatan"],
                        'kelurahan_lama' => $line["Desa / Kelurahan"],
                    ]);
                    return;
                }
                $village = DB::table('master_subdistrict')
                    ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
                    ->whereIn('district_code', $districts)
                    ->first();
                if (!$village) {
                    $failures[$baris] = [
                        'message' => 'Desa/Kelurahan ' . $line["Desa / Kelurahan"] . ' tidak ditemukan di kecamatan ' . $line["Kecamatan"],
                        'baris' => $baris,
                    ];
                    $imbInduk = IMBIndukPerum::create([
                        'imb_induk' => $line["IMB Induk"],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                        'no_register' => $line["No. Register"],
                        'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                        'nama' => $line["Nama"],
                        'atas_nama' => $line["Atas Nama"],
                        'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                        'kecamatan_lama' => $line["Kecamatan"],
                        'kelurahan_lama' => $line["Desa / Kelurahan"],
                    ]);
                    return;
                }
                $rowJenisKegiatan = strtolower($line["Jenis Kegiatan"]);
                $rowFungsiBangunan = strtolower($line["Fungsi Bangunan"]);
                $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
                if (!is_null($imbInduk)) {
                    $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
                    $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                        ->where('name_jeniskeg', $jenisKegiatanGabungan)
                        ->first();
                    if (!$jenisKegiatanRecord) {
                        $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                            'name_jeniskeg' => $jenisKegiatanGabungan
                        ]);
                    } else {
                        $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
                    }
                    DB::table('imb_induk_perum')
                        ->where('id', $imbInduk->id)
                        ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                    $jenis_kegiatan_array = [];
                }
                $imbInduk = IMBIndukPerum::create([
                    'imb_induk' => $line["IMB Induk"],
                    'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                    'no_register' => $line["No. Register"],
                    'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                    'nama' => $line["Nama"],
                    'atas_nama' => $line["Atas Nama"],
                    'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                    'kecamatan' => $village->district_code,
                    'desa_kelurahan' => $village->code,
                ]);
                if (!in_array($line["Jenis Kegiatan"], $jenis_kegiatan_array)) {
                    $jenis_kegiatan_array[] = $line["Jenis Kegiatan"];
                }
                IMBItem::create([
                    'induk_perum_id' => $imbInduk->id,
                    'jenis_kegiatan' => $jenis_kegiatan,
                    'fungsi_bangunan' => $fungsi_bangunan,
                    'type' => $line["Type"],
                    'luas_bangunan' => $line["Luas Bangunan"],
                    'jumlah_unit' => $line["Jumlah Unit"],
                    'keterangan' => $line["Keterangan"],
                    'scan_imb' => $line["Scan IMB"],
                ]);
                if ($baris === $totalRows) {
                    $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
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
                    DB::table('imb_induk_perum')
                        ->where('id', $imbInduk->id)
                        ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                }
            } else {
                if (!is_null($imbInduk)) {
                    $rowJenisKegiatan = strtolower($line["Jenis Kegiatan"]);
                    $rowFungsiBangunan = strtolower($line["Fungsi Bangunan"]);
                    $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                    $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
                    if (!in_array($line["Jenis Kegiatan"], $jenis_kegiatan_array)) {
                        $jenis_kegiatan_array[] = $line["Jenis Kegiatan"];
                    }
                    IMBItem::create([
                        'induk_perum_id' => $imbInduk->id,
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'type' => $line["Type"],
                        'luas_bangunan' => $line["Luas Bangunan"],
                        'jumlah_unit' => $line["Jumlah Unit"],
                        'keterangan' => $line["Keterangan"],
                    ]);
                    if ($baris === $totalRows) {
                        $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
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
                        DB::table('imb_induk_perum')
                            ->where('id', $imbInduk->id)
                            ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                    }
                }
            }
            $baris++;
        });
        if (count($failures) > 0) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Import data selesai, namun terdapat kesalahan. Silahkan download file log untuk melihat detail kesalahan.'])->with('failures', $failures);
        } else {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Import data berhasil']);
        }
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
                    $scanImbPath = $request->file("scan_imb_$i")->store('scans', 'public');
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
                    $scanImbPath = $request->file("scan_imb_$i")->store('scans', 'public');
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
                } else {
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
                    ]);
                }
            }

            $jenisKegiatanGabungan = implode(' / ', array_unique($jenisKegiatanArray));

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
            dd($th);
            return redirect()->back()->with(['status' => 'errors', 'message' => 'Anda memiliki kesalahan format']);
        }
    }

    public function downloadTemplate()
    {
        $template = public_path('template/imb_induk_perum_contoh.xlsx');
        return response()->download($template);
    }


    public function cariIMB(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('imb_induk_perum')
                // ->leftJoin('imb_pecahan', 'imb_induk_perum.id', '=', 'imb_pecahan.induk_id')
                // ->leftJoin('imb_perluasan', 'imb_induk_perum.id', '=', 'imb_perluasan.induk_id')
                ->join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select(
                    'imb_induk_perum.id',
                    'imb_induk_perum.nama',
                    'imb_induk_perum.atas_nama',
                    'imb_induk_perum.imb_induk as no_imb',
                    'imb_induk_perum.tgl_imb_induk as tgl_imb',
                    'imb_induk_perum.lokasi_perumahan as lokasi',
                    'master_district.name as kecamatan',
                    'master_subdistrict.name as kelurahan',
                    DB::raw("'induk' as jenis"),
                    // DB::raw("COALESCE(imb_pecahan.blok, imb_perluasan.blok) as blok"),
                    // DB::raw("COALESCE(imb_pecahan.no_blok, imb_perluasan.no_blok) as no_blok")
                );

            // Apply filters
            if ($request->no_imb) {
                $query->where('imb_induk_perum.imb_induk', 'like', '%' . $request->no_imb . '%');
            }
            if ($request->tgl_imb) {
                $query->whereDate('imb_induk_perum.tgl_imb_induk', $request->tgl_imb);
            }
            if ($request->nama) {
                $query->where('imb_induk_perum.nama', 'like', '%' . $request->nama . '%');
            }
            if ($request->atas_nama) {
                $query->where('imb_induk_perum.atas_nama', 'like', '%' . $request->atas_nama . '%');
            }
            if ($request->lokasi) {
                $query->where('imb_induk_perum.lokasi_perumahan', 'like', '%' . $request->lokasi . '%');
            }
            if ($request->kecamatan) {
                $query->where('master_district.name', 'like', '%' . $request->kecamatan . '%');
            }
            if ($request->desa_kelurahan) {
                $query->where('master_subdistrict.name', 'like', '%' . $request->desa_kelurahan . '%');
            }
            if ($request->blok) {
                $query->where(function($query) use ($request) {
                    $query->where('imb_pecahan.blok', 'like', '%' . $request->blok . '%')
                          ->orWhere('imb_perluasan.blok', 'like', '%' . $request->blok . '%');
                });
            }
            if ($request->no_blok) {
                $query->where(function($query) use ($request) {
                    $query->where('imb_pecahan.no_blok', 'like', '%' . $request->no_blok . '%')
                          ->orWhere('imb_perluasan.no_blok', 'like', '%' . $request->no_blok . '%');
                });
            }
            $data = $query->get();

            // Fetch IMB Pecahan
            $imbPecahan = DB::table('imb_pecahan')
                ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select(
                    'imb_pecahan.id',
                    'imb_pecahan.nama',
                    'imb_pecahan.atas_nama',
                    'imb_pecahan.imb_pecahan as no_imb',
                    'imb_pecahan.tgl_imb_pecahan as tgl_imb',
                    'imb_pecahan.lokasi_perumahan as lokasi',
                    'imb_pecahan.blok as blok',
                    'imb_pecahan.no_blok as no_blok',
                    'master_district.name as kecamatan',
                    'master_subdistrict.name as kelurahan',
                    DB::raw("'pecahan' as jenis")
                );

            // Apply filters
            if ($request->no_imb) {
                $imbPecahan->where('imb_pecahan.imb_pecahan', 'like', '%' . $request->no_imb . '%');
            }
            if ($request->tgl_imb) {
                $imbPecahan->whereDate('imb_pecahan.tgl_imb_pecahan', $request->tgl_imb);
            }
            if ($request->nama) {
                $imbPecahan->where('imb_pecahan.nama', 'like', '%' . $request->nama . '%');
            }
            if ($request->atas_nama) {
                $imbPecahan->where('imb_pecahan.atas_nama', 'like', '%' . $request->atas_nama . '%');
            }
            if ($request->lokasi) {
                $imbPecahan->where('imb_pecahan.lokasi_perumahan', 'like', '%' . $request->lokasi . '%');
            }
            if ($request->blok) {
                $imbPecahan->where('imb_pecahan.blok', 'like', '%' . $request->blok . '%');
            }
            if ($request->no_blok) {
                $imbPecahan->where('imb_pecahan.no_blok', 'like', '%' . $request->no_blok . '%');
            }
            if ($request->kecamatan) {
                $imbPecahan->where('master_district.name', 'like', '%' . $request->kecamatan . '%');
            }
            if ($request->desa_kelurahan) {
                $imbPecahan->where('master_subdistrict.name', 'like', '%' . $request->desa_kelurahan . '%');
            }

            $data = $data->merge($imbPecahan->get());

            // Fetch IMB Perluasan
            $imbPerluasan = DB::table('imb_perluasan')
                ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select(
                    'imb_perluasan.id',
                    'imb_perluasan.nama',
                    'imb_perluasan.atas_nama',
                    'imb_perluasan.imb_perluasan as no_imb',
                    'imb_perluasan.tgl_imb_perluasan as tgl_imb',
                    'imb_perluasan.lokasi_perumahan as lokasi',
                    'imb_perluasan.blok as blok',
                    'imb_perluasan.no_blok as no_blok',
                    'master_district.name as kecamatan',
                    'master_subdistrict.name as kelurahan',
                    DB::raw("'perluasan' as jenis")
                );

            // Apply filters
            if ($request->no_imb) {
                $imbPerluasan->where('imb_perluasan.imb_perluasan', 'like', '%' . $request->no_imb . '%');
            }
            if ($request->tgl_imb) {
                $imbPerluasan->whereDate('imb_perluasan.tgl_imb_perluasan', $request->tgl_imb);
            }
            if ($request->nama) {
                $imbPerluasan->where('imb_perluasan.nama', 'like', '%' . $request->nama . '%');
            }
            if ($request->atas_nama) {
                $imbPerluasan->where('imb_perluasan.atas_nama', 'like', '%' . $request->atas_nama . '%');
            }
            if ($request->lokasi) {
                $imbPerluasan->where('imb_perluasan.lokasi_perumahan', 'like', '%' . $request->lokasi . '%');
            }
            if ($request->blok) {
                $imbPerluasan->where('imb_perluasan.blok', 'like', '%' . $request->blok . '%');
            }
            if ($request->no_blok) {
                $imbPerluasan->where('imb_perluasan.no_blok', 'like', '%' . $request->no_blok . '%');
            }
            if ($request->kecamatan) {
                $imbPerluasan->where('master_district.name', 'like', '%' . $request->kecamatan . '%');
            }
            if ($request->desa_kelurahan) {
                $imbPerluasan->where('master_subdistrict.name', 'like', '%' . $request->desa_kelurahan . '%');
            }

            $data = $data->merge($imbPerluasan->get());

            // Fetch IMB Non Perum
            $imbNonPerum = DB::table('imb_induk_non_perum')
                ->join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select(
                    'imb_induk_non_perum.id',
                    'imb_induk_non_perum.nama',
                    'imb_induk_non_perum.atas_nama',
                    'imb_induk_non_perum.imb_induk as no_imb',
                    'imb_induk_non_perum.tgl_imb_induk as tgl_imb',
                    'imb_induk_non_perum.lokasi_perumahan as lokasi',
                    'master_district.name as kecamatan',
                    'master_subdistrict.name as kelurahan',
                    DB::raw("'non_perum' as jenis")
                );

            // Apply filters
            if ($request->no_imb) {
                $imbNonPerum->where('imb_induk_non_perum.imb_induk', 'like', '%' . $request->no_imb . '%');
            }
            if ($request->tgl_imb) {
                $imbNonPerum->whereDate('imb_induk_non_perum.tgl_imb_induk', $request->tgl_imb);
            }
            if ($request->nama) {
                $imbNonPerum->where('imb_induk_non_perum.nama', 'like', '%' . $request->nama . '%');
            }
            if ($request->atas_nama) {
                $imbNonPerum->where('imb_induk_non_perum.atas_nama', 'like', '%' . $request->atas_nama . '%');
            }
            if ($request->lokasi) {
                $imbNonPerum->where('imb_induk_non_perum.lokasi_perumahan', 'like', '%' . $request->lokasi . '%');
            }
            if ($request->kecamatan) {
                $imbNonPerum->where('master_district.name', 'like', '%' . $request->kecamatan . '%');
            }
            if ($request->desa_kelurahan) {
                $imbNonPerum->where('master_subdistrict.name', 'like', '%' . $request->desa_kelurahan . '%');
            }

            $data = $data->merge($imbNonPerum->get());

            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-primary btn-sm view-details" data-id="' . $row->id . '" data-type="' . $row->jenis . '"> <i class="fas fa-eye"></i></button>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    // public function cariIMB(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = collect();

    //         // Fetch IMB Induk
    //         $imbInduk = DB::table('imb_induk_perum')
    //             ->join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //             ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
    //             ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
    //             ->select(
    //                 'imb_induk_perum.id',
    //                 'imb_induk_perum.nama',
    //                 'imb_induk_perum.atas_nama',
    //                 'imb_induk_perum.imb_induk as no_imb',
    //                 'imb_induk_perum.tgl_imb_induk as tgl_imb',
    //                 'imb_induk_perum.lokasi_perumahan as lokasi',
    //                 'master_district.name as kecamatan',
    //                 'master_subdistrict.name as kelurahan',
    //                 DB::raw("'induk' as jenis")
    //             )
    //             ->get();
    //         $data = $data->merge($imbInduk);

    //         // Fetch IMB Pecahan
    //         $imbPecahan = DB::table('imb_pecahan')
    //             ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
    //             ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
    //             ->select(
    //                 'imb_pecahan.id',
    //                 'imb_pecahan.nama',
    //                 'imb_pecahan.atas_nama',
    //                 'imb_pecahan.imb_pecahan as no_imb',
    //                 'imb_pecahan.tgl_imb_pecahan as tgl_imb',
    //                 'imb_pecahan.lokasi_perumahan as lokasi',
    //                 'master_district.name as kecamatan',
    //                 'master_subdistrict.name as kelurahan',
    //                 DB::raw("'pecahan' as jenis")
    //             )
    //             ->get();
    //         $data = $data->merge($imbPecahan);

    //         // Fetch IMB Perluasan
    //         $imbPerluasan = DB::table('imb_perluasan')
    //             ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
    //             ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
    //             ->select(
    //                 'imb_perluasan.id',
    //                 'imb_perluasan.nama',
    //                 'imb_perluasan.atas_nama',
    //                 'imb_perluasan.imb_perluasan as no_imb',
    //                 'imb_perluasan.tgl_imb_perluasan as tgl_imb',
    //                 'imb_perluasan.lokasi_perumahan as lokasi',
    //                 'master_district.name as kecamatan',
    //                 'master_subdistrict.name as kelurahan',
    //                 DB::raw("'perluasan' as jenis")
    //             )
    //             ->get();
    //         $data = $data->merge($imbPerluasan);

    //         // Fetch IMB Non Perum
    //         $imbNonPerum = DB::table('imb_induk_non_perum')
    //             ->join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //             ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
    //             ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
    //             ->select(
    //                 'imb_induk_non_perum.id',
    //                 'imb_induk_non_perum.nama',
    //                 'imb_induk_non_perum.atas_nama',
    //                 'imb_induk_non_perum.imb_induk as no_imb',
    //                 'imb_induk_non_perum.tgl_imb_induk as tgl_imb',
    //                 'imb_induk_non_perum.lokasi_perumahan as lokasi',
    //                 'master_district.name as kecamatan',
    //                 'master_subdistrict.name as kelurahan',
    //                 DB::raw("'non_perum' as jenis")
    //             )
    //             ->get();
    //         $data = $data->merge($imbNonPerum);

    //         return Datatables::of($data)
    //             ->addColumn('action', function ($row) {
    //                 return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '" data-type="' . $row->jenis . '">Detail</button>';
    //             })
    //             ->rawColumns(['action'])
    //             ->addIndexColumn()
    //             ->make(true);
    //     }
    // }

// public function cariIMB(Request $request)
// {
//     if ($request->ajax()) {
//         $filters = [
//             'no_imb' => $request->input('no_imb'),
//             'tgl_imb' => $request->input('tgl_imb'),
//             'nama' => $request->input('nama'),
//             'atas_nama' => $request->input('atas_nama'),
//             'lokasi' => $request->input('lokasi'),
//             'blok' => $request->input('blok'),
//             'no_blok' => $request->input('no_blok'),
//             'kecamatan' => $request->input('kecamatan'),
//             'desa_kelurahan' => $request->input('desa_kelurahan'),
//         ];

//         $data = collect();

//         // Fetch IMB Induk
//         $imbInduk = DB::table('imb_induk_perum')
//             ->join('app_md_jeniskeg', 'imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
//             ->join('master_district', 'imb_induk_perum.kecamatan', '=', 'master_district.code')
//             ->join('master_subdistrict', 'imb_induk_perum.desa_kelurahan', '=', 'master_subdistrict.code')
//             ->select(
//                 'imb_induk_perum.id',
//                 'imb_induk_perum.nama',
//                 'imb_induk_perum.atas_nama',
//                 'imb_induk_perum.imb_induk as no_imb',
//                 'imb_induk_perum.tgl_imb_induk as tgl_imb',
//                 'imb_induk_perum.lokasi_perumahan as lokasi',
//                 'master_district.name as kecamatan',
//                 'master_subdistrict.name as kelurahan',
//                 DB::raw("'induk' as jenis")
//             );
//             $filteredInduk = $this->applyFilters(
//                 $imbInduk,
//                 array_diff_key($filters, ['blok' => '', 'no_blok' => ''])
//             );
//             $data = $data->merge($filteredInduk->get());

//         // Fetch IMB Pecahan
//         $imbPecahan = DB::table('imb_pecahan')
//             ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
//             ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
//             ->select(
//                 'imb_pecahan.id',
//                 'imb_pecahan.nama',
//                 'imb_pecahan.atas_nama',
//                 'imb_pecahan.imb_pecahan as no_imb',
//                 'imb_pecahan.tgl_imb_pecahan as tgl_imb',
//                 'imb_pecahan.lokasi_perumahan as lokasi',
//                 'imb_pecahan.blok',
//                 'imb_pecahan.no_blok',
//                 'master_district.name as kecamatan',
//                 'master_subdistrict.name as kelurahan',
//                 DB::raw("'pecahan' as jenis")
//             );
//         $imbPecahan = $this->applyFilters($imbPecahan, $filters);
//         $data = $data->merge($imbPecahan->get());

//         // Fetch IMB Perluasan
//         $imbPerluasan = DB::table('imb_perluasan')
//             ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
//             ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
//             ->select(
//                 'imb_perluasan.id',
//                 'imb_perluasan.nama',
//                 'imb_perluasan.atas_nama',
//                 'imb_perluasan.imb_perluasan as no_imb',
//                 'imb_perluasan.tgl_imb_perluasan as tgl_imb',
//                 'imb_perluasan.lokasi_perumahan as lokasi',
//                 'imb_perluasan.blok',
//                 'imb_perluasan.no_blok',
//                 'master_district.name as kecamatan',
//                 'master_subdistrict.name as kelurahan',
//                 DB::raw("'perluasan' as jenis")
//             );
//         $imbPerluasan = $this->applyFilters($imbPerluasan, $filters);
//         $data = $data->merge($imbPerluasan->get());

//         // Fetch IMB Non Perum
//         $imbNonPerum = DB::table('imb_induk_non_perum')
//             ->join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
//             ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
//             ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
//             ->select(
//                 'imb_induk_non_perum.id',
//                 'imb_induk_non_perum.nama',
//                 'imb_induk_non_perum.atas_nama',
//                 'imb_induk_non_perum.imb_induk as no_imb',
//                 'imb_induk_non_perum.tgl_imb_induk as tgl_imb',
//                 'imb_induk_non_perum.lokasi_perumahan as lokasi',
//                 'imb_induk_non_perum.blok',
//                 'imb_induk_non_perum.no_blok',
//                 'master_district.name as kecamatan',
//                 'master_subdistrict.name as kelurahan',
//                 DB::raw("'non_perum' as jenis")
//             );
//             $filteredIndukNonPerum = $this->applyFilters(
//                 $imbNonPerum,
//                 array_diff_key($filters, ['blok' => '', 'no_blok' => ''])
//             );
//             $data = $data->merge($filteredIndukNonPerum->get());

//         return Datatables::of($data)
//             ->addColumn('action', function ($row) {
//                 return '<button class="btn btn-info btn-sm view-details" data-id="' . $row->id . '" data-type="' . $row->jenis . '">Detail</button>';
//             })
//             ->rawColumns(['action'])
//             ->addIndexColumn()
//             ->make(true);
//     }
// }


// /**
//  * Helper function to apply filters to query
//  */
// private function applyFilters($query, $filters)
// {
//     foreach ($filters as $column => $value) {
//         if ($value) {
//             // Cek apakah kolom tersedia di tabel saat ini
//             $query->where(function ($q) use ($column, $value) {
//                 $q->where($column, 'LIKE', '%' . $value . '%')
//                   ->orWhere(DB::raw("LOWER({$column})"), 'LIKE', '%' . strtolower($value) . '%');
//             });
//         }
//     }
//     return $query;
// }



    // public function getIMBDetail($id, $type)
    // {
    //     switch ($type) {
    //         case 'induk':
    //             $imb = DB::table('imb_induk_perum')
    //                 ->select(
    //                     'imb_induk_perum.imb_induk as nomor_imb',
    //                     'imb_induk_perum.no_register',
    //                     'imb_induk_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_perum.id', $id)
    //                 ->first();

    //                 $items = DB::table('item_imb_induk_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')

    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         case 'pecahan':
    //             $imb = DB::table('imb_pecahan')
    //                 ->join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_pecahan.imb_pecahan as nomor_imb',
    //                     'imb_pecahan.no_register',
    //                     'imb_pecahan.tgl_register',
    //                     'imb_pecahan.blok',
    //                     'imb_pecahan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Pecahan' as jenis_imb")
    //                 )
    //                 ->where('imb_pecahan.id', $id)
    //                 ->first();

    //                 $item = [];

    //             break;

    //         case 'perluasan':
    //             $imb = DB::table('imb_perluasan')
    //                 ->join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_perluasan.imb_perluasan as nomor_imb',
    //                     'imb_perluasan.no_register',
    //                     'imb_perluasan.tgl_register',
    //                     'imb_perluasan.blok',
    //                     'imb_perluasan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Perluasan' as jenis_imb")
    //                 )
    //                 ->where('imb_perluasan.id', $id)
    //                 ->first();

    //                 $item = [];
    //             break;

    //         case 'non_perum':
    //              $imb = DB::table('imb_induk_non_perum')
    //                 ->select(
    //                     'imb_induk_non_perum.imb_induk as nomor_imb',
    //                     'imb_induk_non_perum.no_register',
    //                     'imb_induk_non_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_non_perum.id', $id)
    //                 ->first();

    //                 $items = DB::table('item_imb_induk_non_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')

    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         default:
    //             return response()->json(['error' => 'Invalid type'], 400);
    //     }
    //     return response()->json([
    //         'imb' => $imb,
    //         'items' => $items
    //     ]);
    // }
    // public function getIMBDetail($id, $type)
    // {
    //     $items = []; // Inisialisasi variabel $items
    //     \Log::info('Request Data:', ['id' => $id, 'type' => $type]);
    //     switch ($type) {
    //         case 'induk':
    //             $imb = DB::table('imb_induk_perum')
    //                 ->select(
    //                     'imb_induk_perum.imb_induk as nomor_imb',
    //                     'imb_induk_perum.no_register',
    //                     'imb_induk_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_perum.id', $id)
    //                 ->first();

    //             $items = DB::table('item_imb_induk_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         case 'pecahan':
    //             $imb = DB::table('imb_pecahan')
    //                 ->join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_pecahan.imb_pecahan as nomor_imb',
    //                     'imb_pecahan.no_register',
    //                     'imb_pecahan.tgl_register',
    //                     'imb_pecahan.blok',
    //                     'imb_pecahan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Pecahan' as jenis_imb")
    //                 )
    //                 ->where('imb_pecahan.id', $id)
    //                 ->first();

    //             // Tidak ada items untuk pecahan
    //             break;

    //         case 'perluasan':
    //             $imb = DB::table('imb_perluasan')
    //                 ->join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_perluasan.imb_perluasan as nomor_imb',
    //                     'imb_perluasan.no_register',
    //                     'imb_perluasan.tgl_register',
    //                     'imb_perluasan.blok',
    //                     'imb_perluasan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Perluasan' as jenis_imb")
    //                 )
    //                 ->where('imb_perluasan.id', $id)
    //                 ->first();

    //             // Tidak ada items untuk perluasan
    //             break;

    //         case 'non_perum':
    //             $imb = DB::table('imb_induk_non_perum')
    //                 ->select(
    //                     'imb_induk_non_perum.imb_induk as nomor_imb',
    //                     'imb_induk_non_perum.no_register',
    //                     'imb_induk_non_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_non_perum.id', $id)
    //                 ->first();

    //             $items = DB::table('item_imb_induk_non_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         default:
    //             return response()->json(['error' => 'Invalid type'], 400);
    //     }

    //     return response()->json([
    //         'imb' => $imb,
    //         'items' => $items
    //     ]);
    // }

    // public function getIMBDetail($id, $type)
    // {
    //     $items = []; // Inisialisasi variabel $items

    //     switch ($type) {
    //         case 'induk':
    //             $imb = DB::table('imb_induk_perum')
    //                 ->select(
    //                     'imb_induk_perum.imb_induk as nomor_imb',
    //                     'imb_induk_perum.no_register',
    //                     'imb_induk_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_perum.id', $id)
    //                 ->first();

    //             $items = DB::table('item_imb_induk_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         case 'pecahan':
    //             $imb = DB::table('imb_pecahan')
    //                 ->join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_pecahan.imb_pecahan as nomor_imb',
    //                     'imb_pecahan.no_register',
    //                     'imb_pecahan.tgl_register',
    //                     'imb_pecahan.blok',
    //                     'imb_pecahan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Pecahan' as jenis_imb")
    //                 )
    //                 ->where('imb_pecahan.id', $id)
    //                 ->first();

    //             // Tidak ada items untuk pecahan
    //             break;

    //         case 'perluasan':
    //             $imb = DB::table('imb_perluasan')
    //                 ->join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select(
    //                     'imb_perluasan.imb_perluasan as nomor_imb',
    //                     'imb_perluasan.no_register',
    //                     'imb_perluasan.tgl_register',
    //                     'imb_perluasan.blok',
    //                     'imb_perluasan.no_blok',
    //                     'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
    //                     DB::raw("'Perluasan' as jenis_imb")
    //                 )
    //                 ->where('imb_perluasan.id', $id)
    //                 ->first();

    //             // Tidak ada items untuk perluasan
    //             break;

    //         case 'non_perum':
    //             $imb = DB::table('imb_induk_non_perum')
    //                 ->select(
    //                     'imb_induk_non_perum.imb_induk as nomor_imb',
    //                     'imb_induk_non_perum.no_register',
    //                     'imb_induk_non_perum.tgl_register',
    //                     DB::raw("'Induk' as jenis_imb")
    //                 )
    //                 ->where('imb_induk_non_perum.id', $id)
    //                 ->first();

    //             $items = DB::table('item_imb_induk_non_perum')
    //                 ->where('induk_perum_id', $id)
    //                 ->join('app_md_jeniskeg', 'item_imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
    //                 ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
    //                 ->get();
    //             break;

    //         default:
    //             return response()->json(['error' => 'Invalid type'], 400);
    //     }

    //     return response()->json([
    //         'imb' => $imb,
    //         'items' => $items
    //     ]);
    // }

    public function getIMBDetail($id, $type)
    {
        $items = []; // Inisialisasi variabel $items

        switch ($type) {
            case 'induk':
                $imb = DB::table('imb_induk_perum')
                    ->select(
                        'imb_induk_perum.imb_induk as nomor_imb',
                        'imb_induk_perum.no_register',
                        'imb_induk_perum.tgl_register',
                        DB::raw("'Induk' as jenis_imb")
                    )
                    ->where('imb_induk_perum.id', $id)
                    ->first();

                $items = DB::table('item_imb_induk_perum')
                    ->where('induk_perum_id', $id)
                    ->join('app_md_jeniskeg', 'item_imb_induk_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                    ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_perum.fungsi_bangunan', 'item_imb_induk_perum.luas_bangunan', 'item_imb_induk_perum.jumlah_unit', 'item_imb_induk_perum.keterangan', 'item_imb_induk_perum.scan_imb')
                    ->get();
                break;

            case 'pecahan':
                $imb = DB::table('imb_pecahan')
                    ->join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                    ->select(
                        'imb_pecahan.imb_pecahan as nomor_imb',
                        'imb_pecahan.no_register',
                        'imb_pecahan.tgl_register',
                        'imb_pecahan.blok',
                        'imb_pecahan.no_blok',
                        'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
                        DB::raw("'Pecahan' as jenis_imb")
                    )
                    ->where('imb_pecahan.id', $id)
                    ->first();

                // Tidak ada items untuk pecahan
                break;

            case 'perluasan':
                $imb = DB::table('imb_perluasan')
                    ->join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                    ->select(
                        'imb_perluasan.imb_perluasan as nomor_imb',
                        'imb_perluasan.no_register',
                        'imb_perluasan.tgl_register',
                        'imb_perluasan.blok',
                        'imb_perluasan.no_blok',
                        'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
                        DB::raw("'Perluasan' as jenis_imb")
                    )
                    ->where('imb_perluasan.id', $id)
                    ->first();

                // Tidak ada items untuk perluasan
                break;

            case 'non_perum':
                $imb = DB::table('imb_induk_non_perum')
                    ->select(
                        'imb_induk_non_perum.imb_induk as nomor_imb',
                        'imb_induk_non_perum.no_register',
                        'imb_induk_non_perum.tgl_register',
                        DB::raw("'Induk' as jenis_imb")
                    )
                    ->where('imb_induk_non_perum.id', $id)
                    ->first();

                $items = DB::table('item_imb_induk_non_perum')
                    ->where('induk_perum_id', $id)
                    ->join('app_md_jeniskeg', 'item_imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                    ->select('app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'item_imb_induk_non_perum.fungsi_bangunan', 'item_imb_induk_non_perum.luas_bangunan', 'item_imb_induk_non_perum.jumlah_unit', 'item_imb_induk_non_perum.keterangan', 'item_imb_induk_non_perum.scan_imb')
                    ->get();
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        return response()->json([
            'imb' => $imb,
            'items' => $items
        ]);
    }
}
