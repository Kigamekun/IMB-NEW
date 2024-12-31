<?php

namespace App\Http\Controllers;

use App\Models\IMBItemNon;
use Illuminate\Http\Request;
use App\Imports\ImportIMBIndukNonPerum;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IMBIndukNonPerum;
use \Yajra\DataTables\DataTables;
use App\Exports\IMBIndukNonPerumExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
class IMBIndukNonPerumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_regency', 'imb_induk_non_perum.kabupaten', '=', 'master_regency.code')
                ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
                ->join('master_jenis_non_perum', 'imb_induk_non_perum.jenis', '=', 'master_jenis_non_perum.id')
                ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_regency.name as kabupaten','master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_subdistrict.name as kelurahan', 'master_jenis_non_perum.name as jenis');
            // $query = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            //     ->join('master_regency', 'imb_induk_non_perum.kabupaten', '=', 'master_regency.code')
            //     ->join('master_district',  function($join) {
            //         $join->on('imb_induk_non_perum.kecamatan', '=', 'master_district.code')
            //              ->whereColumn('master_district.regency_code', 'master_regency.code');
            //     })
            //     ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
            //     ->join('master_jenis_non_perum', 'imb_induk_non_perum.jenis', '=', 'master_jenis_non_perum.id')
            //     ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_regency.name as kabupaten','master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_jenis_non_perum.name as jenis');

               // Filter berdasarkan kabupaten
            if ($request->has('kabupaten') && $request->kabupaten) {
                $query->where('imb_induk_non_perum.kabupaten', $request->kabupaten);
            }

            // Filter berdasarkan kecamatan
            if ($request->has('kecamatan') && $request->kecamatan) {
                $query->where('imb_induk_non_perum.kecamatan', $request->kecamatan);
            }

            // Filter berdasarkan kelurahan
            if ($request->has('kelurahan') && $request->kelurahan) {
                $query->where('imb_induk_non_perum.desa_kelurahan', $request->kelurahan);
            }

            $query = $query->orderBy('imb_induk_non_perum.created_at', 'desc')->get();


            return Datatables::of($query)
                // ->addColumn('jenis', function ($row) {
                //     $master = DB::table('master_jenis_non_perum')->where('id', $row->jenis)->first();
                //     return '<span class="badge badge-primary">' . $master->name . '</span>';
                // })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex" style="gap:10px;display:flex;">
                            <a href="' . route('IMBIndukNonPerum.edit', $row->id) . '" class="edit btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                            <form action="' . route('IMBIndukNonPerum.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)"><i class="fa fa-trash"></i></button>
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
    public function items(Request $request)
    {
        $data = IMBItemNon::join('app_md_jeniskeg', 'item_imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('app_md_fungsibang', 'item_imb_induk_non_perum.fungsi_bangunan', '=', 'app_md_fungsibang.id_fungsibang')
            ->select('item_imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'app_md_fungsibang.name_fungsibang as fungsi_bangunan')
            ->where('induk_perum_id', $_GET['id'])->get();
        return response()->json($data, 200);
    }
    // public function importData(Request $request)
    // {
    //     $file = $request->file('file');
    //     Excel::import(new ImportIMBIndukNonPerum(), $file);
    //     return redirect()->route('IMBIndukNonPerum.index');
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
        $jenisIMBList = DB::table('master_jenis_non_perum')->pluck('id', 'name')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $jenis_kegiatan_array = [];
        $baris = 1;
        $totalRows = (new FastExcel)->import($file)->count();
        $users = (new FastExcel)->import($file, function ($line) use ($jenisKegiatanList, $fungsiBangunanList, &$failures, &$baris, &$imbInduk, &$jenis_kegiatan_array, $totalRows, $jenisIMBList) {
            if ($line["IMB Induk"] != null) {

                $tanda = 1;
                $regency = 0;

                $rowRegency = strtolower($line["Kabupaten / Kota"]);
                $rowDistrict = strtolower($line["Kecamatan"]);
                $rowSubdistrict = strtolower($line["Desa / Kelurahan"]);
                $jenis = strtolower($line["Jenis"]);
                $jenis = $jenisIMBList[$jenis] ?? null;
                $regency = DB::table('master_regency')
                ->where(DB::raw('LOWER(name)'), $rowRegency)
                ->pluck('code')
                ->first();
                if (!$regency) {
                    $failures[] = [
                        'message' => 'Kabupaten/Kota ' . $line["Kabupaten / Kota"] . ' tidak ditemukan',
                        'baris' => $baris,
                    ];
                    $imbInduk = IMBIndukNonPerum::create([
                        'jenis' => $jenis,
                        'imb_induk' => $line["IMB Induk"],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                        'no_register' => $line["No. Register"],
                        'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                        'nama' => $line["Nama"],
                        'atas_nama' => $line["Atas Nama"],
                        'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                        'kabupaten_lama' => $line["Kabupaten / Kota"],
                        'kecamatan_lama' => $line["Kecamatan"],
                        'kelurahan_lama' => $line["Desa / Kelurahan"],
                    ]);
                    $tanda = 0;
                    // return;
                }
                $districts = DB::table('master_district')
                    ->where(DB::raw('LOWER(name)'), $rowDistrict)
                    ->where("regency_code", $regency)
                    ->pluck('code')
                    ->toArray();
                if (empty($districts) && $tanda != 0) {
                    $failures[$baris] = [
                        'message' => 'Kecamatan ' . $line["Kecamatan"] . ' tidak ditemukan',
                        'baris' => $baris,
                    ];
                    $imbInduk = IMBIndukNonPerum::create([
                        'jenis' => $jenis,
                        'imb_induk' => $line["IMB Induk"],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                        'no_register' => $line["No. Register"],
                        'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                        'nama' => $line["Nama"],
                        'atas_nama' => $line["Atas Nama"],
                        'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                        'kabupaten_lama' => $line["Kabupaten / Kota"],
                        'kecamatan_lama' => $line["Kecamatan"],
                        'kelurahan_lama' => $line["Desa / Kelurahan"],
                    ]);
                    $tanda = 0;
                    // return;
                }
                $village = DB::table('master_subdistrict')
                    ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
                    ->whereIn('district_code', $districts)
                    ->first();
                if (!$village && $tanda != 0) {
                    $failures[$baris] = [
                        'message' => 'Desa/Kelurahan ' . $line["Desa / Kelurahan"] . ' tidak ditemukan di kecamatan ' . $line["Kecamatan"],
                        'baris' => $baris,
                    ];
                    $imbInduk = IMBIndukNonPerum::create([
                        'jenis' => $jenis,
                        'imb_induk' => $line["IMB Induk"],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                        'no_register' => $line["No. Register"],
                        'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                        'nama' => $line["Nama"],
                        'atas_nama' => $line["Atas Nama"],
                        'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                        'kabupaten_lama' => $line["Kabupaten / Kota"],
                        'kecamatan_lama' => $line["Kecamatan"],
                        'kelurahan_lama' => $line["Desa / Kelurahan"],
                    ]);
                    $tanda = 0;
                    // return;
                }

                if ($tanda == 0) {
                    $rowJenisKegiatan = strtolower($line["Jenis Kegiatan"]);
                    $rowFungsiBangunan = strtolower($line["Fungsi Bangunan"]);
                    $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                    $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;

                    if (!in_array($line["Jenis Kegiatan"], $jenis_kegiatan_array)) {
                        $jenis_kegiatan_array[] = $line["Jenis Kegiatan"];
                    }
                    IMBItemNon::create([
                        'induk_perum_id' => $imbInduk->id,
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'type' => $line["Type"],
                        'luas_bangunan' => $line["Luas Bangunan"],
                        'jumlah_unit' => $line["Jumlah Unit"],
                        'keterangan' => $line["Keterangan"],
                        'scan_imb' => $line["Scan IMB"],
                    ]);

                } else {
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
                    DB::table('imb_induk_non_perum')
                        ->where('id', $imbInduk->id)
                        ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                    $jenis_kegiatan_array = [];
                }
               // dd($regency);
               // dd($line["Tgl. Register"]);
                $imbInduk = IMBIndukNonPerum::create([
                    'jenis' => $jenis,
                    'imb_induk' => $line["IMB Induk"],
                    'tgl_imb_induk' => date('Y-m-d', strtotime($line["Tgl. IMB Induk"])),
                    'no_register' => $line["No. Register"],
                    'tgl_register' => $line["Tgl. Register"] != '' ? date('Y-m-d', strtotime($line["Tgl. Register"])) : null,
                    'nama' => $line["Nama"],
                    'atas_nama' => $line["Atas Nama"],
                    'lokasi_perumahan' => $line["Lokasi / Perumahan"],
                    'kabupaten' => $regency,
                    'kecamatan' => $village->district_code,
                    'desa_kelurahan' => $village->code,
                ]);
                if (!in_array($line["Jenis Kegiatan"], $jenis_kegiatan_array)) {
                    $jenis_kegiatan_array[] = $line["Jenis Kegiatan"];
                }
                IMBItemNon::create([
                    'induk_perum_id' => $imbInduk->id,
                    'jenis_kegiatan' => $jenis_kegiatan,
                    'fungsi_bangunan' => $fungsi_bangunan,
                    'type' => $line["Type"],
                    'luas_bangunan' => $line["Luas Bangunan"],
                    'jumlah_unit' => $line["Jumlah Unit"],
                    'keterangan' => $line["Keterangan"],
                    'scan_imb' => $line["Scan IMB"],
                ]);
            }
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
                    DB::table('imb_induk_non_perum')
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
                    IMBItemNon::create([
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
                        DB::table('imb_induk_non_perum')
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

            $indukPerumId = DB::table('imb_induk_non_perum')->insertGetId([
                'jenis' => $request->input('jenis'),
                'imb_induk' => $request->input('imb_induk'),
                'tgl_imb_induk' => $request->input('tgl_imb_induk'),
                'no_register' => $request->input('no_register'),
                'tgl_register' => $request->input('tgl_register'),
                'nama' => $request->input('nama'),
                'atas_nama' => $request->input('atas_nama'),
                'lokasi_perumahan' => $request->input('lokasi_perumahan'),
                'kabupaten' => $request->input('kabupaten'),
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

                DB::table('item_imb_induk_non_perum')->insert([
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

            // Update imb_induk_non_perum dengan jenis_kegiatan gabungan ID
            DB::table('imb_induk_non_perum')
                ->where('id', $indukPerumId)
                ->update(['jenis_kegiatan' => $jenisKegiatanId]);

            DB::commit();

            // dd($request->all());
            return redirect()->route('IMBIndukNonPerum.index')->with(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to save data: ' . $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        $data = IMBIndukNonPerum::join('app_md_jeniskeg', 'imb_induk_non_perum.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
            ->join('master_regency', 'imb_induk_non_perum.kabupaten', '=', 'master_regency.code')
            ->join('master_district', 'imb_induk_non_perum.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_induk_non_perum.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_induk_non_perum.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_regency.name as kabupaten' , 'master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_induk_non_perum.id', $id)->first();
        $item = IMBItemNon::where('induk_perum_id', $id)->get();
        return view('IMBIndukNonPerum.edit', compact('data', 'item'));
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $jenisKegiatanArray = [];

            // Update imb_induk_non_perum
            DB::table('imb_induk_non_perum')->where('id', $id)->update([
                'jenis' => $request->input('jenis'),
                'imb_induk' => $request->input('imb_induk'),
                'tgl_imb_induk' => $request->input('tgl_imb_induk'),
                'no_register' => $request->input('no_register'),
                'tgl_register' => $request->input('tgl_register'),
                'nama' => $request->input('nama'),
                'atas_nama' => $request->input('atas_nama'),
                'lokasi_perumahan' => $request->input('lokasi_perumahan'),
                'kabupaten' => $request->input('kabupaten'),
                'kecamatan' => $request->input('kecamatan'),
                'desa_kelurahan' => $request->input('kelurahan'),
            ]);

            // Hapus item lama dan tambahkan yang baru
            DB::table('item_imb_induk_non_perum')->where('induk_perum_id', $id)->delete();

            $entryCount = $request->input('entryCount');
            for ($i = 0; $i < $entryCount; $i++) {
                $scanImbPath = null;
                if ($request->hasFile("scan_imb_$i")) {
                    $scanImbPath = $request->file("scan_imb_$i")->store('scans', 'public');
                    $jenisKegiatan = $request->input("jenis_kegiatan_$i");
                    $jenisKegiatanArray[] = $jenisKegiatan;

                    $kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $jenisKegiatan)->first()->id_jeniskeg;

                    DB::table('item_imb_induk_non_perum')->insert([
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

                    DB::table('item_imb_induk_non_perum')->insert([
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

            // Update imb_induk_non_perum dengan jenis_kegiatan gabungan ID
            DB::table('imb_induk_non_perum')
                ->where('id', $id)
                ->update(['jenis_kegiatan' => $jenisKegiatanId]);

            DB::commit();
            return redirect()->route('IMBIndukNonPerum.index')->with(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        IMBIndukNonPerum::destroy($id);
        IMBitem::where('induk_perum_id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function exportData()
    {
        try {
            return Excel::download(new IMBIndukNonPerumExport, 'IMBIndukNonPerum' . Carbon::now()->timestamp . '.xlsx');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with(['status' => 'errors', 'message' => 'Anda memiliki kesalahan format']);
        }
    }

    public function downloadTemplate()
    {
        $template = public_path('template/imb_induk_non_perum_contoh.xlsx');
        return response()->download($template);
    }
}
