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
                    <h3 class="text-3xl font-bold">Data IMB</h3>


                    <br />

                    <div class="table-responsive py-3">

                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;"
                            id="IMBTable">

                            <thead>
                                <tr>
                                    <th rowspan="3">NO</th>
                                    <th rowspan="3">KAB/KOTA</th>
                                    <th rowspan="3">KECAMATAN</th>
                                    <th rowspan="3">DESA/KEL</th>
                                    <th rowspan="3">TAHUN</th>
                                    <th rowspan="3">JUMLAH IMB</th>
                                    <th rowspan="3">JUMLAH UNIT</th>
                                    <th colspan="9" rowspan="2">JENIS IMB</th>
                                    <th colspan="10">FUNGSI BANGUNAN</th>
                                </tr>
                                <tr>
                                    <th colspan="2">HUNIAN</th>
                                    <th colspan="2">USAHA</th>
                                    <th colspan="2">SOSIAL DAN BUDAYA</th>
                                    <th colspan="2">KHUSUS</th>
                                    <th colspan="2">CAMPURAN</th>
                                </tr>
                                <tr>
                                    <th>INDUK PERUMAHAN</th>
                                    <th>PECAHAN</th>
                                    <th>PERLUASAN</th>
                                    <th >INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th >INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th >INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th >PEMUTIHAN</th>
                                    <th >BERSYARAT</th>
                                    <th >LAINNYA</th>
                                    <th>IMB</th>
                                    <th>UNIT</th>
                                    <th>IMB</th>
                                    <th>UNIT</th>
                                    <th>IMB</th>
                                    <th>UNIT</th>
                                    <th>IMB</th>
                                    <th>UNIT</th>
                                    <th>IMB</th>
                                    <th>UNIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ 'BOGOR' }}</td> {{-- Ganti dengan data kabupaten jika tersedia --}}
                                    <td>{{
                                        DB::table('master_district')->where('code',$row->kecamatan)->first()->name
                                        }}</td>
                                        <td>{{
                                        DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name

                                        }}</td>
                                    <td>{{ $row->tahun }}</td>
                                    <td>{{ $row->imb_induk_perum + $row->imb_pecahan + $row->imb_perluasan + $row->imb_non_perusahaan + $row->imb_non_perorangan + $row->imb_non_sosial_budaya + $row->imb_pemutihan + $row->imb_bersyarat + $row->imb_lainnya }}</td>
                                    <td>{{ $row->jumlah_unit }}</td>
                                    <td>{{ $row->imb_induk_perum }}</td>
                                    <td>{{ $row->imb_pecahan }}</td>
                                    <td>{{ $row->imb_perluasan }}</td>
                                    <td>{{ $row->imb_non_perusahaan }}</td>
                                    <td>{{ $row->imb_non_perorangan }}</td>
                                    <td>{{ $row->imb_non_sosial_budaya }}</td>
                                    <td>{{ $row->imb_pemutihan }}</td>
                                    <td>{{ $row->imb_bersyarat }}</td>
                                    <td>{{ $row->imb_lainnya }}</td>
                                    <td>{{ $row->hunian_imb }}</td>
                                    <td>{{ $row->hunian_unit }}</td>
                                    <td>{{ $row->usaha_imb }}</td>
                                    <td>{{ $row->usaha_unit }}</td>
                                    <td>{{ $row->sosbud_imb }}</td>
                                    <td>{{ $row->sosbud_unit }}</td>
                                    <td>{{ $row->khusus_imb }}</td>
                                    <td>{{ $row->khusus_unit }}</td>
                                    <td>{{ $row->campuran_imb }}</td>
                                    <td>{{ $row->campuran_unit }}</td>
                                </tr>
                            @endforeach

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
