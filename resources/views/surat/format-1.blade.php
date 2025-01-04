<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Izin Mendirikan Bangunan</title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: {{ $referensi['font_surat'] }}pt !important;
            line-height: 1.5;
            text-align: justify
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


        ul.custom-list {
        list-style-type: none;
        }
        ul.custom-list li::before {
            content: "-";
            margin-left: 5px
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
            width: 300px;
            border: none;
            border-collapse: collapse;
        }

        .info-table td {
            text-align: left;
            border: none;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 8px;
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

        .info-tables td:first-child {
            width: 15%;
            /* Lebar kolom pertama */
            white-space: nowrap;
            /* Mencegah kolom pertama meluas */
        }

        .info-tables td:nth-child(2) {
            width: 1%;
            /* Lebar kolom untuk tanda titik dua (:) */
            text-align: center;
        }

        .info-tables td:last-child {
            width: 84%;
            /* Lebar kolom terakhir */
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-title {
            /* font-weight: bold; */
            text-align: justify;
            margin-bottom: 10px;
        }

        .signature-role {
            text-align: center;
        }

        .signature-name {
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }

        .signature-nip {
            text-align: center;
            margin-top: 5px;
        }

        table td {
            line-height:1 !important;
        }
    </style>
</head>

<body style="width: 700px">
    <div style="float: right; margin-right:100px;  margin-top:-25px">
        <p>Cibinong,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$tahun}}
        </p>
    </div>
    <table class="info-table" style="  width:300px;">
        <tr>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $nomorSurat }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>{{ $lampiran }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>{{ $sifat }}</td>
        </tr>
        {{-- <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>{{ $lampiran }}</td>
        </tr> --}}
        <tr style="">
            <td>Hal</td>
            <td>:</td>
            <td style="line-height: 1.15; text-align:justify; font-weight:bold; ">{{ $perihal }}</td>
        </tr>
    </table>
    <!-- Address Section -->
    <div style="line-height:3px;margin-left:8px; padding">
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

    <div class="content" style="margin-left: 65px">
        <h3>A. DASAR</h3>
        <ul class="custom-list" style="margin-top:-16px; ">
            <li ><p style="margin-top:-12px; line-height:1.15 !important; margin-left: 15px">Peraturan Bupati Nomor 63 tahun 2013 Tentang Izin Mendirikan Bangunan Gedung BAB VII
                Penggantian IMBG Hilang
                atau Rusak, Legalisasi dan Pemutakhiran.</p></li>
        </ul>

        <h3>B. MEMPERHATIKAN</h3>
        <ol style="margin-top:-15px; margin-left:-5px">
            <li >
                <div>
                    Permohonan Surat Keterangan Penerbitan Izin Mendirikan Bangunan (IMB) tanggal {{ $tanggalSurat }},
                    dari:
                    <table class="info-tables" style="width: 100%; margin-left:-1px;">
                        <tr>
                            <td style="width: 250px;">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style="width: 2px;">:</td>
                            <td class="bold-text"><b>{{ $pemohon['nama'] }}</b></td>
                        </tr>
                        <tr >
                            <td>Bertindak Atas Nama</td>
                            <td>:</td>
                            <td>{{ $pemohon['bertindak_atas_nama'] }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td style="line-height:1.15 !important; text-align:justify " id="pemohon_alamat">
                                {{ $pemohon['alamat'] }}, Desa/Kelurahan
                                {{ $pemohon['kelurahanPemohon'] }}, Kecamatan
                                {{ $pemohon['kecamatanPemohon'] }},
                                {{ $pemohon['kabupatenPemohon'] }}
                            </td>
                            </td>
                        </tr>

                    </table>

                </div>
            </li>


            <li style="margin-top: 15px !important; margin-bottom: 15px !important; line-height:1.15 !important;">Hasil pengecekan pada buku Izin Mendirikan Bangunan yang ada pada Dinas Perumahan Kawasan Permukiman dan
                Pertanahan Kabupaten Bogor, dengan ini dapat disampaikan:</li>


            <div>
                <table class="info-tables" style="width: 100%; margin-left:-1px">
                    <tr>
                        <td style="width: 200px">Izin Mendirikan Bangunan Atas Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width: 5px">:</td>
                        <td class="bold-text">
                            <b>{{ $referensi['izin_mendirikan_bangunan_atas_nama'] }}</b>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>Peruntukan</td>
                        <td>:</td>
                        <td>{{ $referensi['tujuan'] }}</td>
                    </tr> --}}
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td style="line-height:1.15 !important; text-align:justify">
                            {{-- {{ $referensi['lokasi'] != null || $referensi['lokasi'] != '' ? $referensi['lokasi'] . ',' : '' }}

                            Desa/Kelurahan {{ $referensi['kelurahan'] }}
                            @if (isset($referensi['kelurahan-terdahulu']) && $referensi['kelurahan-terdahulu'] != null)
                                (d/h. {{ $referensi['kelurahan-terdahulu'] }})
                            @endif
                            , Kecamatan {{ $referensi['kecamatan'] }}
                            @if (isset($referensi['kecamatan-terdahulu']) && $referensi['kecamatan-terdahulu'] != null)
                                (d/h. {{ $referensi['kecamatan-terdahulu'] }})
                            @endif
                            , {{ $referensi['kabupaten'] }}
                            @if (isset($referensi['kabupaten-terdahulu']) && $referensi['kabupaten-terdahulu'] != null)
                                (d/h. {{ $referensi['kabupaten-terdahulu'] }})
                            @endif --}}
                            {{ $referensi['lokasi'] != null || $referensi['lokasi'] != '' ? $referensi['lokasi'] . ',' : '' }}

                            @if (isset($referensi['kelurahan-terdahulu']) && $referensi['kelurahan-terdahulu'] != null)
                                Desa/Kelurahan {{ $referensi['kelurahan'] }} (d/h. {{ $referensi['kelurahan-terdahulu'] }}),
                            @else
                                Desa/Kelurahan {{ $referensi['kelurahan'] }},
                            @endif
                            @if (isset($referensi['kecamatan-terdahulu']) && $referensi['kecamatan-terdahulu'] != null)
                            Kecamatan {{ $referensi['kecamatan'] }} (d/h. {{ $referensi['kecamatan-terdahulu'] }}),
                            @else
                            Kecamatan {{ $referensi['kecamatan'] }},
                            @endif

                            @if (isset($referensi['kabupaten-terdahulu']) && $referensi['kabupaten-terdahulu'] != null)
                                {{ $referensi['kabupaten'] }} (d/h. {{ $referensi['kabupaten-terdahulu'] }}).
                            @else
                                {{ $referensi['kabupaten'] }}.
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
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
                        <td>{{ $referensi['registerTanggalConvert'] }}</td>
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
                        <td>Dipergunakan Untuk</td>
                        <td>:</td>
                        <td><i><b>{{ $referensi['tujuan'] }}</b></i></td>
                    </tr>
                </table>
            </div>
        </ol>
        <ol start="3" style="line-height:1.15">
            <li>{{ $keterangan[0] }}</li>
        </ol>
        {{-- <p style=" margin-top:10px;">{{ $keterangan[0] }}</p> --}}
        <p style="margin-left: 25px; line-height:1.15" >
            {{ $keterangan[1] }}
        </p>

        <p style="margin-left: 25px; line-height:40px !important;">
            {{ $keterangan[2] }}
        </p>
        @if (count($keterangan) > 3)
            @foreach ($keterangan as $index => $ket)
                @if ($index > 2)
                    <p style="margin-left: 25px; line-height:1.15">{{ $ket }}</p>
                @endif
            @endforeach
        @endif

        <p style="margin-left: 25px; line-height:1.15">Demikian disampaikan untuk diketahui dan dipergunakan sebagaimana mestinya.</p>
    </div>
    <div style="float: right">
        <div class="signature-section" style="width: 250px;text-align:left">
            <p class="signature-title" style="text-align:left; line-height: 1.5">{{ $penandatangan['jabatan'] }} Perumahan, Kawasan
                Permukiman dan
                Pertanahan,</p>
            <br>
            <br>
            <br>
            <br>
            <p class="signature-name" style="text-align:left;line-height:5px">{{ $penandatangan['kepalaDinas'] }}</p>
            <p class="signature-role" style="text-align:left;line-height:5px">{{ $penandatangan['pangkat'] }}</p>
            <p class="signature-nip" style="text-align:left;line-height:5px">NIP. {{ $penandatangan['nip'] }}</p>
        </div>
    </div>
    <div style="margin-top:200px;">
        <h4 style="margin-left: 25px; line-height:1">Tembusan :</h4>
        <ol style="margin-top: -15px">
            <li>Yth. Kepala DPMPTSP Kabupaten Bogor.</li>
        </ol>
    </div>
</body>

</html>
