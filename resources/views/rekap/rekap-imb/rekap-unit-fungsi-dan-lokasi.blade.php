@extends('layouts.base')


@section('content')
    <style>
        th {
            white-space: wrap;
            text-align: center;
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
                    <h3 class="text-3xl font-bold">REKAP UNIT FUNGSI DAN LOKASI </h3>
                    <br />
                    <div class="mb-4">
                        <form id="filterForm" class="form-inline">
                            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                <div>
                                    <input type="number" id="startYear" class="form-control" placeholder="Tahun Awal" />
                                </div>
                                <div>
                                    <input type="number" id="endYear" class="form-control" placeholder="Tahun Akhir" />
                                </div>
                                <div>
                                    <select name="" value="" class="form-control" id="kecamatan">
                                        <option value="" hidden>Pilih kecamatan</option>
                                        @foreach ($data as $index => $row)
                                            <option
                                                value="{{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}">
                                                {{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="" value="" class="form-control" id="kelurahan">
                                        <option value="" hidden>Pilih kelurahan</option>
                                        @foreach ($data as $index => $row)
                                            <option
                                                value="{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}">
                                                {{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                                    <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
                                    <th colspan="4" style="text-align: center" rowspan="2">JENIS IMB</th>
                                    <th colspan="10" style="text-align: center">FUNGSI BANGUNAN</th>
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
                                    <th>INDUK NON PERUMAHAN</th>
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
                                        <td>{{ 'BOGOR' }}</td> {{-- Sesuaikan jika ada data kabupaten --}}
                                        <td>{{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}
                                        </td>
                                        <td>{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}
                                        </td>
                                        <td>{{ $row->tahun }}</td>
                                        <td>{{ $row->imb_induk_perumahan + $row->imb_pecahan + $row->imb_perluasan + $row->imb_non_perumahan }}
                                        </td>
                                        <td>
                                            {{ $row->hunian_unit + $row->usaha_unit + $row->sosbud_unit + $row->khusus_unit + $row->campuran_unit }}
                                        </td>

                                        <td>{{ $row->imb_induk_perumahan }}</td>
                                        <td>{{ $row->imb_pecahan }}</td>
                                        <td>{{ $row->imb_perluasan }} </td>
                                        <td>{{ $row->imb_non_perumahan }}</td>
                                        <td>{{ $row->hunian_imb }} </td>
                                        <td> {{ $row->hunian_unit }}</td>
                                        <td>{{ $row->usaha_imb }} </td>
                                        <td> {{ $row->usaha_unit }}</td>
                                        <td>{{ $row->sosbud_imb }} </td>
                                        <td> {{ $row->sosbud_unit }}</td>
                                        <td>{{ $row->khusus_imb }} </td>
                                        <td> {{ $row->khusus_unit }}</td>
                                        <td>{{ $row->campuran_imb }} </td>
                                        <td> {{ $row->campuran_unit }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th id="total-jumlah-imb"></th>
                                    <th id="total-jumlah-unit"></th>
                                    <th id="total-imb-induk-perumahan"></th>
                                    <th id="total-imb-pecahan"></th>
                                    <th id="total-imb-perluasan"></th>
                                    <th id="total-imb-non-perumahan"></th>
                                    <th id="total-hunian-imb"></th>
                                    <th id="total-hunian-unit"></th>
                                    <th id="total-usaha-imb"></th>
                                    <th id="total-usaha-unit"></th>
                                    <th id="total-sosbud-imb"></th>
                                    <th id="total-sosbud-unit"></th>
                                    <th id="total-khusus-imb"></th>
                                    <th id="total-khusus-unit"></th>
                                    <th id="total-campuran-imb"></th>
                                    <th id="total-campuran-unit"></th>
                                    <th id="total-imb-khusus"></th>
                                    <th id="total-unit-khusus"></th>
                                    <th id="total-imb-campuran"></th>
                                    <th id="total-unit-campuran"></th>
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

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kecamatan').select2()
            $('#kelurahan').select2()
            const table = $('#IMBTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
                    },
                    {
                        extend: 'csv',
                        filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-'),
                        title: null
                    },
                    {
                        extend: 'excel',
                        filename: 'ExcelExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-'),
                        title: null
                    },
                    {
                        extend: 'pdf',
                        filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-'),
                        title: null
                    },
                    {
                        extend: 'print',
                        title: ''
                    }
                ],
               footerCallback: function(row, data, start, end, display) {
    // Inisialisasi total untuk setiap kolom
    let totalJumlahIMB = 0;
    let totalJumlahUnit = 0;
    let totalIndukPerumahan = 0;
    let totalPecahan = 0;
    let totalPerluasan = 0;
    let totalNonPerumahan = 0;
    let totalHunianIMB = 0;
    let totalHunianUnit = 0;
    let totalUsahaIMB = 0;
    let totalUsahaUnit = 0;
    let totalSosbudIMB = 0;
    let totalSosbudUnit = 0;
    let totalKhususIMB = 0;
    let totalKhususUnit = 0;
    let totalCampuranIMB = 0;
    let totalCampuranUnit = 0;

    // Iterasi melalui data untuk menghitung total
    display.forEach(function(idx) {
        const rowData = data[idx];
        totalJumlahIMB += parseFloat(rowData[5]) || 0; // Kolom Jumlah IMB
        totalJumlahUnit += parseFloat(rowData[6]) || 0; // Kolom Jumlah Unit
        totalIndukPerumahan += parseFloat(rowData[7]) || 0; // Kolom Induk Perumahan
        totalPecahan += parseFloat(rowData[8]) || 0; // Kolom Pecahan
        totalPerluasan += parseFloat(rowData[9]) || 0; // Kolom Perluasan
        totalNonPerumahan += parseFloat(rowData[10]) || 0; // Kolom Non Perumahan
        totalHunianIMB += parseFloat(rowData[11]) || 0; // Kolom Hunian IMB
        totalHunianUnit += parseFloat(rowData[12]) || 0; // Kolom Hunian Unit
        totalUsahaIMB += parseFloat(rowData[13]) || 0; // Kolom Usaha IMB
        totalUsahaUnit += parseFloat(rowData[14]) || 0; // Kolom Usaha Unit
        totalSosbudIMB += parseFloat(rowData[15]) || 0; // Kolom Sosial dan Budaya IMB
        totalSosbudUnit += parseFloat(rowData[16]) || 0; // Kolom Sosial dan Budaya Unit
        totalKhususIMB += parseFloat(rowData[17]) || 0; // Kolom Khusus IMB
        totalKhususUnit += parseFloat(rowData[18]) || 0; // Kolom Khusus Unit
        totalCampuranIMB += parseFloat(rowData[19]) || 0; // Kolom Campuran IMB
        totalCampuranUnit += parseFloat(rowData[20]) || 0; // Kolom Campuran Unit
    });

    // Update elemen footer dengan total nilai
    $('#total-jumlah-imb').text(totalJumlahIMB);
    $('#total-jumlah-unit').text(totalJumlahUnit);
    $('#total-imb-induk-perumahan').text(totalIndukPerumahan);
    $('#total-imb-pecahan').text(totalPecahan);
    $('#total-imb-perluasan').text(totalPerluasan);
    $('#total-imb-non-perumahan').text(totalNonPerumahan);
    $('#total-hunian-imb').text(totalHunianIMB);
    $('#total-hunian-unit').text(totalHunianUnit);
    $('#total-usaha-imb').text(totalUsahaIMB);
    $('#total-usaha-unit').text(totalUsahaUnit);
    $('#total-sosbud-imb').text(totalSosbudIMB);
    $('#total-sosbud-unit').text(totalSosbudUnit);
    $('#total-khusus-imb').text(totalKhususIMB);
    $('#total-khusus-unit').text(totalKhususUnit);
    $('#total-campuran-imb').text(totalCampuranIMB);
    $('#total-campuran-unit').text(totalCampuranUnit);
}

            });

            // Filter button functionality
            $('#filterButton').on('click', function() {
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);

                // Filter logic
                table.draw(); // Trigger DataTable redraw with filter
            });

            // Reset button functionality
            $('#resetButton').on('click', function() {
                $('#startYear').val('');
                $('#endYear').val('');
                $('#kecamatan').val('');
                $('#kelurahan').val('');
                table.draw();
            });

            // DataTable custom search function for year filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const tahun = parseInt(data[4]) || 0;
                const tableKec = data[2] || "";
                const tableKel = data[3] || "";
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);
                const kec = $('#kecamatan').val();
                const kel = $('#kelurahan').val();

                if (
                    (!startYear || tahun >= startYear) &&
                    (!endYear || tahun <= endYear) || (kec === tableKec || kel === tableKel)
                ) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
