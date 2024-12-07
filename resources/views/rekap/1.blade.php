@extends('layouts.base')


@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">REKAP PENERBITAN</h3>
                    <br />
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;"
                            id="IMBTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TAHUN</th>
                                    <th>JUMLAH IMB</th>
                                    <th>IMB INDUK PERUMAHAN</th>
                                    <th>IMB PECAHAN</th>
                                    <th>IMB PERLUASAN</th>
                                    <th>IMB INDUK NON PERUMAHAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalJumlahIMB = 0;
                                    $totalIndukPerumahan = 0;
                                    $totalPecahan = 0;
                                    $totalPerluasan = 0;
                                    $totalIndukNonPerumahan = 0;
                                @endphp
                                @foreach ($data as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->tahun }}</td>
                                        <td>{{ $row->jumlah_imb }}</td>
                                        <td>{{ $row->imb_induk_perumahan }}</td>
                                        <td>{{ $row->imb_pecahan }}</td>
                                        <td>{{ $row->imb_perluasan }}</td>
                                        <td>{{ $row->imb_induk_non_perumahan }}</td>
                                    </tr>
                                    @php
                                        $totalJumlahIMB += $row->jumlah_imb;
                                        $totalIndukPerumahan += $row->imb_induk_perumahan;
                                        $totalPecahan += $row->imb_pecahan;
                                        $totalPerluasan += $row->imb_perluasan;
                                        $totalIndukNonPerumahan += $row->imb_induk_non_perumahan;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td></td>
                                    <td><strong>{{ $totalJumlahIMB }}</strong></td>
                                    <td><strong>{{ $totalIndukPerumahan }}</strong></td>
                                    <td><strong>{{ $totalPecahan }}</strong></td>
                                    <td><strong>{{ $totalPerluasan }}</strong></td>
                                    <td><strong>{{ $totalIndukNonPerumahan }}</strong></td>
                                </tr>
                            </tbody>
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
