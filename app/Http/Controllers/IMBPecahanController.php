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
            $query = IMBPecahan::join('app_md_jeniskeg', 'imb_pecahan.jenis_kegiatan', '=', 'app_md_jeniskeg.id_jeniskeg')
                ->join('master_regency', 'imb_pecahan.kabupaten', '=', 'master_regency.code')
                ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
                ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
                ->select(
                    'imb_pecahan.id',
                    'imb_pecahan.imb_induk_id',
                    'imb_pecahan.tgl_imb_induk',
                    'imb_pecahan.imb_pecahan',
                    'imb_pecahan.tgl_imb_pecahan',
                    'imb_pecahan.no_register',
                    'imb_pecahan.tgl_register',
                    'imb_pecahan.nama',
                    'imb_pecahan.atas_nama',
                    'imb_pecahan.lokasi_perumahan',
                    'imb_pecahan.type',
                    'imb_pecahan.luas',
                    'imb_pecahan.blok',
                    'imb_pecahan.no_blok',
                    'imb_pecahan.keterangan',
                    'imb_pecahan.scan_imb',
                    'app_md_jeniskeg.name_jeniskeg as jenis_kegiatan',
                    'master_regency.name as kabupaten',
                    'master_regency.code as kabupaten_code',
                    'master_district.name as kecamatan',
                    'master_district.code as kecamatan_code',
                    'master_subdistrict.name as kelurahan',
                    'master_subdistrict.code as kelurahan_code'
                );


            if ($request->has('kabupaten') && $request->kabupaten) {
                $query->where('imb_pecahan.kabupaten', $request->kabupaten);
            }

            // Filter berdasarkan kecamatan
            if ($request->has('kecamatan') && $request->kecamatan) {
                $query->where('imb_pecahan.kecamatan', $request->kecamatan);
            }

            // Filter berdasarkan kelurahan
            if ($request->has('kelurahan') && $request->kelurahan) {
                $query->where('imb_pecahan.desa_kelurahan', $request->kelurahan);
            }

            $query = $query->orderBy('imb_pecahan.created_at', 'desc');



            return Datatables::of($query)
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
        return view('IMBPecahan.index');
    }

    public function import()
    {
        return view('IMBPecahan.import');
    }

    public function importData(Request $request)
    {
        // Mengatur PHP untuk tidak ada batasan waktu eksekusi dan penggunaan memori
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel

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
                    IMBPecahan::create([
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

                    $this->handleFailure($failures, $baris, $line, "Kabupaten {$line['Kabupaten / Kota']} tidak ditemukan");
                    $baris++;
                    return;
                }

                $districts = $locationCache['districts'][$regency->code] ?? collect();
                $district = $districts->first(fn($d) => strtolower($d->name) === $rowDistrict);

                if (!$district) {
                    IMBPecahan::create([
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
                    IMBPecahan::create([
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
                    'kabupaten' => $regency->code,
                    'kecamatan' => $district->code,
                    'desa_kelurahan' => $village->code,
                    'type' => $line['Type'],
                    'luas' => $line['Luas'] == '' ? null : $line['Luas'],
                    'blok' => $line['Blok'],
                    'no_blok' => $line['No Blok'],
                    'keterangan' => $line['Keterangan'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Batch insert when reaching batch size
                if (count($records) >= $batchSize) {
                    IMBPecahan::insert($records);
                    $records = [];
                }

                $baris++;
            });

            // Insert remaining records
            if (!empty($records)) {
                IMBPecahan::insert($records);
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
            'kabupaten' => 'nullable|string',
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
            $scanImbPath = $request->file('scan_imb')->store('scans', 'public');
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
            'kabupaten' => $validatedData['kabupaten'],
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
            ->join('master_regency', 'imb_pecahan.kabupaten', '=', 'master_regency.code')
            ->join('master_district', 'imb_pecahan.kecamatan', '=', 'master_district.code')
            ->join('master_subdistrict', 'imb_pecahan.desa_kelurahan', '=', 'master_subdistrict.code')
            ->select('imb_pecahan.*', 'master_regency.name as kabupaten', 'master_regency.code as kabupaten_code', 'master_district.name as kecamatan', 'master_district.code as kecamatan_code', 'master_subdistrict.name as kelurahan', 'master_subdistrict.code as kelurahan_code')
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
            'kabupaten' => 'nullable|string',
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
            $scanImbPath = $request->file('scan_imb')->store('scans', 'public');
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
                'kabupaten' => $validatedData['kabupaten'],
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
                'kabupaten' => $validatedData['kabupaten'],
                'kecamatan' => $validatedData['kecamatan'],
                'desa_kelurahan' => $validatedData['desa_kelurahan'],
                'type' => $validatedData['type'],
                'luas' => $validatedData['luas'],
                'blok' => $validatedData['blok'],
                'no_blok' => $validatedData['no_blok'],
                'keterangan' => $validatedData['keterangan'],
            ]);
        }
        return redirect()->route('IMBPecahan.index')->with(['status' => 'success', 'message' => 'Data berhasil diubah']);
    }

    public function destroy($id)
    {
        IMBPecahan::destroy($id);
        return redirect()->back()->with(['success' => 'Data berhasil dihapus']);
    }

    public function exportData()
    {
        ini_set('max_execution_time', 0); // Tidak ada batas waktu eksekusi
        ini_set('memory_limit', '-1'); // Tidak ada batas penggunaan memori
        ini_set('upload_max_filesize', '0'); // Tidak ada batasan ukuran file
        ini_set('post_max_size', '0'); // Tidak ada batasan ukuran data POST
        ini_set('max_input_time', '0'); // Tidak ada batasan waktu input data
        ini_set('max_input_vars', 10000); // Mengatur batas input variabel
        return Excel::download(new IMBPecahanExport, 'IMBPecahan' . Carbon::now()->timestamp . '.xlsx');
    }

    public function downloadTemplate()
    {
        $template = public_path('template/imb_pecahan_contoh.xlsx');
        return response()->download($template);
    }
}
