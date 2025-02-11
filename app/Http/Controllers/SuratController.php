<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
// use Dompdf\Dompdf;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use \Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;


class SuratController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // $query = \DB::table('surat');
            $query = \DB::table('surat')
                ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code') // Join ke master_regency
                ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join ke master_district
                ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join ke master_subdistrict
                ->select(
                        'surat.id',
                        'surat.jenisSurat',
                        'surat.tahun',
                        'surat.nomorSurat',
                        'surat.tanggalSurat',
                        'surat.lampiran',
                        'surat.sifat',
                        'surat.perihal',
                        'surat.permohonanTanggal',
                        'surat.nama',
                        'surat.bertindak_atas_nama',
                        'surat.alamat',
                        'surat.izin_mendirikan_bangunan_atas_nama',
                        'surat.lokasi',
                        'surat.tujuanSurat',
                        'surat.jenisKegiatan',
                        'surat.registerNomor',
                        'surat.registerTanggal',
                        'surat.imbgNomor',
                        'surat.imbgTanggal',
                        'surat.provinsi',
                        'surat.kabupaten',
                        'surat.kecamatan',
                        'surat.kelurahan',
                        'surat.provinsi_terdahulu',
                        'surat.kabupaten_terdahulu',
                        'surat.kecamatan_terdahulu',
                        'surat.kelurahan_terdahulu',
                        'surat.provinsiPemohon',
                        'surat.kabupatenPemohon',
                        'surat.kecamatanPemohon',
                        'surat.kelurahanPemohon',
                        'surat.jabatan',
                        'surat.kepalaDinas',
                        'surat.pangkat',
                        'surat.keterangan',
                        'surat.details',
                        'surat.details2',
                        'surat.file',
                        'surat.upload',
                        'surat.created_at',
                        'surat.updated_at',
                        'surat.sapaanPemohon',
                        'surat.font_surat',
                    'master_regency.name as nama_kabupaten',
                    'master_district.name as nama_kecamatan',
                    'master_subdistrict.name as nama_kelurahan'
                );
            // dd($query->count());
            // \Log::info($query->toSql(), $query->getBindings()); // Log query dan bindings
            $data = $query;
            // dd($data);
                if (isset($_GET['format'])) {
                    switch ($_GET['format']) {
                        case '1':
                            $query->where('jenisSurat', 'format-1');
                            break;
                        case '2':
                            $query->where('jenisSurat', 'format-2');
                            break;
                        case '3':
                            $query->where('jenisSurat', 'format-3');
                            break;
                        case '4':
                            $query->where('jenisSurat', 'format-4');
                            break;
                    }
                }


            // Filter berdasarkan input dari request
            if ($request->has('nomor_surat') && $request->input('nomor_surat') != null) {
                $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
            }

            if ($request->has('nama_pemohon') && $request->input('nama_pemohon') != null) {
                $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
            }

            if ($request->has('nomor_imbg')  && $request->input('nomor_imbg') != null) {
                $query->where('surat.imbgNomor', 'like', '%' . $request->input('nomor_imbg') . '%');
            }

            if ($request->has('lokasi_bangunan')  && $request->input('lokasi_bangunan') != null) {
                $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
            }
            if ($request->has('kabupaten_pemohon') && $request->input('kabupaten_pemohon') != null) {

                $query->where('master_regency.name', 'like', '%' . $request->input('kabupaten_pemohon') . '%');
            }

            if ($request->has('kecamatan_pemohon') && $request->input('kecamatan_pemohon') != null) {
                $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
            }

            if ($request->has('kelurahan_pemohon')  && $request->input('kelurahan_pemohon') != null) {
                $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
            }


            $query = $query->orderBy('id', 'desc');

            return Datatables::of($query)
                ->filterColumn('nama_kabupaten', function ($query, $keyword) {
                    $query->whereRaw('(master_regency.name) LIKE ?', ["%$keyword%"]);
                })
                ->filterColumn('nama_kecamatan', function ($query, $keyword) {
                    $query->whereRaw('(master_district.name) LIKE ?', ["%$keyword%"]);
                })
                ->filterColumn('nama_kelurahan', function ($query, $keyword) {
                    $query->whereRaw('(master_subdistrict.name) LIKE ?', ["%$keyword%"]);
                })
                ->addColumn('action', function ($row) {
                    // Periksa jenisSurat untuk menyembunyikan tombol lihat table
                    $showLihatTable = !in_array($row->jenisSurat, ['format-1', 'format-4']); // Menyembunyikan jika format-1 atau format-4

                    // Membuat tombol actions
                    $actions = '
                        <div class="d-flex flex-wrap" style="display:flex;gap:8px;">
                            <div class="d-flex align-items-center" style="gap:5px;display:flex; width: 50%;">
                                <a href="' . route('surat.download', $row->id) . '" class="btn btn-success btn-sm" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" data-url="' . route('surat.upload', $row->id) . '" class="btn btn-info btn-sm text-white uploadSuratModal" title="Upload"
                                    data-toggle="modal" data-target="#uploadSuratModal">
                                    <i class="fas fa-upload"></i>
                                </button>
                                 <a href="' . route('surat.lihat', $row->id) . '" onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;" class="btn btn-primary btn-sm" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>';

                                // Tombol Lihat Table hanya akan muncul jika jenisSurat bukan format-1 dan format-4
                                if ($showLihatTable) {
                                    $actions .= '

                                            <a href="' . route('surat.lihatTable', $row->id) . '" onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;" class="btn btn-primary btn-sm" title="Lihat Table">
                                                <i class="fa fa-table"></i>
                                            </a>
                                     ';
                                }
                            $actions .= '
                                    <button type="button" data-url="' . route('surat.update_nomor', $row->id) . '" class="btn btn-warning btn-sm text-white updateNomorModal" title="Update Nomor"
                                        data-toggle="modal" data-target="#updateNomorModal">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <a class="btn btn-warning btn-sm text-white" href="' . route('surat.edit', $row->id) . '">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="' . route('surat.destroy', $row->id) . '" method="POST" style="display:inline;">
                                        ' . csrf_field() . method_field('DELETE') . '
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="confirmDelete(event)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                    </form>

                                </div>
                                '
                                ;


                            ;


                    return $actions;
                })
                ->addColumn('sudah_upload', function ($row) {
                    return $row->upload ?
                        '<span class="badge badge-success">Sudah</span>' :
                        '<span class="badge badge-da    nger">Belum</span>';
                })
                ->editColumn('nama_kecamatan', function ($row) {
                    return ucwords(strtolower($row->nama_kecamatan));
                })
                ->editColumn('nama_kecamatan', function ($row) {
                    return ucwords(strtolower($row->nama_kecamatan));
                })
                ->editColumn('nama_kelurahan', function ($row) {
                    return ucwords(strtolower($row->nama_kelurahan));
                })
                ->rawColumns(['action', 'sudah_upload'])
                ->addIndexColumn()
                ->make(true);
        }
         // Ambil data surat untuk select box
        // $surat = \DB::table('surat')->select('id', 'nomorSurat', 'nama', 'tahun')->get();

        // return view('surat.index',  compact('surat'));
        //   return view('surat.index',  compact('surat'));
        return view('surat.index');
    }


    public function create()
    {
        return view('surat.create');
    }

    public function edit($id){
        $data =  (array) \DB::table('surat')->where('id',$id)->first();



        $strKabupaten = \DB::table('master_regency')
        ->where('code', $data['kabupaten'])
        ->first()->name;
        $kabupaten = str_replace('kab.', 'Kabupaten', strtolower($strKabupaten));
        $data['nama_kabupaten'] = ucwords($kabupaten);

        // Untuk Kecamatan
        $strKecamatan = \DB::table('master_district')
            ->where('code', $data['kecamatan'])
            ->first()->name;
        $kecamatan = ucwords(strtolower($strKecamatan));
        $data['nama_kecamatan'] = $kecamatan;


        // Untuk Kelurahan
        $strKelurahan = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahan'])
            ->first()->name;
        $kelurahan = ucwords(strtolower($strKelurahan));
        $data['nama_kelurahan'] = $kelurahan;

        // Untuk Provinsi
        $strProvinsi = \DB::table('master_province')
            ->where('code', 32)
            ->first()->name;
        $provinsi = ucwords(strtolower($strProvinsi));
        $data['nama_provinsi'] = $provinsi;




        $strKabupaten = \DB::table('master_regency')
        ->where('code', $data['kabupatenPemohon'])
        ->first()->name;
        $kabupaten = str_replace('kab.', 'Kabupaten', strtolower($strKabupaten));
        $data['nama_kabupaten_pemohon'] = ucwords($kabupaten);

        // Untuk Kecamatan
        $strKecamatan = \DB::table('master_district')
            ->where('code', $data['kecamatanPemohon'])
            ->first()->name;
        $kecamatan = ucwords(strtolower($strKecamatan));
        $data['nama_kecamatan_pemohon'] = $kecamatan;


        // Untuk Kelurahan
        $strKelurahan = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahanPemohon'])
            ->first()->name;
        $kelurahan = ucwords(strtolower($strKelurahan));
        $data['nama_kelurahan_pemohon'] = $kelurahan;

        $strProvinsi = \DB::table('master_province')
        ->where('code',$data['provinsiPemohon'] )
        ->first()->name;
        $provinsi = ucwords(strtolower($strProvinsi));
        $data['nama_provinsi_pemohon'] = $provinsi;


        $data['keterangan'] = json_decode($data['keterangan']);
        $data['details'] = json_decode($data['details']);
        $data['details2'] = json_decode($data['details2']);

        $url = '/imb/surat/update/'.$data['id'];

        return view('surat.edit',compact('data','url'));
    }

    public function format1()
    {
        return view('surat.format-1');
    }

    public function format3()
    {
        return view('surat.format-3');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $namaFile = 'surat-' . $data['nomorSurat'] . uniqid() . '.pdf';

        // dd($data);
        if (in_array($data['jenisSurat'], ['format-1', 'format-2', 'format-3'])) {
            $validate['jenisKegiatan'] = 'nullable';
            $validate['tujuanSurat'] = 'required';
        } elseif ($data['jenisSurat'] == 'format-4') {
            $validate['tujuanSurat'] = 'nullable';
            $validate['jenisKegiatan'] = 'required';
        }
        if ($data['jenisSurat'] == 'format-2') {
            $validate['details'] = 'required';
            $validate['details2'] = 'nullable';
        } elseif ($data['jenisSurat'] == 'format-3') {
            $validate['details'] = 'required';
            $validate['details2'] = 'required';
        } elseif (in_array($data['jenisSurat'], ['format-1', 'format-4'])) {
            $validate['details'] = 'nullable';
            $validate['details2'] = 'nullable';
        }
        $validate = $request->validate([
            'jenisSurat' => 'required',
            'tahun' => 'required',
            'nomorSurat' => 'nullable',
            'tanggalSurat' => 'nullable',
            'lampiran' => 'nullable',
            'sifat' => 'required',
            'perihal' => 'required',
            'permohonanTanggal' => 'required',
            'nama' => 'required',
            'bertindak_atas_nama' => 'required',
            'alamat' => 'required',
            'izin_mendirikan_bangunan_atas_nama' => 'required',
            'lokasi' => 'nullable',

            'registerNomor' => 'nullable',
            'registerTanggal' => 'required',
            'imbgNomor' => 'nullable',
            'imbgTanggal' => 'required',
            'sapaanPemohon' => 'required',
            'provinsiPemohon' => 'required',
            'kabupatenPemohon' => 'required',
            'kecamatanPemohon' => 'required',
            'kelurahanPemohon' => 'required',
            'jabatan' => 'required',
            'font_surat' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'kepalaDinas' => 'required',
            'pangkat' => 'required',
            'ket' => 'required',

        ]);

        \DB::table('surat')->insert([
            'jenisSurat' => $request->input('jenisSurat'),
            'tahun' => $request->input('tahun'),
            'nomorSurat' => $request->input('nomorSurat'),
            'tanggalSurat' => $request->input('tanggalSurat'),
            'lampiran' => $request->input('lampiran'),
            'sifat' => $request->input('sifat'),
            'perihal' => $request->input('perihal'),
            'permohonanTanggal' => $request->input('permohonanTanggal'),
            'nama' => $request->input('nama'),
            'bertindak_atas_nama' => $request->input('bertindak_atas_nama'),
            'alamat' => $request->input('alamat'),
            'izin_mendirikan_bangunan_atas_nama' => $request->input('izin_mendirikan_bangunan_atas_nama'),
            'lokasi' => $request->input('lokasi'),
            'jenisKegiatan' => $request->input('jenisKegiatan'),
            'tujuanSurat' => $request->input('tujuanSurat'),
            'registerNomor' => $request->input('registerNomor'),
            'registerTanggal' => $request->input('registerTanggal'),
            'imbgNomor' => $request->input('imbgNomor'),
            'imbgTanggal' => $request->input('imbgTanggal'),

            'sapaanPemohon' => $request->input('sapaanPemohon'),
            'provinsiPemohon' => $request->input('provinsiPemohon'),
            'kabupatenPemohon' => $request->input('kabupatenPemohon'),
            'kecamatanPemohon' => $request->input('kecamatanPemohon'),
            'kelurahanPemohon' => $request->input('kelurahanPemohon'),
            'jabatan' => $request->input('jabatan'),

            'provinsi' => $request->input('provinsi'),
            'font_surat' => $request->input('font_surat'),
            'kabupaten' => $request->input('kabupaten'),
            'kecamatan' => $request->input('kecamatan'),
            'kelurahan' => $request->input('kelurahan'),
            'provinsi_terdahulu' => $request->input('provinsi-terdahulu'),
            'kabupaten_terdahulu' => $request->input('kabupaten-terdahulu'),
            'kecamatan_terdahulu' => $request->input('kecamatan-terdahulu'),
            'kelurahan_terdahulu' => $request->input('kelurahan-terdahulu'),
            'kepalaDinas' => $request->input('kepalaDinas'),
            'pangkat' => $request->input('pangkat'),
            'keterangan' => json_encode($request->input('ket')),
            'details' => json_encode($request->input('details')),
            'details2' => json_encode($request->input('details2')),
            'file' => $namaFile,
        ]);


        $jenisSurat = $data['jenisSurat'];
        list($nip, $kepalaDinas) = explode(' | ', $request->kepalaDinas);

        $details = json_encode($request->input('details'));
        $details2 = json_encode($request->input('details2'));

        // Untuk Kabupaten
        $strKabupaten = \DB::table('master_regency')
            ->where('code', $data['kabupaten'])
            ->first()->name;
        $kabupaten = str_replace('kab.', 'Kabupaten', strtolower($strKabupaten));
        $kabupaten = ucwords($kabupaten);

        // Untuk Kecamatan
        $strKecamatan = \DB::table('master_district')
            ->where('code', $data['kecamatan'])
            ->first()->name;
        $kecamatan = ucwords(strtolower($strKecamatan));

        // Untuk Kelurahan
        $strKelurahan = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahan'])
            ->first()->name;
        $kelurahan = ucwords(strtolower($strKelurahan));

        // Untuk Provinsi
        $strProvinsi = \DB::table('master_province')
            ->where('code', $data['provinsi'])
            ->first()->name;
        $provinsi = ucwords(strtolower($strProvinsi));

        $strKabupatenPemohon = \DB::table('master_regency')
            ->where('code', $data['kabupatenPemohon'])
            ->first()->name;
        $kabupatenPemohon = str_replace('kab.', 'Kabupaten', strtolower($strKabupatenPemohon));
        $kabupatenPemohon = ucwords($kabupatenPemohon);

        // Untuk Kecamatan
        $strKecamatanPemohon = \DB::table('master_district')
            ->where('code', $data['kecamatanPemohon'])
            ->first()->name;
        $kecamatanPemohon = ucwords(strtolower($strKecamatanPemohon));

        // Untuk Kelurahan
        $strKelurahanPemohon = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahanPemohon'])
            ->first()->name;
        $kelurahanPemohon = ucwords(strtolower($strKelurahanPemohon));

        // Untuk Provinsi
        $strProvinsiPemohon = \DB::table('master_province')
            ->where('code', $data['provinsiPemohon'])
            ->first()->name;

        $provinsiPemohon = ucwords(strtolower($strProvinsiPemohon));

        $timestamp = strtotime($data['permohonanTanggal']);

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $day = date('d', $timestamp);
        $month = $months[date('m', $timestamp)];
        $year = date('Y', $timestamp);

        $imbgTgl = strtotime($data['imbgTanggal']);

        $dayImb = date('d', $imbgTgl);
        $monthImb = $months[date('m', $imbgTgl)];
        $yearImb = date('Y', $imbgTgl);

        $registerTgl = strtotime($data['registerTanggal']);

        $dayRegister = date('d', $registerTgl);
        $monthRegister = $months[date('m', $registerTgl)];
        $yearRegister = date('Y', $registerTgl);

        $tahun = $data['tahun'];
        $nomorSurat = $data['nomorSurat'];
        $tanggalSurat = "$day $month $year";
        $imbgTanggalConvert = "$dayImb $monthImb $yearImb";
        $registerTanggalConvert = "$dayRegister $monthRegister $yearRegister";

        $tahun = $data['tahun'];
        $nomorSurat = $data['nomorSurat'];
        $tanggalSurat = "$day $month $year";
        $lampiran = $data['lampiran'];
        $sifat = $data['sifat'];
        $perihal = $data['perihal'];
        $pemohon = [
            'tanggal' => $data['permohonanTanggal'],
            'nama' => $data['nama'],
            'bertindak_atas_nama' => $data['bertindak_atas_nama'],
            'alamat' => $data['alamat'],
            'sapaanPemohon' => $data['sapaanPemohon'],
            'provinsiPemohon' => $provinsiPemohon,
            'kabupatenPemohon' => $kabupatenPemohon,
            'kecamatanPemohon' => $kecamatanPemohon,
            'kelurahanPemohon' => $kelurahanPemohon,
        ];
        $referensi = [
            'font_surat' => $data['font_surat'],

            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'tujuan' => $data['tujuanSurat'],
            'jenisKegiatan' => $data['jenisKegiatan'],
            'registerNomor' => $data['registerNomor'],
            'registerTanggal' => $data['registerTanggal'],
            'imbgNomor' => $data['imbgNomor'],
            'imbgTanggal' => $data['imbgTanggal'],
            'imbgTanggalConvert' => $imbgTanggalConvert,
            'registerTanggalConvert' => $registerTanggalConvert,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'kabupaten-terdahulu' => $data['kabupaten-terdahulu'],
            'kecamatan-terdahulu' => $data['kecamatan-terdahulu'],
            'kelurahan-terdahulu' => $data['kelurahan-terdahulu'],

        ];
        $penandatangan = [
            'kepalaDinas' => $kepalaDinas,
            'jabatan' => $data['jabatan'],
            'nip' => $nip,
            'pangkat' => $data['pangkat'],
        ];
        $keterangan = $data['ket'];

        // Ambil detail data IMBG
        $details = $data['details'];
        $details2 = $data['details2'];
        dd('data 1 dan 2', $details, $details2);
        // Load template view dan kirim data
        if ($jenisSurat == 'format-1') {
            $html = view('surat.format-1', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            Storage::put('public/surat/' . $namaFile, $dompdf->output());



            // Simpan atau kirimkan sebagai respons
            return response()->json(['status' => 'success', 'message' => 'Surat berhasil dibuat', 'file' => $namaFile]);

        } else if ($jenisSurat == 'format-2') {
            $html = view('surat.format-2', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            Storage::put('public/surat/' . $namaFile, $dompdf->output());


            // Simpan atau kirimkan sebagai respons
            // return $dompdf->stream('surat.pdf', ['Attachment' => true]);

            return response()->json(['status' => 'success', 'message' => 'Surat berhasil dibuat', 'file' => $namaFile]);
        } else if ($jenisSurat == 'format-3'){
            $html = view('surat.format-3', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            Storage::put('public/surat/' . $namaFile, $dompdf->output());

            // Simpan atau kirimkan sebagai respons
            return response()->json(['status' => 'success', 'message' => 'Surat berhasil dibuat', 'file' => $namaFile]);

        } else {
            $html = view('surat.format-4', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            Storage::put('public/surat/' . $namaFile, $dompdf->output());

            // Simpan atau kirimkan sebagai respons
            return response()->json(['status' => 'success', 'message' => 'Surat berhasil dibuat', 'file' => $namaFile]);

        }
    }


    public function updateNomorSurat(Request $request, $id)
    {
        // Validasi input nomorSurat
        $request->validate([
            'nomorSurat' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $nomorSuratBaru = $data['nomorSurat'];

        // Perbarui nomorSurat di tabel surat
        $updated = \DB::table('surat')->where('id', $id)->update([
            'nomorSurat' => $nomorSuratBaru,
        ]);

        if ($updated) {
            return response()->json(['status' => 'success', 'message' => 'Nomor Surat berhasil diperbarui.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui Nomor Surat.'], 500);
        }
    }


    public function update(Request $request, $id)
    {

        // Pastikan ID adalah integer
        $id = (int) $id;

        // Cek apakah ID valid
        $surat = \DB::table('surat')->where('id', $id)->first();
        if (!$surat) {
            return response()->json(['status' => 'error', 'message' => 'Invalid ID.'], 404);
        }
        // Validasi ID
        if (!is_numeric($id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid ID.'], 400);
        }


        if ($request->input('jenisSurat') == 'format-2') {
            $validated['details'] = 'required';
            $validated['details2'] = 'nullable';
        } elseif ($request->input('jenisSurat') == 'format-3') {
            $validated['details'] = 'required';
            $validated['details2'] = 'required';
        } elseif (in_array($request->input('jenisSurat'), ['format-1', 'format-4'])) {
            $validated['details'] = 'nullable';
            $validated['details2'] = 'nullable';
        }

        // Validasi input data
        $validated = $request->validate([
            'jenisSurat' => 'required|string|max:255',
            'bertindak_atas_nama' => 'required|string|max:255',
            'izin_mendirikan_bangunan_atas_nama' => 'required',
            'lokasi' => 'nullable|string|max:255',
            'tahun' => 'required|integer',
            'nomorSurat' => 'required|string|max:255',
            'tanggalSurat' => 'required|date',
            'lampiran' => 'nullable|string|max:255',
            'sifat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'tujuanSurat' => 'nullable|string|max:255',
            'jenisKegiatan' => 'nullable|string|max:255',
            'permohonanTanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kepalaDinas' => 'required|string|max:255',
            'ket' => 'nullable|array',
            'details' => 'nullable|array',
            'details2' => 'nullable|array',
            'registerNomor' => 'required',
            'imbgNomor' => 'required',
            'imbgTanggal' => 'required',
            'registerTanggal' => 'required',
            'provinsiPemohon' => 'required',
            'kabupatenPemohon' => 'required',
            'kecamatanPemohon' => 'required',
            'kelurahanPemohon' => 'required',
            'jabatan' => 'required',
            'font_surat' => 'required',
            'pangkat' => 'required',

        ]);

        try {
            // Format nama file baru
            $namaFile = 'surat-' . $validated['nomorSurat'] . uniqid() . '.pdf';

            // Perbarui data di tabel surat
            $updated = \DB::table('surat')->where('id', $id)->update([
                'jenisSurat' => $validated['jenisSurat'],
                'tahun' => $validated['tahun'],
                'nomorSurat' => $validated['nomorSurat'],
                'tanggalSurat' => $validated['tanggalSurat'],
                'lampiran' => $validated['lampiran'] ?? '-',
                'sifat' => $validated['sifat'],
                'perihal' => $validated['perihal'],
                'registerNomor' => $validated['registerNomor'],
                'imbgNomor' => $validated['imbgNomor'],
                'imbgTanggal' => $validated['imbgTanggal'],
                'registerTanggal' => $validated['registerTanggal'],
                'permohonanTanggal' => $validated['permohonanTanggal'],
                'nama' => $validated['nama'],
                'bertindak_atas_nama' => $validated['bertindak_atas_nama'],
                'alamat' => $validated['alamat'],
                'lokasi' => $validated['lokasi'],
                'provinsi' => $validated['provinsi'],
                'kabupaten' => $validated['kabupaten'],
                'kecamatan' => $validated['kecamatan'],
                'tujuanSurat' => $validated['tujuanSurat'],
                'jenisKegiatan' => $validated['jenisKegiatan'],
                'izin_mendirikan_bangunan_atas_nama' => $validated['izin_mendirikan_bangunan_atas_nama'],
                'kelurahan' => $validated['kelurahan'],
                'provinsiPemohon' => $validated['provinsiPemohon'],
                'kabupatenPemohon' => $validated['kabupatenPemohon'],
                'kecamatanPemohon' => $validated['kecamatanPemohon'],
                'kelurahanPemohon' => $validated['kelurahanPemohon'],
                'kepalaDinas' => $validated['kepalaDinas'],
                'pangkat' => $validated['pangkat'],
                'jabatan' => $validated['jabatan'],
                'font_surat' => $validated['font_surat'],
                'keterangan' => json_encode($validated['ket'] ),
                'details' => json_encode($validated['details']),
                'details2' => json_encode($validated['details2']),
                'file' => $namaFile,
            ]);

            // Tampilkan response sukses
            return response()->json(['status' => 'success', 'message' => 'Surat berhasil diperbarui.', 'file' => $namaFile]);
            if ($updated) {
                return response()->json(['status' => 'success', 'message' => 'Surat berhasil diupdate', 'file' => $namaFile]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate surat.'], 500);
            }
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function preview(Request $request)
    {
        $data = $request->all();


        $jenisSurat = $data['jenisSurat'];

        list($nip, $kepalaDinas) = explode(' | ', $request->kepalaDinas);

        $details = json_encode($request->input('details'));
        $details2 = json_encode($request->input('details2'));

        // dd($details);

        // Untuk Kabupaten
        $strKabupaten = \DB::table('master_regency')
            ->where('code', $data['kabupaten'])
            ->first()->name;
        $kabupaten = str_replace('kab.', 'Kabupaten', strtolower($strKabupaten));
        $kabupaten = ucwords($kabupaten);

        // Untuk Kecamatan
        $strKecamatan = \DB::table('master_district')
            ->where('code', $data['kecamatan'])
            ->first()->name;
        $kecamatan = ucwords(strtolower($strKecamatan));

        // Untuk Kelurahan
        $strKelurahan = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahan'])
            ->first()->name;
        $kelurahan = ucwords(strtolower($strKelurahan));

        // Untuk Provinsi
        $strProvinsi = \DB::table('master_province')
            ->where('code', 32)
            ->first()->name;
        $provinsi = ucwords(strtolower($strProvinsi));

        $strKabupatenPemohon = \DB::table('master_regency')
            ->where('code', $data['kabupatenPemohon'])
            ->first()->name;
        $kabupatenPemohon = str_replace('kab.', 'Kabupaten', strtolower($strKabupatenPemohon));
        $kabupatenPemohon = ucwords($kabupatenPemohon);

        // Untuk Kecamatan
        $strKecamatanPemohon = \DB::table('master_district')
            ->where('code', $data['kecamatanPemohon'])
            ->first()->name;
        $kecamatanPemohon = ucwords(strtolower($strKecamatanPemohon));

        // Untuk Kelurahan
        $strKelurahanPemohon = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahanPemohon'])
            ->first()->name;
        $kelurahanPemohon = ucwords(strtolower($strKelurahanPemohon));

        // Untuk Provinsi
        $strProvinsiPemohon = \DB::table('master_province')
            ->where('code', $data['provinsiPemohon'])
            ->first()->name;

        $provinsiPemohon = ucwords(strtolower($strProvinsiPemohon));



        $timestamp = strtotime($data['permohonanTanggal']);

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $day = date('d', $timestamp);
        $month = $months[date('m', $timestamp)];
        $year = date('Y', $timestamp);

        $imbgTgl = strtotime($data['imbgTanggal']);

        $dayImb = date('d', $imbgTgl);
        $monthImb = $months[date('m', $imbgTgl)];
        $yearImb = date('Y', $imbgTgl);

        $registerTgl = strtotime($data['registerTanggal']);

        $dayRegister = date('d', $registerTgl);
        $monthRegister = $months[date('m', $registerTgl)];
        $yearRegister = date('Y', $registerTgl);

        $tahun = $data['tahun'];
        $nomorSurat = $data['nomorSurat'];
        $tanggalSurat = "$day $month $year";
        // $imbgTanggalConvert = "$dayImb $monthImb $yearImb";
        // $registerTanggalConvert = "$dayRegister $monthRegister $yearRegister";
        $imbgTanggalConvert = ($dayImb == '01' && $monthImb == 'Januari') ? '-' : "$dayImb $monthImb $yearImb";
        $registerTanggalConvert = ($dayRegister == '01' && $monthRegister == 'Januari') ? '-' : "$dayRegister $monthRegister $yearRegister";
        $lampiran = $data['lampiran'];
        $sifat = $data['sifat'];
        $perihal = $data['perihal'];
        $pemohon = [
            'tanggal' => $data['permohonanTanggal'],
            'nama' => $data['nama'],
            'bertindak_atas_nama' => $data['bertindak_atas_nama'],
            'alamat' => $data['alamat'],
            'sapaanPemohon' => $data['sapaanPemohon'],

            'provinsiPemohon' => $provinsiPemohon,
            'kabupatenPemohon' => $kabupatenPemohon,
            'kecamatanPemohon' => $kecamatanPemohon,
            'kelurahanPemohon' => $kelurahanPemohon,
        ];
        $referensi = [
            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'font_surat' => $data['font_surat'],
            'jenisKegiatan' => $data['jenisKegiatan'],
            'tujuan' => $data['tujuanSurat'],
            'registerNomor' => $data['registerNomor'],
            'registerTanggal' => $data['registerTanggal'],
            'imbgNomor' => $data['imbgNomor'],
            'imbgTanggal' => $data['imbgTanggal'],
            'imbgTanggalConvert' => $imbgTanggalConvert,
            'registerTanggalConvert' => $registerTanggalConvert,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'kabupaten-terdahulu' => $data['kabupaten-terdahulu'],
            'kecamatan-terdahulu' => $data['kecamatan-terdahulu'],
            'kelurahan-terdahulu' => $data['kelurahan-terdahulu'],

        ];
        $penandatangan = [
            'kepalaDinas' => $kepalaDinas,
            'jabatan' => $data['jabatan'],
            'nip' => $nip,
            'pangkat' => $data['pangkat'],
        ];
        $keterangan = $data['ket'];


        // // Ambil detail data IMBG
        // $details = $data['details'];
        // $details2 = $data['details2'];
        // dd('data details 1',$details);
        // dd('data details 2',$details2);

        // Load template view dan kirim data
        if ($jenisSurat == 'format-1') {
            return view('surat.format-1', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            $html = view('surat.format-1', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else if ($jenisSurat == 'format-2') {
            return view('surat.format-2', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details'
            ))->render();

            $html = view('surat.format-2', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else if ($jenisSurat == 'format-3') {

            return view('surat.format-3', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            $html = view('surat.format-3', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else {
            return view('surat.format-4', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            $html = view('surat.format-4', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ));

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        }
    }

    public function LihatTableIndex($id)
    {
        $data = \DB::table('surat')
            ->select('id',
                    'jenisSurat',
                    'tahun',
                    'nomorSurat',
                    'tanggalSurat',
                    'lampiran',
                    'sifat',
                    'perihal',
                    'permohonanTanggal',
                    'nama',
                    'bertindak_atas_nama',
                    'alamat',
                    'izin_mendirikan_bangunan_atas_nama',
                    'lokasi',
                    'tujuanSurat',
                    'jenisKegiatan',
                    'registerNomor',
                    'registerTanggal',
                    'imbgNomor',
                    'imbgTanggal',
                    'provinsi',
                    'kabupaten',
                    'kecamatan',
                    'kelurahan',
                    'provinsi_terdahulu',
                    'kabupaten_terdahulu',
                    'kecamatan_terdahulu',
                    'kelurahan_terdahulu',
                    'provinsiPemohon',
                    'kabupatenPemohon',
                    'kecamatanPemohon',
                    'kelurahanPemohon',
                    'jabatan',
                    'kepalaDinas',
                    'pangkat',
                    'keterangan',
                    'details',
                    'details2',
                    'file',
                    'upload',
                    'created_at',
                    'updated_at',
                    'sapaanPemohon',
                    'font_surat') // Ensure 'tahun' is selected
            ->where('id', $id)
            ->first();

        if (!$data) {
            return redirect()->route('surat.index')->with('error', 'Data tidak ditemukan.');
        }

        // Konversi data menjadi array
        $data = (array) $data;

        // Pastikan $details dan $details2 adalah array atau objek
        $details = json_decode($data['details'], true); // Ubah JSON string menjadi array jika perlu
        $details2 = isset($data['details2']) ? json_decode($data['details2'], true) : null;

        // Cek tipe data
        if (!is_array($details)) {
            // Jika details bukan array, beri pesan error atau penanganan lebih lanjut
            return response()->view('error', ['message' => 'Data details tidak valid']);
        }

        // Tampilkan view sesuai dengan jenisSurat
        switch ($data['jenisSurat']) {
            case 'format-2':
                return response()->view('surat.preview-2', compact('details'))
                                ->header('Content-Type', 'text/html');
            case 'format-3':
                return response()->view('surat.preview-3', compact('details', 'details2'))
                                ->header('Content-Type', 'text/html');
            default:
                return response()->view('surat.preview-2', compact('details'))
                                ->header('Content-Type', 'text/html');
        }
    }



    // untuk preview table data imbg
    public function previewTable(Request $request)
    {
        $data = $request->all();

        // Dump data untuk debug
        // dd($data);

        $jenisSurat = $data['jenisSurat'];

        // Ambil data details dan details2
        $details = $data['details'];
        $details2 = $data['details2'];

        // Pastikan server mengembalikan HTML
        if ($jenisSurat == 'format-2') {
            return response()->view('surat.preview-2', compact('details'))
                             ->header('Content-Type', 'text/html');
        } else if ($jenisSurat == 'format-3') {
            return response()->view('surat.preview-3', compact('details', 'details2'))
                             ->header('Content-Type', 'text/html');
        }
        // di controller
        return response()->view('surat.preview-2', compact('details'))->header('Content-Type', 'text/html');

    }


    public function upload(Request $request, $id)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $scanSuratPath = $request->file('file')->store('file-surat', 'public');


        \DB::table('surat')
            ->where('id', $id)
            ->update(['upload' => $scanSuratPath]);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Surat berhasil diupload']);
    }

    public function updateNomor(Request $request, $id)
    {

        \DB::table('surat')
            ->where('id', $id)
            ->update(['nomorSurat' => $request->nomor_surat]);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Nomor surat berhasil diupdate']);
    }

    public function lihatSurat($id)
    {
        // $surat = \DB::table('surat')->where('id', $id)->first()->upload;


        // if ($surat == null) {
        //     $surat = \DB::table('surat')->where('id', $id)->first()->file;

        //     return response()->file(storage_path('app/public/surat/' . $surat));

        // } else {
        //     return response()->file(storage_path('app/' . $surat));

        // }


        $data = \DB::table('surat')->where('id', $id)->first();

        $data = (array) $data;

        $jenisSurat = $data['jenisSurat'];

        list($nip, $kepalaDinas) = explode(' | ', $data['kepalaDinas']);

        $details = json_decode($data['details'],true);
        $details2 = json_decode($data['details2'],true);


        // Untuk Kabupaten
        $strKabupaten = \DB::table('master_regency')
            ->where('code', $data['kabupaten'])
            ->first()->name;
        $kabupaten = str_replace('kab.', 'Kabupaten', strtolower($strKabupaten));
        $kabupaten = ucwords($kabupaten);

        // Untuk Kecamatan
        $strKecamatan = \DB::table('master_district')
            ->where('code', $data['kecamatan'])
            ->first()->name;
        $kecamatan = ucwords(strtolower($strKecamatan));

        // Untuk Kelurahan
        $strKelurahan = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahan'])
            ->first()->name;
        $kelurahan = ucwords(strtolower($strKelurahan));

        // Untuk Provinsi
        $strProvinsi = \DB::table('master_province')
            ->where('code', 32)
            ->first()->name;
        $provinsi = ucwords(strtolower($strProvinsi));

        $strKabupatenPemohon = \DB::table('master_regency')
            ->where('code', $data['kabupatenPemohon'])
            ->first()->name;
        $kabupatenPemohon = str_replace('kab.', 'Kabupaten', strtolower($strKabupatenPemohon));
        $kabupatenPemohon = ucwords($kabupatenPemohon);

        // Untuk Kecamatan
        $strKecamatanPemohon = \DB::table('master_district')
            ->where('code', $data['kecamatanPemohon'])
            ->first()->name;
        $kecamatanPemohon = ucwords(strtolower($strKecamatanPemohon));

        // Untuk Kelurahan
        $strKelurahanPemohon = \DB::table('master_subdistrict')
            ->where('code', $data['kelurahanPemohon'])
            ->first()->name;
        $kelurahanPemohon = ucwords(strtolower($strKelurahanPemohon));

        // Untuk Provinsi
        $strProvinsiPemohon = \DB::table('master_province')
            ->where('code', $data['provinsiPemohon'])
            ->first()->name;

        $provinsiPemohon = ucwords(strtolower($strProvinsiPemohon));



        $timestamp = strtotime($data['permohonanTanggal']);

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $day = date('d', $timestamp);
        $month = $months[date('m', $timestamp)];
        $year = date('Y', $timestamp);

        $imbgTgl = strtotime($data['imbgTanggal']);

        $dayImb = date('d', $imbgTgl);
        $monthImb = $months[date('m', $imbgTgl)];
        $yearImb = date('Y', $imbgTgl);

        $registerTgl = strtotime($data['registerTanggal']);

        $dayRegister = date('d', $registerTgl);
        $monthRegister = $months[date('m', $registerTgl)];
        $yearRegister = date('Y', $registerTgl);

        $tahun = $data['tahun'];
        $nomorSurat = $data['nomorSurat'];
        $tanggalSurat = "$day $month $year";
        // $imbgTanggalConvert = "$dayImb $monthImb $yearImb";
        // $registerTanggalConvert = "$dayRegister $monthRegister $yearRegister";
        $imbgTanggalConvert = ($dayImb == '01' && $monthImb == 'Januari') ? '-' : "$dayImb $monthImb $yearImb";
        $registerTanggalConvert = ($dayRegister == '01' && $monthRegister == 'Januari') ? '-' : "$dayRegister $monthRegister $yearRegister";
        $lampiran = $data['lampiran'];
        $sifat = $data['sifat'];
        $perihal = $data['perihal'];
        $pemohon = [
            'tanggal' => $data['permohonanTanggal'],
            'nama' => $data['nama'],
            'bertindak_atas_nama' => $data['bertindak_atas_nama'],
            'alamat' => $data['alamat'],
            'sapaanPemohon' => $data['sapaanPemohon'],

            'provinsiPemohon' => $provinsiPemohon,
            'kabupatenPemohon' => $kabupatenPemohon,
            'kecamatanPemohon' => $kecamatanPemohon,
            'kelurahanPemohon' => $kelurahanPemohon,
        ];
        $referensi = [
            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'font_surat' => $data['font_surat'],
            'jenisKegiatan' => $data['jenisKegiatan'],
            'tujuan' => $data['tujuanSurat'],
            'registerNomor' => $data['registerNomor'],
            'registerTanggal' => $data['registerTanggal'],
            'imbgNomor' => $data['imbgNomor'],
            'imbgTanggal' => $data['imbgTanggal'],
            'imbgTanggalConvert' => $imbgTanggalConvert,
            'registerTanggalConvert' => $registerTanggalConvert,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'kabupaten-terdahulu' => $data['kabupaten_terdahulu'],
            'kecamatan-terdahulu' => $data['kecamatan_terdahulu'],
            'kelurahan-terdahulu' => $data['kelurahan_terdahulu'],

        ];
        $penandatangan = [
            'kepalaDinas' => $kepalaDinas,
            'jabatan' => $data['jabatan'],
            'nip' => $nip,
            'pangkat' => $data['pangkat'],
        ];
        $keterangan = json_decode($data['keterangan'],true);


        // Ambil detail data IMBG
        $details = $details;
        $details2 = $details2;

        // Load template view dan kirim data
        if ($jenisSurat == 'format-1') {
            return view('surat.format-1', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            $html = view('surat.format-1', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else if ($jenisSurat == 'format-2') {
            return view('surat.format-2', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details'
            ))->render();

            $html = view('surat.format-2', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else if ($jenisSurat == 'format-3') {

            return view('surat.format-3', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            $html = view('surat.format-3', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan',
                'details',
                'details2'
            ))->render();

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else {
            return view('surat.format-4', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ))->render();

            $html = view('surat.format-4', compact(
                'jenisSurat',
                'tahun',
                'nomorSurat',
                'tanggalSurat',
                'lampiran',
                'sifat',
                'perihal',
                'pemohon',
                'referensi',
                'penandatangan',
                'keterangan'
            ));

            // Setup Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Opsional) Pengaturan tambahan
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            // Render PDF
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        }
    }

    public function destroy($id)
    {
        \DB::table('surat')->where('id', $id)->delete();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Surat berhasil dihapus']);
    }

    public function download($id)
    {
        $surat = \DB::table('surat')->where('id', $id)->first()->upload;
        if($surat == null) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Surat belum diupload']);
        }
        return response()->download(storage_path('app/public/' . $surat));
    }


    public function getNomorSuratPemohon(Request $request)
    {
        \Log::info('getNomorSuratPemohon called with ID: ' . $request->id);
        dd($request->all()); // Debugging: Hentikan eksekusi dan tampilkan data request

        // Validasi input
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Ambil data berdasarkan ID
        $data = \DB::table('surat')->where('id', $request->id);

        // Cek apakah data ditemukan
        if ($data->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        // Kembalikan response JSON
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function copyData(Request $request)
    {
        $id = $request->input('nomorSK-Pemohon');

        // Ambil data asli berdasarkan ID
        $originalData = \DB::table('surat')
        ->where('id', $id)->first();

        if ($originalData) {
            // Ubah data menjadi array dan hapus ID untuk duplikasi
            $newData = (array) $originalData;
            unset($newData['id']);
            $newData['nomorSurat'] .= 'copy';
            $newData['created_at'] = now();
            $newData['updated_at'] = now();

            // Insert data baru ke database
            $newId = \DB::table('surat')->insertGetId($newData);

            return redirect()->back()->with(['status' => 'success', 'message' => 'Surat berhasil disalin.']);
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    // Filter Surat Untuk tahun pada copyData
    // public function filterSurat(Request $request)
    // {
    //     $request->validate([
    //         'tahun' => 'required|integer',
    //     ]);

    //     $tahun = $request->input('tahun');

    //     // Menggunakan cursor untuk membaca data dalam bentuk generator
    //     $filteredSurat = \DB::table('surat')
    //         ->select('id', 'nomorSurat', 'nama', 'tahun')
    //         ->where('tahun', $tahun)
    //         ->orderByDesc('id') // Urutkan secara descending
    //         ->cursor(); // Mengembalikan generator

    //     // Ubah generator menjadi array JSON
    //     $data = [];
    //     foreach ($filteredSurat as $item) {
    //         $data[] = $item;
    //     }

    //     return response()->json($data);
    // }

   // Filter Surat Untuk tahun pada copyData
    public function filterSurat(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
        ]);

        $tahun = $request->input('tahun');

        // Cek cache terlebih dahulu
        $cacheKey = "filtered_surat_{$tahun}";
        $filteredSurat = Cache::get($cacheKey);

        if (!$filteredSurat) {
            $filteredSurat = \DB::table('surat')
                ->select('id', 'nomorSurat', 'nama', 'tahun')
                ->where('tahun', $tahun)
                ->orderByDesc('id')
                ->get();

            // Simpan hasil ke cache
            Cache::put($cacheKey, $filteredSurat, 60); // Cache selama 60 menit
        }

        return response()->json($filteredSurat);
    }




    public function LihatIndex(Request $request){
        $query = \DB::table('surat')
            ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code') // Join ke master_regency
            ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join ke master_district
            ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join ke master_subdistrict
            ->select(
                'surat.id',
                'surat.jenisSurat',
                'surat.tahun',
                'surat.nomorSurat',
                'surat.tanggalSurat',
                'surat.lampiran',
                'surat.sifat',
                'surat.perihal',
                'surat.permohonanTanggal',
                'surat.nama',
                'surat.bertindak_atas_nama',
                'surat.alamat',
                'surat.izin_mendirikan_bangunan_atas_nama',
                'surat.lokasi',
                'surat.tujuanSurat',
                'surat.jenisKegiatan',
                'surat.registerNomor',
                'surat.registerTanggal',
                'surat.imbgNomor',
                'surat.imbgTanggal',
                'surat.provinsi',
                'surat.kabupaten',
                'surat.kecamatan',
                'surat.kelurahan',
                'surat.provinsi_terdahulu',
                'surat.kabupaten_terdahulu',
                'surat.kecamatan_terdahulu',
                'surat.kelurahan_terdahulu',
                'surat.provinsiPemohon',
                'surat.kabupatenPemohon',
                'surat.kecamatanPemohon',
                'surat.kelurahanPemohon',
                'surat.jabatan',
                'surat.kepalaDinas',
                'surat.pangkat',
                'surat.keterangan',
                'surat.details',
                'surat.details2',
                'surat.file',
                'surat.upload',
                'surat.created_at',
                'surat.updated_at',
                'surat.sapaanPemohon',
                'surat.font_surat',
                    'master_regency.name as nama_kabupaten',
                    'master_district.name as nama_kecamatan',
                    'master_subdistrict.name as nama_kelurahan'
            )
            ->orderBy('id', 'desc');

        $data = $query;

        if ($request->ajax()) {
            return Datatables::of($query)
                ->editColumn('nama_kecamatan', function ($row) {
                    return ucwords(strtolower($row->nama_kecamatan));
                })
                ->editColumn('nama_kecamatan', function ($row) {
                    return ucwords(strtolower($row->nama_kecamatan));
                })
                ->editColumn('nama_kelurahan', function ($row) {
                    return ucwords(strtolower($row->nama_kelurahan));
                })
                // ->rawColumns(['action', 'sudah_upload'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('surat.preview-index',['data'=>$data->get()]);
    }


    // public function cetakHalaman(Request $request)
    // {
    //     // Inisialisasi variabel row number
    //     // \DB::statement(\DB::raw('SET @row_number = 0'));
    //     $perPage = $request->input('perPage', 10); // Default 10
    //     $currentPage = $request->input('page', 1);
    //     $offset = ($currentPage - 1) * $perPage;
    //     $query = \DB::table('surat')
    //         ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code')
    //         ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code')
    //         ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code')
    //         ->select(
    //             'surat.*',
    //             'master_regency.name as nama_kabupaten',
    //             'master_district.name as nama_kecamatan',
    //             'master_subdistrict.name as nama_kelurahan',
    //             // \DB::raw('@row_number := @row_number + 1 as row_number')
    //         )
    //         ->orderBy('id', 'desc')
    //         ->skip($offset)
    //         ->take($perPage)
    //         ->get();

    //     // Filter berdasarkan input dari request
    //     if ($request->has('nomor_surat') && $request->input('nomor_surat') != null) {
    //         $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
    //     }

    //     if ($request->has('nama_pemohon') && $request->input('nama_pemohon') != null) {
    //         $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
    //     }

    //     if ($request->has('nomor_imbg')  && $request->input('nomor_imbg') != null) {
    //         $query->where('surat.imbgNomor', 'like', '%' . $request->input('nomor_imbg') . '%');
    //     }

    //     if ($request->has('lokasi_bangunan')  && $request->input('lokasi_bangunan') != null) {
    //         $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
    //     }
    //     if ($request->has('kabupaten_pemohon') && $request->input('kabupaten_pemohon') != null) {
    //         $query->where('master_regency.name', 'like', '%' . $request->input('kabupaten_pemohon') . '%');
    //     }

    //     if ($request->has('kecamatan_pemohon') && $request->input('kecamatan_pemohon') != null) {
    //         $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
    //     }

    //     if ($request->has('kelurahan_pemohon')  && $request->input('kelurahan_pemohon') != null) {
    //         $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
    //     }


    //     return view('surat.cetak-halaman', compact('query'));
    // }

    public function cetakHalaman(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default 10
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        // Inisialisasi query
        $query = \DB::table('surat')
            ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code')
            ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code')
            ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code')
            ->select(
                'surat.id',
                    'surat.tahun',
                    'surat.nomorSurat',
                    'surat.nama',
                    'surat.imbgNomor',
                    'surat.lokasi',
                    'surat.jenisSurat',
                    'surat.tanggalSurat',
                    'surat.lampiran',
                    'surat.sifat',
                    'surat.perihal',
                    'surat.permohonanTanggal',
                    'surat.bertindak_atas_nama',
                    'surat.alamat',
                    'surat.izin_mendirikan_bangunan_atas_nama',
                    'surat.registerNomor',
                    'surat.registerTanggal',
                    'surat.imbgTanggal',
                    'surat.sapaanPemohon',
                    'surat.provinsiPemohon',
                    'surat.kabupatenPemohon',
                    'surat.kecamatanPemohon',
                    'surat.kelurahanPemohon',
                    'surat.jabatan',
                    'surat.font_surat',
                    'surat.kabupaten',
                    'surat.kecamatan',
                    'surat.kelurahan',
                    'surat.kepalaDinas',
                    'surat.pangkat',
                    'surat.keterangan',
                    'surat.details',
                    'surat.details2',
                    'surat.file',
                    'surat.upload',
                    'surat.created_at',
                    'surat.updated_at',
                    'surat.provinsi_terdahulu',
                    'surat.kabupaten_terdahulu',
                    'surat.kecamatan_terdahulu',
                    'surat.kelurahan_terdahulu',
                    'surat.tujuanSurat',
                    'surat.jenisKegiatan',
                'master_regency.name as nama_kabupaten',
                'master_district.name as nama_kecamatan',
                'master_subdistrict.name as nama_kelurahan'
            )
            ->orderBy('surat.id', 'desc');

        // Terapkan filter berdasarkan input
        if ($request->anyFilled('nomor_surat')) {
            $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
        }

        if ($request->filled('nama_pemohon')) {
            $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
        }

        if ($request->filled('nomor_imbg')) {
            $query->where('surat.imbgNomor', 'like', '%' . $request->input('nomor_imbg') . '%');
        }

        if ($request->filled('lokasi_bangunan')) {
            $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
        }

        if ($request->filled('kabupaten_pemohon')) {
            $query->where('master_regency.name', 'like', '%' . $request->input('kabupaten_pemohon') . '%');
        }

        if ($request->filled('kecamatan_pemohon')) {
            $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
        }

        if ($request->filled('kelurahan_pemohon')) {
            $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
        }

        // Tambahkan pagination
        $data = $query->skip($offset)->take($perPage);

        // Kirim data ke view
        return view('surat.cetak-halaman',['data'=>$data->get()]);
    }

    public function cariSurat(Request $request)
    {

        // Query dengan join untuk mendapatkan nama kecamatan dan kelurahan
        $query = \DB::table('surat')
            ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join ke master_district
            ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join ke master_subdistrict
            ->select(
                'surat.id',
                    'surat.tahun',
                    'surat.nomorSurat',
                    'surat.nama',
                    'surat.imbgNomor',
                    'surat.lokasi',
                    'surat.jenisSurat',
                    'surat.tanggalSurat',
                    'surat.lampiran',
                    'surat.sifat',
                    'surat.perihal',
                    'surat.permohonanTanggal',
                    'surat.bertindak_atas_nama',
                    'surat.alamat',
                    'surat.izin_mendirikan_bangunan_atas_nama',
                    'surat.registerNomor',
                    'surat.registerTanggal',
                    'surat.imbgTanggal',
                    'surat.sapaanPemohon',
                    'surat.provinsiPemohon',
                    'surat.kabupatenPemohon',
                    'surat.kecamatanPemohon',
                    'surat.kelurahanPemohon',
                    'surat.jabatan',
                    'surat.font_surat',
                    'surat.kabupaten',
                    'surat.kecamatan',
                    'surat.kelurahan',
                    'surat.kepalaDinas',
                    'surat.pangkat',
                    'surat.keterangan',
                    'surat.details',
                    'surat.details2',
                    'surat.file',
                    'surat.upload',
                    'surat.created_at',
                    'surat.updated_at',
                    'surat.provinsi_terdahulu',
                    'surat.kabupaten_terdahulu',
                    'surat.kecamatan_terdahulu',
                    'surat.kelurahan_terdahulu',
                    'surat.tujuanSurat',
                    'surat.jenisKegiatan',
                'master_district.name as nama_kecamatan',
                'master_subdistrict.name as nama_kelurahan'
            );

        // Filter berdasarkan input dari request
        if ($request->has('nomor_surat')) {
            $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
        }

        if ($request->has('nama_pemohon')) {
            $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
        }

        if ($request->has('lokasi_bangunan')) {
            $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
        }

        if ($request->has('kecamatan_pemohon')) {
            $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
        }

        if ($request->has('kelurahan_pemohon')) {
            $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
        }


        // Ambil data
        $data = $query->orderBy('id', 'desc');

        // Generate Datatable response
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $showLihatTable = !in_array($row->jenisSurat, ['format-1', 'format-4']); // Menyembunyikan jika format-1 atau format-4

                $actions = '
                    <div class="d-flex " style="gap:8px;">
                        <div class="d-flex align-items-center" style="gap:5px; width: 50%;">
                            <a href="' . route('surat.lihat', $row->id) . '" onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;" class="btn btn-primary btn-sm" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>';

                // Tombol Lihat Table hanya akan muncul jika jenisSurat bukan format-1 dan format-4
                if ($showLihatTable) {
                    $actions .= '
                        <div class="d-flex align-items-center" style="gap:5px; width: 50%;">
                            <a href="' . route('surat.lihatTable', $row->id) . '" onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;" class="btn btn-primary btn-sm" title="Lihat Table">
                                <i class="fas fa-table"></i>
                            </a>
                        </div>';
                }

                $actions .= '</div>';
                return $actions;
            })
            ->addColumn('sudah_upload', function ($row) {
                return $row->upload ?
                    '<span class="badge badge-success">Sudah</span>' :
                    '<span class="badge badge-danger">Belum</span>';
            })
            ->editColumn('nama_kecamatan', function ($row) {
                return ucwords(strtolower($row->nama_kecamatan));
            })
            ->editColumn('nama_kelurahan', function ($row) {
                return ucwords(strtolower($row->nama_kelurahan));
            })
            ->rawColumns(['action', 'sudah_upload'])
            ->make(true);
    }


}
