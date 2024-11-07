<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
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
                ->rawColumns(['action','sudah_upload'])
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

        // Ambil data utama
        $jenisSurat = $data['jenisSurat'];


        $details = json_encode($request->input('details'));
        $details2 = json_encode($request->input('details2'));

        // Simpan data ke database
        $surat = \DB::table('surat')->insert([
            'jenis_surat' => $request->input('jenisSurat'),
            'tahun' => $request->input('tahun'),
            'nomor_surat' => $request->input('nomorSurat'),
            'tanggal_surat' => $request->input('tanggalSurat'),
            'lampiran' => $request->input('lampiran'),
            'sifat' => $request->input('sifat'),
            'perihal' => $request->input('perihal'),
            'permohonan_tanggal' => $request->input('permohonanTanggal'),
            'nama' => $request->input('nama'),
            'provinsi' => $request->input('provinsi'),
            'kabupaten' => $request->input('kabupaten'),
            'kecamatan' => $request->input('kecamatan'),
            'alamat' => $request->input('alamat'),
            'tujuan_surat' => $request->input('tujuanSurat'),
            'register_nomor' => $request->input('registerNomor'),
            'register_tanggal' => $request->input('registerTanggal'),
            'imbg_nomor' => $request->input('imbgNomor'),
            'imbg_tanggal' => $request->input('imbgTanggal'),
            'kepala_dinas' => $request->input('kepalaDinas'),
            'nip' => $request->input('nip'),
            'pangkat' => $request->input('pangkat'),
            'ket1' => $request->input('ket1'),
            'ket2' => $request->input('ket2'),
            'ket3' => $request->input('ket3'),
            'details' => $details,
            'details2' => $details2
        ]);


        if ($jenisSurat == 'format-1') {
            $tahun = $data['tahun'];
            $nomorSurat = $data['nomorSurat'];
            $tanggalSurat = $data['tanggalSurat'];
            $lampiran = $data['lampiran'];
            $sifat = $data['sifat'];
            $perihal = $data['perihal'];
            $pemohon = [
                'tanggal' => $data['permohonanTanggal'],
                'nama' => $data['nama'],
                'provinsi' => $data['provinsi'],
                'kabupaten' => $data['kabupaten'],
                'kecamatan' => $data['kecamatan'],
                'alamat' => $data['alamat'],
            ];
            $referensi = [
                'tujuan' => $data['tujuanSurat'],
                'registerNomor' => $data['registerNomor'],
                'registerTanggal' => $data['registerTanggal'],
                'imbgNomor' => $data['imbgNomor'],
                'imbgTanggal' => $data['imbgTanggal'],
            ];
            $penandatangan = [
                'kepalaDinas' => $data['kepalaDinas'],
                'nip' => $data['nip'],
                'pangkat' => $data['pangkat'],
            ];
            $keterangan = [
                'ket1' => $data['ket1'],
                'ket2' => $data['ket2'],
                'ket3' => $data['ket3'],
            ];

            // Ambil detail data IMBG
            $details = $data['details'];

            // Load template view dan kirim data
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
                'keterangan',
                'details'
            ))->render();

            // dd($jenisSurat, $tahun, $nomorSurat, $tanggalSurat, $lampiran, $sifat, $perihal, $pemohon, $referensi, $penandatangan, $keterangan, $details);
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
            return $dompdf->stream('surat.pdf', ['Attachment' => true]);
        } else {
            $tahun = $data['tahun'];
            $nomorSurat = $data['nomorSurat'];
            $tanggalSurat = $data['tanggalSurat'];
            $lampiran = $data['lampiran'];
            $sifat = $data['sifat'];
            $perihal = $data['perihal'];
            $pemohon = [
                'tanggal' => $data['permohonanTanggal'],
                'nama' => $data['nama'],
                'provinsi' => $data['provinsi'],
                'kabupaten' => $data['kabupaten'],
                'kecamatan' => $data['kecamatan'],
                'alamat' => $data['alamat'],
            ];
            $referensi = [
                'tujuan' => $data['tujuanSurat'],
                'registerNomor' => $data['registerNomor'],
                'registerTanggal' => $data['registerTanggal'],
                'imbgNomor' => $data['imbgNomor'],
                'imbgTanggal' => $data['imbgTanggal'],
            ];
            $penandatangan = [
                'kepalaDinas' => $data['kepalaDinas'],
                'nip' => $data['nip'],
                'pangkat' => $data['pangkat'],
            ];
            $keterangan = [
                'ket1' => $data['ket1'],
                'ket2' => $data['ket2'],
                'ket3' => $data['ket3'],
            ];

            // Ambil detail data IMBG
            $details = $data['details'];

            // Load template view dan kirim data
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
                'details'
            ))->render();

            // dd($jenisSurat, $tahun, $nomorSurat, $tanggalSurat, $lampiran, $sifat, $perihal, $pemohon, $referensi, $penandatangan, $keterangan, $details);
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
            return $dompdf->stream('surat.pdf', ['Attachment' => true]);
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

}
