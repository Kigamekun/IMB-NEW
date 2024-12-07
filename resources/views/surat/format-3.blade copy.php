<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Izin Mendirikan Bangunan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: {{ $referensi['font_surat'] }}px !important;
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
            font-size: {{ $referensi['font_surat'] }}px !important;
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
            padding: 4px 8px;
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
            line-height:1 !important;
        }
    </style>
</head>

<body>
    <div style="float: right; margin-right:125px; display:grid grid-column: 1">
        <p>Cibinong,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2024
        </p>
        <div style="line-height:1px;">
            <p>Kepada</p>
            <p>Yth, <b>{{ $pemohon['sapaanPemohon'] }} {{ $pemohon['nama'] }}</b></p>
            <p>di</p>
            @php
            if (str_contains($pemohon['kabupatenPemohon'], 'Kabupaten')) {
                $pemohon['kabupatenPemohon'] = trim(str_replace('Kabupaten', '', $pemohon['kabupatenPemohon']));
            }
            @endphp

            <p style="margin-left: 15px">{{$pemohon['kabupatenPemohon'] }}</p>
        </div>
    </div>

    <table class="info-table">
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
            <td>Perihal</td>
            <td>:</td>
            <td style="line-height: normal">{{ $perihal }}</td>
        </tr>
    </table>

    <div class="content" style="margin-top:1px; margin-left: 70px">
        <h3>A. DASAR</h3>
        <p style="margin-top:-5px">Peraturan Bupati Nomor 63 tahun 2013 Tentang Izin Mendirikan Bangunan Gedung BAB VII
            Penggantian IMBG Hilang
            Atau Rusak, Legalisasi dan Pemutakhiran.</p>
        <h3>B. MEMPERHATIKAN</h3>
        <ol style="margin-top:-15px">
            <li>
                <div>
                    Permohonan Surat Keterangan Penerbitan Izin Mendirikan Bangunan (IMB) tanggal {{ $tanggalSurat }},
                    dari:
                    <table class="info-tables">
                        <tr>
                            <td style="width: 200px">Nama</td>
                            <td style="width: 5px">:</td>
                            <td class="bold-text"><b>{{ $pemohon['nama'] }}</b></td>
                        </tr>
                        <tr>
                            <td>Bertindak Atas Nama</td>
                            <td>:</td>
                            <td>{{ $pemohon['bertindak_atas_nama'] }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td style="line-height:10px;">{{ $pemohon['alamat'] }}, Desa/Kelurahan
                                {{ $pemohon['kelurahanPemohon'] }}, Kecamatan
                                {{ $pemohon['kecamatanPemohon'] }}, {{ $pemohon['kabupatenPemohon'] }}</td>
                            </td>
                        </tr>
                    </table>
                </div>
            </li>
            <li>Hasil pengecekan pada buku Izin Mendirikan Bangunan yang ada pada Dinas Perumahan Kawasan Permukiman dan
                Pertanahan Kabupaten Bogor, dengan ini dapat disampaikan:</li>
            <div>
                <table class="info-tables">
                    <tr>
                        <td style="width: 200px">Izin Mendirikan Bangunan Atas Nama</td>
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
                        <td style="line-height:10px;">
                            {{ $referensi['lokasi'] != null || $referensi['lokasi'] != '' ? $referensi['lokasi'] . ',' : '' }}

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
                            @endif
                        </td>
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
            <div class="table-">
                <table>
                    <tr>
                        <th>TIPE</th>
                        <th>PECAH/RINCIK</th>
                        <th>BELUM RINCIK/PECAH</th>
                        <th>Yang Sudah Dimohon Surat Keterangan</th>
                    </tr>
                    @php
                        $jumlah = 0;
                        $pecah_jumlah = 0;
                        $belum_rincik_jumlah = 0;
                        $sudah_dimohon_jumlah = 0;
                    @endphp
                    @foreach ($details as $item)
                        <tr>
                            <td>Type {{ $item['type'] }} = {{ $item['jumlah'] }} Unit</td>
                            <td>Type {{ $item['pecah_type'] }} = {{ $item['pecah_jumlah'] }} Unit</td>
                            <td>Type {{ $item['belum_rincik_type'] }} = {{ $item['belum_rincik_jumlah'] }} Unit</td>
                            <td>
                                @if ($item['sudah_dimohon_jumlah'] > 0)
                                    Type {{ $item['sudah_dimohon_type'] }} = {{ $item['sudah_dimohon_jumlah'] }} Unit
                                @else
                                    ---
                                @endif
                            </td>
                        </tr>
                        @php
                            $jumlah += $item['jumlah'];
                            $pecah_jumlah += $item['pecah_jumlah'];
                            $belum_rincik_jumlah += $item['belum_rincik_jumlah'];
                            $sudah_dimohon_jumlah += $item['sudah_dimohon_jumlah'];
                        @endphp
                    @endforeach
                    <tr>
                        <td style="font-weight: bold">Jumlah = {{ $jumlah }} Unit</td>
                        <td style="font-weight: bold">Jumlah = {{ $pecah_jumlah }} Unit</td>
                        <td style="font-weight: bold">Jumlah = {{ $belum_rincik_jumlah }} Unit</td>
                        <td style="font-weight: bold">Jumlah = {{ $sudah_dimohon_jumlah }} Unit</td>
                    </tr>
                </table>
            </div>
        </ol>
        <ol start="3">
            <li>Nama dan Nomor Izin Mendirikan Bangunan Gedung tersebut di atas adalah benar tercatat dalam buku
                register Izin Mendirikan Bangunan pada Dinas Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten
                Bogor...</li>
            <div class="table-">
                <table>
                    <tr>
                        <th>NO</th>
                        <th>TIPE</th>
                        <th>BLOK</th>
                        <th>NOMOR</th>
                        <th>JUMLAH</th>
                    </tr>
                    @php
                        $jumlah = 0;
                    @endphp
                    @foreach ($details2 as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['type'] }}</td>
                            <td>{{ $item['blok'] }}</td>
                            <td>{{ $item['nomor'] }}</td>
                            <td>{{ $item['jumlah'] }}</td>
                            @php
                                $jumlah += $item['jumlah'];
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{ $jumlah }}</td>
                    </tr>
                </table>
            </div>
        </ol>
        <p>{{ $keterangan[0] }}</p>
        <p>
            {{ $keterangan[1] }}
        </p>
        <p>
            {{ $keterangan[2] }}
        </p>
        @if (count($keterangan) > 3)
            @foreach ($keterangan as $index => $ket)
                @if ($index > 2)
                    <p>{{ $ket }}</p>
                @endif
            @endforeach
        @endif
        <p>Demikian disampaikan untuk diketahui dan dipergunakan sebagaimana mestinya.</p>
    </div>
    <div style="float: right;margin-top:-10px">
        <div class="signature-section" style="width: 250px;text-align:left">
            <p class="signature-title" style="text-align:left">{{ $penandatangan['jabatan'] }} Perumahan, Kawasan
                Permukiman dan
                Pertanahan,</p>
            <br>
            <br>
            <br>
            <p class="signature-name" style="text-align:left;line-height:5px">{{ $penandatangan['kepalaDinas'] }}</p>
            <p class="signature-role" style="text-align:left;line-height:5px">{{ $penandatangan['pangkat'] }}</p>
            <p class="signature-nip" style="text-align:left;line-height:5px">NIP. {{ $penandatangan['nip'] }}</p>
        </div>
    </div>
</body>

</html>
