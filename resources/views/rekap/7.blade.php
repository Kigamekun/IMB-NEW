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





                        <h2>Table 1</h2>
                        <table id="table1" class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" class="display">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NO. IMB PECAHAN</th>
                                    <th>TGL. IMB PECAHAN</th>
                                    <th>NAMA</th>
                                    <th>BERTINDAK ATAS NAMA</th>
                                    <th>JENIS KEGIATAN</th>
                                    <th>FUNGSI BANGUNAN</th>
                                    <th>LOKASI / PERUMAHAN</th>
                                    <th>KECAMATAN</th>
                                    <th>DESA/ KEL.</th>
                                    <th>TYPE SEBELUMNYA</th>
                                    <th>BLOK</th>
                                    <th>NO. BLOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2000</td>
                                    <td>2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>0</td>
                                </tr>
                            </tbody>
                        </table>

                        <h2>Table 2</h2>
                        <table id="table2" class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" class="display">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NO. IMB PECAHAN</th>
                                    <th>TGL. IMB PECAHAN</th>
                                    <th>NAMA</th>
                                    <th>BERTINDAK ATAS NAMA</th>
                                    <th>JENIS KEGIATAN</th>
                                    <th>FUNGSI BANGUNAN</th>
                                    <th>LOKASI / PERUMAHAN</th>
                                    <th>KECAMATAN</th>
                                    <th>DESA/ KEL.</th>
                                    <th>TYPE SEBELUMNYA</th>
                                    <th>BLOK</th>
                                    <th>NO. BLOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2000</td>
                                    <td>PT. KENTANIX SUPRA INTERNATIONAL</td>
                                    <td>104</td>
                                    <td>2</td>
                                    <td>102</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>0</td>
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
            $('#table1').DataTable();
            $('#table2').DataTable();
        });
    </script>
@endsection
