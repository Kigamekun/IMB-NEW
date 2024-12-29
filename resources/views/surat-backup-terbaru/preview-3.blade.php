<!DOCTYPE html>
<html>
    <head>
        <title>Detail Blog IMB</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
        </style>
    </head>
    <body style="width: 700px;">
        {{-- <h1>Preview Format 3</h1> --}}

        <h2>Details data IMBG</h2>
        <table>
            <tr>
                <th style="text-align:center">TIPE</th>
                <th style="text-align:center">PECAH/RINCIK</th>
                <th style="text-align:center">BELUM RINCIK/PECAH</th>
                <th style="text-align:center">Yang Sudah Dimohon Surat Keterangan</th>
            </tr>
            @php
                $jumlah = 0;
                $pecah_jumlah = 0;
                $belum_rincik_jumlah = 0;
                $sudah_dimohon_jumlah = 0;
            @endphp
            @foreach ($details as $item)
                <tr style="text-align:center">
                    <td style="text-align:center">Type {{ $item['type'] }} = {{ $item['jumlah'] }} Unit</td>
                    <td style="text-align:center">Type {{ $item['pecah_type'] }} = {{ $item['pecah_jumlah'] }} Unit</td>
                    <td style="text-align:center">Type {{ $item['belum_rincik_type'] }} = {{ $item['belum_rincik_jumlah'] }} Unit</td>
                    <td style="text-align:center">
                        @if ($item['sudah_dimohon_jumlah'] > 0)
                            Type {{ $item['sudah_dimohon_type'] }} = {{ $item['sudah_dimohon_jumlah'] }} Unit, <br>
                            {{ $item['sudah_dimohon_keterangan'] }}
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
                <td style="font-weight: bold; text-align:center">Jumlah = {{ $jumlah }} Unit</td>
                <td style="font-weight: bold; text-align:center">Jumlah = {{ $pecah_jumlah }} Unit</td>
                <td style="font-weight: bold; text-align:center">Jumlah = {{ $belum_rincik_jumlah }} Unit</td>
                <td style="font-weight: bold; text-align:center">Jumlah = {{ $sudah_dimohon_jumlah }} Unit</td>
            </tr>
        </table>

        <h2>Details 2 Data IMBG</h2>
        <table>
            <thead>
                <tr>
                    <th style="text-align:center">No</th>
                    <th style="text-align:center">Type</th>
                    <th style="text-align:center">Blok</th>
                    <th style="text-align:center">Nomor</th>
                    <th style="text-align:center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details2 as $index => $detail)
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td style="text-align:center">{{ $detail['type'] }}</td>
                        <td style="text-align:center">{{ $detail['blok'] }}</td>
                        <td style="text-align:center">{{ $detail['nomor'] }}</td>
                        <td style="text-align:center">{{ $detail['jumlah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
