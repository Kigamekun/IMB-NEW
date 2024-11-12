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
                    if ($row->upload) {
                        return '
                        <div class="d-flex" style="gap:10px;">

                        <a  href="' . route('surat.download', $row->id) . '" class="btn btn-success btn-sm">Download</a>

                            <button type="button"
                            data-url="' . route('surat.upload', $row->id) . '"
                            class="ml-2 btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#uploadSuratModal">
                                Upload Surat
                            </button>
                            <a target="_blank" href="' . route('surat.lihat', $row->id) . '" class="btn btn-primary btn-sm">Lihat</a>
                            <form action="' . route('surat.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">Hapus</button>
                            </form>
                        </div>';
                    } else {
                        return '
                        <div class="d-flex" style="gap:10px;">
                        <a  href="' . route('surat.download', $row->id) . '" class="btn btn-success btn-sm">Download</a>

                            <button type="button"
                            data-url="' . route('surat.upload', $row->id) . '"
                            class="ml-2 btn btn-info btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#uploadSuratModal">
                                Upload Surat
                            </button>
                            <form action="' . route('surat.destroy', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(event)">Hapus</button>
                            </form>
                        </div>';
                    }
                })
                ->addColumn('sudah_upload', function ($row) {
                    if ($row->upload) {
                        return '<span class="badge badge-success">Sudah</span>';
                    } else {
                        return '<span class="badge badge-danger">Belum</span>';
                    }
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
            'tujuanSurat' => $request->input('tujuanSurat'),
            'registerNomor' => $request->input('registerNomor'),
            'registerTanggal' => $request->input('registerTanggal'),
            'imbgNomor' => $request->input('imbgNomor'),
            'imbgTanggal' => $request->input('imbgTanggal'),

            'provinsiPemohon' => $request->input('provinsiPemohon'),
            'kabupatenPemohon' => $request->input('kabupatenPemohon'),
            'kecamatanPemohon' => $request->input('kecamatanPemohon'),
            'kelurahanPemohon' => $request->input('kelurahanPemohon'),
            'jabatan' => $request->input('jabatan'),

            'provinsi' => $request->input('provinsi'),
            'kabupaten' => $request->input('kabupaten'),
            'kecamatan' => $request->input('kecamatan'),
            'kelurahan' => $request->input('kelurahan'),
            'provinsi_terdahulu' => $request->input('provinsi-terdahulu'),
            'kabupaten_terdahulu' => $request->input('kabupaten-terdahulu'),
            'kecamatan_terdahulu' => $request->input('kecamatan-terdahulu'),
            'kelurahan_terdahulu' => $request->input('kelurahan-terdahulu'),
            'kepalaDinas' => $request->input('kepalaDinas'),
            'pangkat' => $request->input('pangkat'),
            'keterangan' => json_encode($request->input('keterangan')),
            'details' => json_encode($request->input('details')),
            'details2' => json_encode($request->input('details2')),
            'file' => $namaFile,
        ]);


        $jenisSurat = $data['jenisSurat'];
        list($kepalaDinas, $nip) = explode(' | ', $request->kepalaDinas);

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



        $timestamp = strtotime($data['tanggalSurat']);

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
            'provinsiPemohon' => $provinsiPemohon,
            'kabupatenPemohon' => $kabupatenPemohon,
            'kecamatanPemohon' => $kecamatanPemohon,
            'kelurahanPemohon' => $kelurahanPemohon,
        ];
        $referensi = [
            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'tujuan' => $data['tujuanSurat'],
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
        $keterangan = [
            'ket1' => $data['ket1'],
            'ket2' => $data['ket2'],
            'ket3' => $data['ket3'],
        ];

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
        } else {
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

        }
    }


    public function preview(Request $request)
    {
        $data = $request->all();

        $jenisSurat = $data['jenisSurat'];

        list($kepalaDinas, $nip) = explode(' | ', $request->kepalaDinas);

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



        $timestamp = strtotime($data['tanggalSurat']);

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
            'provinsiPemohon' => $provinsiPemohon,
            'kabupatenPemohon' => $kabupatenPemohon,
            'kecamatanPemohon' => $kecamatanPemohon,
            'kelurahanPemohon' => $kelurahanPemohon,
        ];
        $referensi = [
            'izin_mendirikan_bangunan_atas_nama' => $data['izin_mendirikan_bangunan_atas_nama'],
            'lokasi' => $data['lokasi'],
            'tujuan' => $data['tujuanSurat'],
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
        $keterangan = [
            'ket1' => $data['ket1'],
            'ket2' => $data['ket2'],
            'ket3' => $data['ket3'],
        ];


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

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
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

            // Simpan atau kirimkan sebagai respons
            return $dompdf->stream('surat.pdf', ['Attachment' => false]);
        } else {
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

    public function lihatSurat($id)
    {
        $surat = \DB::table('surat')->where('id', $id)->first()->upload;
        return response()->file(storage_path('app/' . $surat));
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
