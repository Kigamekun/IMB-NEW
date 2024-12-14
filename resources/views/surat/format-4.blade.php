<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Izin Mendirikan Bangunan</title>
    <style>
        @media print {
            @page {
                size: 210mm 330mm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.6;
            }

            table td {
                line-height: 1.6;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: {{ $referensi['font_surat'] }}pt !important;
            line-height: 1;
            text-align: justify;
            white-space: normal; /* Izinkan teks membungkus */

        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .content {
            margin-top: -20px;
        }

        .content p {
            margin: 5px 0;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }

        .content {}

        .content h3 {
            text-decoration: underline;
            font-weight: bolder;
        }

        .content p,
        .content ul {
            margin-left: 20px;
        }

        .content ul {
            list-style-type: none;
            padding-left: 0;
        }

        .table-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: {{ $referensi['font_surat'] }}pt !important;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 5px;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }

        .signature p {
            margin: 0;
            font-size: 0.9em;
        }

        .stamp {
            margin-top: 10px;
            text-align: center;
            font-size: 0.8em;
            color: gray;
        }

        .info-table {
            border:none;
            width: 250px;
            border-collapse: collapse;
        }


        .info-table td {
            text-align: left;
            border: none;
            padding: 4px 8px;
            vertical-align: top;
            line-height: .8;
        }

        .info-table td:first-child {
            width: 15%;
            /* Lebar kolom pertama */
            white-space: nowrap;
            /* Mencegah kolom pertama meluas */
        }

        .info-table td:nth-child(2) {
            width: 1%;
            /* Lebar kolom untuk tanda titik dua (:) */
            text-align: center;
        }

        .info-table td:last-child {
            width: 84%;
            /* Lebar kolom terakhir */
        }

        .info-tables {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .info-tables td {
            text-align: left;
            border: none;
            padding: 2px 2px 2px ;
            vertical-align: top;
            line-height: .8;
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .signature-role {
            text-align: center;
        }

        .signature-name {
            font-weight: bold;
            text-align: center;
        }

        .signature-nip {
            text-align: center;
            margin-top: 5px;
        }

        table td {
            line-height: 1 !important;
        }
    </style>
</head>

<body style="width: 700px">
    <div style="float: right;margin-right: 65px; margin-top:-25px">
        <p>Cibinong,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2024
        </p>
    </div>
    <table class="info-table " style="width:300px;">
        <tr>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $nomorSurat }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>{{ $sifat }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>{{ $lampiran }}</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td style="line-height: 16px !important; text-align:justify;">{{ $perihal }}</td>
        </tr>
    </table>
    <div style="line-height:5px;margin-left:10px">
        <p>Yth,</p>
        <p><b>{{ $pemohon['sapaanPemohon'] }} {{ $pemohon['nama'] }}</b></p>
        <p>di</p>
        @php
            // Cek apakah 'Kabupaten' ada di kabupatenPemohon
            $kabupaten = $pemohon['kabupatenPemohon'];

            // Hanya hapus 'Kabupaten' jika kabupatenPemohon tidak ada di pemohon_alamat
            if (!str_contains($pemohon['alamat'], 'Kabupaten') && str_contains($kabupaten, 'Kabupaten')) {
                $kabupaten = trim(str_replace('Kabupaten', '', $kabupaten));
            }
        @endphp

        <p>{{ $kabupaten }}</p>
    </div>
    <br>
    <div style="margin-left: 10px">
        <p style="margin-top:-5px; margin-left:0p; text-indent: 25px; line-height: 1.15 !important;">Sehubungan permohonan Surat Keterangan Izin Mendirikan Bangunan
            Gedung (IMBG) {{ $referensi['jenisKegiatan'] }}, tanggal {{ $tanggalSurat }} dari:</p>

        <table class="info-tables" style="margin-left: -3px; margin-top: -15px; width: 100%; border-collapse: collapse; ">
            <!-- Bagian Pemohon -->
            <tr style="margin-left: 100px">
                <td style="width: 250px;">Nama</td>
                <td style="width: 5px;">:</td>
                <td st><b>{{ $pemohon['nama'] }}</b></td>
            </tr>
            <tr style="margin-left: 100px">
                <td>Bertindak Atas Nama</td>
                <td>:</td>
                <td>{{ $pemohon['bertindak_atas_nama'] }}</td>
            </tr>
            <tr style="margin-left: 100px">
                <td>Alamat</td>
                <td>:</td>
                <td style=" line-height: 1.15!important;">
                    {{ $pemohon['alamat'] }}, Desa/Kelurahan
                    {{ $pemohon['kelurahanPemohon'] }}, Kecamatan
                    {{ $pemohon['kecamatanPemohon'] }}, {{ $pemohon['kabupatenPemohon'] }}
                </td>
            </tr>

            <!-- Baris Kosong untuk Pemisah -->
            <tr >
                <td colspan="3"
                    style="text-align: justify; padding-top: 10px; padding-bottom: 10px; line-height: 1.15 !important; word-wrap: break-word; white-space: normal; text-indent: 25px;">
                    Setelah dilakukan pengecekan pada buku Izin Mendirikan Bangunan yang ada pada Dinas
                    Perumahan Kawasan Permukiman dan Pertanahan Kabupaten Bogor, dengan ini dapat disampaikan:
                </td>
            </tr>

            <!-- Bagian Izin Mendirikan Bangunan -->
            <tr>
                <td style="width: 300px">Izin Mendirikan Bangunan Atas Nama</td>
                <td>:</td>
                <td  style="width: 400px"><b>{{ $referensi['izin_mendirikan_bangunan_atas_nama'] }}</b></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>:</td>
                <td style=" line-height: 1.15 !important; text-align:justify; ">
                    {{ $referensi['lokasi'] != null || $referensi['lokasi'] != '' ? $referensi['lokasi'] . ',' : '' }}
                    Desa/Kelurahan {{ $referensi['kelurahan'] }}
                    @if (isset($referensi['kelurahan-terdahulu']) && $referensi['kelurahan-terdahulu'] != null)
                        (d/h. {{ $referensi['kelurahan-terdahulu'] }}),
                    @endif
                    Kecamatan {{ $referensi['kecamatan'] }}
                    @if (isset($referensi['kecamatan-terdahulu']) && $referensi['kecamatan-terdahulu'] != null)
                        (d/h. {{ $referensi['kecamatan-terdahulu'] }}),
                    @endif
                     {{ $referensi['kabupaten'] }}
                    @if (isset($referensi['kabupaten-terdahulu']) && $referensi['kabupaten-terdahulu'] != null)
                        (d/h. {{ $referensi['kabupaten-terdahulu'] }}).
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td>Nomor Register</td>
                <td>:</td>
                <td>{{ $referensi['registerNomor'] }}</td>
            </tr>
            <tr>
                <td>Tanggal Register</td>
                <td>:</td>
                <td>
                    @if (date('d-m', strtotime($referensi['registerTanggal'])) === '01-01')
                        -
                    @else
                        {{ $referensi['registerTanggalConvert'] }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Nomor Izin Mendirikan Bangunan</td>
                <td>:</td>
                <td>{{ $referensi['imbgNomor'] }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $referensi['imbgTanggalConvert'] }}</td>
            </tr>
            <tr>
                <td>Peruntukan</td>
                <td>:</td>
                <td><i><b>{{ $referensi['jenisKegiatan'] }}</b></i></td>
            </tr>
        </table>

        <br>
        <p style="text-align: justify;padding-top: 0px; padding-bottom: 0px; line-height: 1.15 !important; word-wrap: break-word; white-space: normal; text-indent: 25px; margin-left:0px">
            {{ $keterangan[0] }}
        </p>
        <p style="text-align: justify;  padding-top: 0px; padding-bottom: 0px; line-height: 1.15 !important; word-wrap: break-word; white-space: normal; text-indent: 25px; margin-left:0px">
            {{ $keterangan[1] }}
        </p>
        <p style="text-align: justify; padding-top: 0px; padding-bottom: 0px; line-height: 1.15 !important; word-wrap: break-word; white-space: normal; text-indent: 25px; margin-left:0px">
            {{ $keterangan[2] }}
        </p>
        @if (count($keterangan) > 3)
            @foreach ($keterangan as $index => $ket)
                @if ($index > 2)
                    <p style="margin-top:-5px;text-indent: 25px;">{{ $ket }}</p>
                @endif
            @endforeach
        @endif

        <div style="float: right">
            <div class="signature-section" style="width: 250px;text-align:left">
                <p class="signature-title" style="text-align:left">{{ $penandatangan['jabatan'] }} Perumahan, Kawasan
                    Permukiman dan
                    Pertanahan,</p>
                <br>
                <br>
                <br>
                <br>
                <p class="signature-name" style="text-align:left;line-height:5px">{{ $penandatangan['kepalaDinas'] }}
                </p>
                <p class="signature-role" style="text-align:left;line-height:5px">{{ $penandatangan['pangkat'] }}</p>
                <p class="signature-nip" style="text-align:left;line-height:5px">NIP. {{ $penandatangan['nip'] }}</p>
            </div>
        </div>


    </div>
</body>

</html>
