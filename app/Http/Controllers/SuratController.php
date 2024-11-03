<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;

class SuratController extends Controller
{
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
        return $dompdf->stream('surat.pdf', ['Attachment' => false]);
    }

}
