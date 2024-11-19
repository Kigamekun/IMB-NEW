<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

use \Yajra\DataTables\DataTables;


class SuratController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \DB::table('surat')->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) {
                    $actions = '
                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <div class="d-flex align-items-center" style="gap:5px; width: 50%;">
                                <a href="' . route('surat.download', $row->id) . '" class="btn btn-success btn-sm" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" data-url="' . route('surat.upload', $row->id) . '" class="btn btn-info btn-sm text-white" title="Upload"
                                    data-bs-toggle="modal" data-bs-target="#uploadSuratModal">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center" style="gap:5px; width: 50%;">
                                <a href="' . route('surat.lihat', $row->id) . '" onclick="window.open(this.href, \'_blank\', \'width=800,height=600\'); return false;" class="btn btn-primary btn-sm" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" data-url="' . route('surat.update_nomor', $row->id) . '" class="btn btn-warning btn-sm text-white" title="Update Nomor"
                                    data-bs-toggle="modal" data-bs-target="#updateNomorModal">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center" style="gap:5px; width: 50%;">

                                <a class="btn btn-warning btn-sm text-white"  href="'.route('surat.edit',$row->id).'">
                                    <i class="fas fa-edit"></i>

                                    </a>
                            <form action="' . route('surat.destroy', $row->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="confirmDelete(event)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>';
                    return $actions;
                })

                ->addColumn('sudah_upload', function ($row) {
                    return $row->upload ?
                        '<span class="badge badge-success">Sudah</span>' :
                        '<span class="badge badge-danger">Belum</span>';
                })
                ->rawColumns(['action', 'sudah_upload'])
                ->addIndexColumn()
                ->make(true);
        }
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

        $url = route('surat.update',['id',$data['id']]);

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

            'provinsi' => 32,
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


    public function update(Request $request,$id)
    {
        $data = $request->all();
        $namaFile = 'surat-' . $data['nomorSurat'] . uniqid() . '.pdf';

        \DB::table('surat')->where('id',$id)->update([
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

            'provinsi' => 32,
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
            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'tujuan' => $data['tujuanSurat'],
            'jenisKegiatan' => $data['jenisKegiatan'],
            'registerNomor' => $data['registerNomor'],
            'registerTanggal' => $data['registerTanggal'],
            'imbgNomor' => $data['imbgNomor'],
            'imbgTanggal' => $data['imbgTanggal'],
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

    public function preview(Request $request)
    {
        $data = $request->all();


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
        $imbgTanggalConvert = "$dayImb $monthImb $yearImb";
        $registerTanggalConvert = "$dayRegister $monthRegister $yearRegister";
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


        // Ambil detail data IMBG
        $details = $data['details'];
        $details2 = $data['details2'];

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
    public function upload(Request $request, $id)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $scanSuratPath = $request->file('file')->store('file-surat');

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
        $imbgTanggalConvert = "$dayImb $monthImb $yearImb";
        $registerTanggalConvert = "$dayRegister $monthRegister $yearRegister";
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
        $surat = \DB::table('surat')->where('id', $id)->first()->file;
        return response()->download(storage_path('app/public/surat/' . $surat));
    }

}
