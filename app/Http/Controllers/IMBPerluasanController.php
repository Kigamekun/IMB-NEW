<?php

namespace App\Http\Controllers;

use App\Imports\ImportIMBPerluasan;
use App\Models\IMBPerluasan;
use Illuminate\Http\Request;
use \Yajra\DataTables\DataTables;
use App\Exports\IMBPerluasanExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class IMBPerluasanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = IMBPerluasan::join('app_md_jeniskeg', 'imb_perluasan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_regency', 'imb_perluasan.kabupaten', '=', 'master_regency.code')
                ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan','master_regency.name as kabupaten','master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code');


                if ($request->has('kabupaten') && $request->kabupaten) {
                    $query->where('imb_perluasan.kabupaten', $request->kabupaten);
                }

                // Filter berdasarkan kecamatan
                if ($request->has('kecamatan') && $request->kecamatan) {
                    $query->where('imb_perluasan.kecamatan', $request->kecamatan);
                }

                // Filter berdasarkan kelurahan
                if ($request->has('kelurahan') && $request->kelurahan) {
                    $query->where('imb_perluasan.desa_kelurahan', $request->kelurahan);
                }

                $query = $query->orderBy('imb_perluasan.created_at', 'desc')->get();


            return Datatables::of($query)
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
                ->editColumn('scan_imb', function ($row) {
                    if ($row->scan_imb) {
                        return '<a href="' . asset('storage/' . $row->scan_imb) . '" download>' . $row->scan_imb . '</a>';
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['action', 'scan_imb'])
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
            'kabupaten' => 'required|string|max:50',
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
            $scanImbPath = $request->file('scan_imb')->store('scans', 'public');
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
            'kabupaten' => $validatedData['kabupaten'],
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
            ->join('master_regency', 'imb_perluasan.kabupaten', '=', 'master_regency.code')
            ->join('master_district', 'imb_perluasan.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_perluasan.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_perluasan.*', 'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan', 'master_regency.name as kabupaten' , 'master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
            ->where('imb_perluasan.id', $id)->first();

        $imbPecahan = IMBPerluasan::where('imb_pecahan', $data->imb_pecahan)->first();


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
            'kabupaten' => 'required|string|max:50',
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
            $scanImbPath = $request->file('scan_imb')->store('scans', 'public');
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
                'kabupaten' => $validatedData['kabupaten'],
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
                'kabupaten' => $validatedData['kabupaten'],
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

        return redirect()->route('IMBPerluasan.index')->with(['status' => 'success', 'message' => 'Data berhasil diupdate']);

    }

    public function destroy($id)
    {
        DB::table('imb_perluasan')->where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    // public function importData(Request $request)
    // {
    //     ini_set('max_execution_time', 0); // Unlimited execution time
    //     ini_set('memory_limit', '-1');
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);
    //     $file = $request->file('file');
    //     $failures = [];
    //     $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
    //     $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
    //     $baris = 1;
    //     $users = (new FastExcel)->import($file, function ($line) use (&$failures, &$baris, $jenisKegiatanList, $fungsiBangunanList) {
    //         $fail = 0;
    //         $rowJenisKegiatan = strtolower($line['Jenis Kegiatan']);
    //         $rowFungsiBangunan = strtolower($line['Fungsi Kegiatan']); // note, ini fungsi bangunan apa fungsi kegiatan
    //         $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
    //         $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
    //         $rowDistrict = strtolower($line['Kecamatan']);
    //         $rowSubdistrict = strtolower($line['Desa / Kelurahan']);
    //         $rowRegency = strtolower($line["Kabupaten / Kota"]);

    //         // dd($line["Tgl. IMB Lama"]->format('Y-m-d'));

    //         $regency = DB::table('master_regency')
    //         ->where(DB::raw('LOWER(name)'), $rowRegency)
    //         ->pluck('code')
    //         ->first();
    //         if (!$regency) {
    //                 $fail = 1;
    //                 IMBPerluasan::create([
    //                     'imb_pecahan' => $line['No. IMB Lama'],
    //                     // 'tgl_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
    //                     'tgl_pecahan' => $line["Tgl. IMB Lama"]->format('Y-m-d'),
    //                     'imb_perluasan' => $line['No. IMB Perluasan'],
    //                     'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
    //                     'no_register' => $line['No. Register'],
    //                     'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
    //                     'nama' => $line['Nama'],
    //                     'atas_nama' => $line['Atas Nama'],
    //                     'jenis_kegiatan' => $jenis_kegiatan,
    //                     'fungsi_bangunan' => $fungsi_bangunan,
    //                     'lokasi_perumahan' => $line['Lokasi / Perumahan'],
    //                     'kabupaten_lama' => $line['Kabupaten / Kota'],
    //                     'kecamatan_lama' => $line['Kecamatan'],
    //                     'kelurahan_lama' => $line['Desa / Kelurahan'],
    //                     'type' => $line['Type'],
    //                     'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
    //                     'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
    //                     'blok' => $line['Blok'],
    //                     'no_blok' => $line['No Blok'],
    //                     'keterangan' => $line['Keterangan']
    //                 ]);
    //                 $failures[] = [
    //                     'message' => 'Kabupaten ' . $line["Kabupaten / Kota"] . ' tidak ditemukan',
    //                     'baris' => $baris,
    //                 ];
    //                 return;
    //         }
    //         $errorDistricts = 0;
    //         $districts = DB::table('master_district')
    //             ->where(DB::raw('LOWER(name)'), $rowDistrict)
    //             ->where('regency_code', $regency)
    //             ->pluck('code')
    //             ->toArray();
    //         if (empty($districts)) {
    //             $fail = 1;
    //             $errorDistricts = 1;
    //             IMBPerluasan::create([
    //                 'imb_pecahan' => $line['No. IMB Lama'],
    //                 // 'tgl_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
    //                 'tgl_pecahan' => $line["Tgl. IMB Lama"]->format('Y-m-d'),
    //                 'imb_perluasan' => $line['No. IMB Perluasan'],
    //                 // 'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
    //                 'tgl_imb_perluasan' => $line["Tgl. IMB Perluasan"]->format('Y-m-d'),
    //                 'no_register' => $line['No. Register'],
    //                 'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
    //                 'nama' => $line['Nama'],
    //                 'atas_nama' => $line['Atas Nama'],
    //                 'jenis_kegiatan' => $jenis_kegiatan,
    //                 'fungsi_bangunan' => $fungsi_bangunan,
    //                 'lokasi_perumahan' => $line['Lokasi / Perumahan'],
    //                 'kabupaten_lama' => $line['Kabupaten / Kota'],
    //                 'kecamatan_lama' => $line['Kecamatan'],
    //                 'kelurahan_lama' => $line['Desa / Kelurahan'],
    //                 'type' => $line['Type'],
    //                 'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
    //                 'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
    //                 'blok' => $line['Blok'],
    //                 'no_blok' => $line['No Blok'],
    //                 'keterangan' => $line['Keterangan']
    //             ]);
    //             $failures[] = [
    //                 'message' => 'Kecamatan ' . $line['Kecamatan'] . ' tidak ditemukan',
    //                 'baris' => $baris,
    //             ];
    //         }

    //         $village = DB::table('master_subdistrict')
    //             ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
    //             ->whereIn('district_code', $districts)
    //             ->first();
    //         if (!$village) {
    //             if ($errorDistricts == 1) {
    //                 $failures[] = [
    //                     'message' => 'Desa/Kelurahan ' . $line['Desa / Kelurahan'] . ' tidak ditemukan di kecamatan ' . $line['Kecamatan'],
    //                     'baris' => $baris,
    //                 ];
    //                 return;
    //             }
    //             $fail = 1;
    //             IMBPerluasan::create([
    //                 'imb_pecahan' => $line['No. IMB Lama'],
    //                 // 'tgl_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
    //                 'tgl_pecahan' => $line["Tgl. IMB Lama"]->format('Y-m-d'),
    //                 'imb_perluasan' => $line['No. IMB Perluasan'],
    //                 // 'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
    //                 'tgl_imb_perluasan' => $line["Tgl. IMB Perluasan"]->format('Y-m-d'),
    //                 'no_register' => $line['No. Register'],
    //                 'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
    //                 'nama' => $line['Nama'],
    //                 'atas_nama' => $line['Atas Nama'],
    //                 'jenis_kegiatan' => $jenis_kegiatan,
    //                 'fungsi_bangunan' => $fungsi_bangunan,
    //                 'lokasi_perumahan' => $line['Lokasi / Perumahan'],
    //                 'kabupaten_lama' => $line['Kabupaten / Kota'],
    //                 'kecamatan_lama' => $line['Kecamatan'],
    //                 'kelurahan_lama' => $line['Desa / Kelurahan'],
    //                 'type' => $line['Type'],
    //                 'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
    //                 'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
    //                 'blok' => $line['Blok'],
    //                 'no_blok' => $line['No Blok'],
    //                 'keterangan' => $line['Keterangan']
    //             ]);
    //             $failures[] = [
    //                 'message' => 'Desa/Kelurahan ' . $line['Desa / Kelurahan'] . ' tidak ditemukan di kecamatan ' . $line['Kecamatan'],
    //                 'baris' => $baris,
    //             ];
    //         }

    //         $baris++;
    //         if ($fail == 0) {
    //             //dd($regency);
    //             IMBPerluasan::create([
    //                 'imb_pecahan' => $line['No. IMB Lama'],
    //                 'tgl_imb_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
    //                 'imb_perluasan' => $line['No. IMB Perluasan'],
    //                 'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
    //                 'no_register' => $line['No. Register'],
    //                 'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
    //                 'nama' => $line['Nama'],
    //                 'atas_nama' => $line['Atas Nama'],
    //                 'jenis_kegiatan' => $jenis_kegiatan,
    //                 'fungsi_bangunan' => $fungsi_bangunan,
    //                 'lokasi_perumahan' => $line['Lokasi / Perumahan'],
    //                 'kabupaten' => $regency,
    //                 'kecamatan' => $village->district_code,
    //                 'desa_kelurahan' => $village->code,
    //                 'type' => $line['Type'],
    //                 'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
    //                 'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
    //                 'blok' => $line['Blok'],
    //                 'no_blok' => $line['No Blok'],
    //                 'keterangan' => $line['Keterangan']
    //             ]);
    //         }
    //     });
    //     if (count($failures) > 0) {
    //         //dd($failures);
    //         return redirect()->back()->with(['status' => 'error', 'message' => 'Import data selesai, namun terdapat kesalahan. Silahkan download file log untuk melihat detail kesalahan.'])->with('failures', $failures);
    //     } else {
    //         return redirect()->back()->with(['status' => 'success', 'message' => 'Import data berhasil']);
    //     }
    // }



    public function importData(Request $request)
    {
        // Performance settings
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $file = $request->file('file');
        $failures = [];
        $baris = 1;

        // Cache frequently accessed data in memory
        $jenisKegiatanList = cache()->remember('jenis_kegiatan_list', 60, function () {
            return DB::table('app_md_jeniskeg')
                ->pluck('id_jeniskeg', 'name_jeniskeg')
                ->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        });

        $fungsiBangunanList = cache()->remember('fungsi_bangunan_list', 60, function () {
            return DB::table('app_md_fungsibang')
                ->pluck('id_fungsibang', 'name_fungsibang')
                ->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        });

        // Pre-fetch and cache location data
        $locationCache = cache()->remember('location_cache', 60, function () {
            $regencies = DB::table('master_regency')->get(['code', 'name'])->keyBy(fn($item) => strtolower($item->name));
            $districts = DB::table('master_district')->get(['code', 'name', 'regency_code']);
            $subdistricts = DB::table('master_subdistrict')->get(['code', 'name', 'district_code']);

            return [
                'regencies' => $regencies,
                'districts' => $districts->groupBy('regency_code'),
                'subdistricts' => $subdistricts->groupBy('district_code')
            ];
        });

        // Prepare batch insert
        $batchSize = 1000;
        $records = [];

        DB::beginTransaction();
        try {
            (new FastExcel)->import($file, function ($line) use (&$failures, &$baris, $jenisKegiatanList, $fungsiBangunanList, $locationCache, &$records, $batchSize) {
                $rowJenisKegiatan = strtolower($line['Jenis Kegiatan']);
                $rowFungsiBangunan = strtolower($line['Fungsi Bangunan']);
                $rowRegency = strtolower($line["Kabupaten / Kota"]);
                $rowDistrict = strtolower($line['Kecamatan']);
                $rowSubdistrict = strtolower($line['Desa / Kelurahan']);

                // Quick lookups from cached data
                $regency = $locationCache['regencies'][$rowRegency] ?? null;

                if (!$regency) {
                    IMBPerluasan::create([
                    'imb_pecahan' => $line['No. IMB Lama'],
                    'tgl_imb_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
                    'imb_perluasan' => $line['No. IMB Perluasan'],
                    'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
                    'no_register' => $line['No. Register'],
                    'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
                    'nama' => $line['Nama'],
                    'atas_nama' => $line['Atas Nama'],
                    'jenis_kegiatan' => $jenisKegiatanList[$rowJenisKegiatan] ?? null,
                        'fungsi_bangunan' => $fungsiBangunanList[$rowFungsiBangunan] ?? null,
                    'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                    'kabupaten_lama' => $line['Kabupaten / Kota'],
                    'kecamatan_lama' => $line['Kecamatan'],
                    'kelurahan_lama' => $line['Desa / Kelurahan'],
                    'type' => $line['Type'],
                    'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
                    'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
                    'blok' => $line['Blok'],
                    'no_blok' => $line['No Blok'],
                    'keterangan' => $line['Keterangan']
                    ]);

                    $this->handleFailure($failures, $baris, $line, "Kabupaten {$line['Kabupaten / Kota']} tidak ditemukan");
                    $baris++;
                    return;
                }

                $districts = $locationCache['districts'][$regency->code] ?? collect();
                $district = $districts->first(fn($d) => strtolower($d->name) === $rowDistrict);

                if (!$district) {
                    IMBPerluasan::create([
                        'imb_induk_id' => $line['No. IMB Induk'],
                        'tgl_imb_induk' => null,
                        'imb_pecahan' => $line['No. IMB Pecahan / Rincikan'],
                        'tgl_imb_pecahan' => is_string($line['Tgl. Pecahan / Rincikan'])
                            ? date('Y-m-d', strtotime($line['Tgl. Pecahan / Rincikan']))
                            : $line['Tgl. Pecahan / Rincikan']->format('Y-m-d'),
                        'no_register' => $line['No. Register'],
                        'tgl_register' => null,
                        'nama' => $line['Nama'],
                        'atas_nama' => $line['Atas Nama'],
                        'jenis_kegiatan' => $jenisKegiatanList[$rowJenisKegiatan] ?? null,
                        'fungsi_bangunan' => $fungsiBangunanList[$rowFungsiBangunan] ?? null,
                        'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                        'kabupaten_lama' => $line['Kabupaten / Kota'],
                        'kecamatan_lama' => $line['Kecamatan'],
                        'kelurahan_lama' => $line['Desa / Kelurahan'],
                        'type' => $line['Type'],
                        'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                        'blok' => $line['Blok'],
                        'no_blok' => $line['No Blok'],
                        'keterangan' => $line['Keterangan']
                    ]);

                    $this->handleFailure($failures, $baris, $line, "Kecamatan {$line['Kecamatan']} tidak ditemukan");
                    $baris++;
                    return;
                }

                $subdistricts = $locationCache['subdistricts'][$district->code] ?? collect();
                $village = $subdistricts->first(fn($s) => strtolower($s->name) === $rowSubdistrict);

                if (!$village) {
                    IMBPerluasan::create([
                        'imb_induk_id' => $line['No. IMB Induk'],
                        'tgl_imb_induk' => null,
                        'imb_pecahan' => $line['No. IMB Pecahan / Rincikan'],
                        'tgl_imb_pecahan' => is_string($line['Tgl. Pecahan / Rincikan'])
                            ? date('Y-m-d', strtotime($line['Tgl. Pecahan / Rincikan']))
                            : $line['Tgl. Pecahan / Rincikan']->format('Y-m-d'),
                        'no_register' => $line['No. Register'],
                        'tgl_register' => null,
                        'nama' => $line['Nama'],
                        'atas_nama' => $line['Atas Nama'],
                        'jenis_kegiatan' => $jenisKegiatanList[$rowJenisKegiatan] ?? null,
                        'fungsi_bangunan' => $fungsiBangunanList[$rowFungsiBangunan] ?? null,
                        'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                        'kabupaten_lama' => $line['Kabupaten / Kota'],
                        'kecamatan_lama' => $line['Kecamatan'],
                        'kelurahan_lama' => $line['Desa / Kelurahan'],
                        'type' => $line['Type'],
                        'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                        'blok' => $line['Blok'],
                        'no_blok' => $line['No Blok'],
                        'keterangan' => $line['Keterangan']
                    ]);
                    $this->handleFailure($failures, $baris, $line, "Desa/Kelurahan {$line['Desa / Kelurahan']} tidak ditemukan di kecamatan {$line['Kecamatan']}");
                    $baris++;
                    return;
                }

                // Prepare record for batch insert
                $records[] = [
                    'imb_pecahan' => $line['No. IMB Lama'],
                    'tgl_imb_pecahan' => date('Y-m-d', strtotime($line['Tgl. IMB Lama'])),
                    'imb_perluasan' => $line['No. IMB Perluasan'],
                    'tgl_imb_perluasan' => date('Y-m-d', strtotime($line['Tgl. IMB Perluasan'])),
                    'no_register' => $line['No. Register'],
                    'tgl_register' => date('Y-m-d', strtotime($line['Tgl. Register'])),
                    'nama' => $line['Nama'],
                    'atas_nama' => $line['Atas Nama'],
                   'jenis_kegiatan' => $jenisKegiatanList[$rowJenisKegiatan] ?? null,
                    'fungsi_bangunan' => $fungsiBangunanList[$rowFungsiBangunan] ?? null,
                    'lokasi_perumahan' => $line['Lokasi / Perumahan'],
                    'kabupaten' => $regency,
                    'kecamatan' => $village->district_code,
                    'desa_kelurahan' => $village->code,
                    'type' => $line['Type'],
                    'luas_bangunan_lama' => $line['Luas Bangunan Lama'] == '' ? null : $line['Luas Bangunan Lama'],
                    'luas_bangunan_perluasan' => $line['Luas Bangunan Perluasan'] == '' ? null : $line['Luas Bangunan Perluasan'],
                    'blok' => $line['Blok'],
                    'no_blok' => $line['No Blok'],
                    'keterangan' => $line['Keterangan'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Batch insert when reaching batch size
                if (count($records) >= $batchSize) {
                    IMBPerluasan::insert($records);
                    $records = [];
                }

                $baris++;
            });

            // Insert remaining records
            if (!empty($records)) {
                IMBPerluasan::insert($records);
            }

            DB::commit();

            return redirect()->back()->with([
                'status' => count($failures) > 0 ? 'error' : 'success',
                'message' => count($failures) > 0
                    ? 'Import data selesai, namun terdapat kesalahan. Silahkan download file log untuk melihat detail kesalahan.'
                    : 'Import data berhasil',
                'failures' => $failures
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    private function handleFailure(&$failures, $baris, $line, $message)
    {
        $failures[] = [
            'message' => $message,
            'baris' => $baris,
        ];
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
