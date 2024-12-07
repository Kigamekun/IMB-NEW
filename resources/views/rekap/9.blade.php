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
                    <h3 class="text-3xl font-bold">Data Rekap</h3>
                    <br />

                    <div class="table-responsive py-3">
                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>BERKAS MASUK</th>
                                    <th>JUMLAH SURAT</th>
                                    <th>PEMUTAKHIRAN DATA</th>
                                    <th>BALIK NAMA</th>
                                    <th>PENGGANTI SK IMBG HILANG</th>
                                    <th>BALIK NAMA & PENGGANTI SK IMBG HILANG</th>
                                    <th>PECAH & BALIK NAMA</th>
                                    <th>LEGALISIR</th>
                                    <th>FORMAT BARU	LINTAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>1980</td>
                                    <td>21,300</td>
                                    <td>1,000</td>
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
