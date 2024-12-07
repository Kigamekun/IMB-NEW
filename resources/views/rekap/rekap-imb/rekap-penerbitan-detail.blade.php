@extends('layouts.base')


@section('content')

<style>
    th {
        white-space: nowrap;
    }
</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">


    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Data Surat</h3>
                    <div style="margin-top:20px">
                        <a href="{{ route('surat.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                    <br />
                    <div class="table-responsive py-3">
                        @php
                        $totalJumlahIMB = 0;
                        $totalIndukPerumahan = 0;
                        $totalPecahan = 0;
                        $totalPerluasan = 0;
                        $totalNonPerusahaan = 0;
                        $totalPerorangan = 0;
                        $totalSosbud = 0;
                        $totalPemutihan = 0;
                        $totalBersyarat = 0;
                        $totalLainnya = 0;
                    @endphp

                    <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;"
                    id="IMBTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Jumlah IMB</th>
                                <th>Induk Perumahan</th>
                                <th>Pecahan</th>
                                <th>Perluasan</th>
                                <th>Non Perusahaan</th>
                                <th>Perorangan</th>
                                <th>Sosbud</th>
                                <th>Pemutihan</th>
                                <th>Bersyarat</th>
                                <th>Lainnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->tahun }}</td>
                                    <td>{{ $row->jumlah_imb }}</td>
                                    <td>{{ $row->imb_induk_perumahan }}</td>
                                    <td>{{ $row->imb_pecahan }}</td>
                                    <td>{{ $row->imb_perluasan }}</td>
                                    <td>{{ $row->imb_non_perusahaan }}</td>
                                    <td>{{ $row->imb_perorangan }}</td>
                                    <td>{{ $row->imb_sosbud }}</td>
                                    <td>{{ $row->imb_pemutihan }}</td>
                                    <td>{{ $row->imb_bersyarat }}</td>
                                    <td>{{ $row->imb_lainnya }}</td>
                                </tr>
                                @php
                                    $totalJumlahIMB += $row->jumlah_imb;
                                    $totalIndukPerumahan += $row->imb_induk_perumahan;
                                    $totalPecahan += $row->imb_pecahan;
                                    $totalPerluasan += $row->imb_perluasan;
                                    $totalNonPerusahaan += $row->imb_non_perusahaan;
                                    $totalPerorangan += $row->imb_perorangan;
                                    $totalSosbud += $row->imb_sosbud;
                                    $totalPemutihan += $row->imb_pemutihan;
                                    $totalBersyarat += $row->imb_bersyarat;
                                    $totalLainnya += $row->imb_lainnya;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Total</strong></td>
                                <td></td>
                                <td><strong>{{ $totalJumlahIMB }}</strong></td>
                                <td><strong>{{ $totalIndukPerumahan }}</strong></td>
                                <td><strong>{{ $totalPecahan }}</strong></td>
                                <td><strong>{{ $totalPerluasan }}</strong></td>
                                <td><strong>{{ $totalNonPerusahaan }}</strong></td>
                                <td><strong>{{ $totalPerorangan }}</strong></td>
                                <td><strong>{{ $totalSosbud }}</strong></td>
                                <td><strong>{{ $totalPemutihan }}</strong></td>
                                <td><strong>{{ $totalBersyarat }}</strong></td>
                                <td><strong>{{ $totalLainnya }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#IMBTable').DataTable();
        });
    </script>
@endsection
