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
                    <h3 class="text-3xl font-bold">REKAP UNIT FUNGSI DAN LOKASI DETAIL</h3>
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
                                    <th>INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th>INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th>INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th>PEMUTIHAN</th>
                                    <th>BERSYARAT</th>
                                    <th>LAINNYA</th>
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
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        <td>{{ 'BOGOR' }}</td> {{-- Sesuaikan jika ada data kabupaten --}}
                                        <td>{{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}
                                        </td>
                                        <td>{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}
                                        </td>
                                        <td>
                                            {{ collect([
                                                $item->imb_induk_perumahan,
                                                $item->imb_pecahan,
                                                $item->imb_perluasan,
                                                $item->imb_non_perumahan_perusahaan,
                                                $item->imb_non_perumahan_perorangan,
                                                $item->imb_non_perumahan_sosbud,
                                                $item->imb_pemutihan,
                                                $item->imb_bersyarat,
                                                $item->imb_lainnya,
                                            ])->sum() }}
                                        </td>
                                        <td>
                                            {{ collect([
                                                $item->unit_induk_perumahan,
                                                $item->unit_pecahan,
                                                $item->unit_perluasan,
                                                $item->unit_non_perumahan_perusahaan,
                                                $item->unit_non_perumahan_perorangan,
                                                $item->unit_non_perumahan_sosbud,
                                                $item->unit_pemutihan,
                                                $item->unit_bersyarat,
                                                $item->unit_lainnya,
                                            ])->sum() }}
                                        </td>
                                        <td>{{ $item->imb_induk_perumahan }}</td>
                                        <td>{{ $item->imb_pecahan }}</td>
                                        <td>{{ $item->imb_perluasan }}</td>
                                        <td>{{ $item->imb_non_perumahan_perusahaan }}</td>
                                        <td>{{ $item->imb_non_perumahan_perorangan }}</td>
                                        <td>{{ $item->imb_non_perumahan_sosbud }}</td>
                                        <td>{{ $item->imb_pemutihan }}</td>
                                        <td>{{ $item->imb_bersyarat }}</td>
                                        <td>{{ $item->imb_lainnya }}</td>
                                        <td>{{ $item->hunian_imb }}</td>
                                        <td>{{ $item->hunian_unit }}</td>
                                        <td>{{ $item->usaha_imb }}</td>
                                        <td>{{ $item->usaha_unit }}</td>
                                        <td>{{ $item->sosbud_imb }}</td>
                                        <td>{{ $item->sosbud_unit }}</td>
                                        <td>{{ $item->khusus_imb }}</td>
                                        <td>{{ $item->khusus_unit }}</td>
                                        <td>{{ $item->campuran_imb }}</td>
                                        <td>{{ $item->campuran_unit }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th id="totalJumlahIMB"></th>
                                    <th id="totalJumlahUnit"></th>
                                    <th id="totalIndukPerumahan"></th>
                                    <th id="totalPecahan"></th>
                                    <th id="totalPerluasan"></th>
                                    <th id="totalIndukNonPerumahanPerusahaan"></th>
                                    <th id="totalIndukNonPerumahanPerorangan"></th>
                                    <th id="totalIndukNonPerumahanSosbud"></th>
                                    <th id="totalPemutihan"></th>
                                    <th id="totalBersyarat"></th>
                                    <th id="totalLainnya"></th>
                                    <th id="totalHunianIMB"></th>
                                    <th id="totalHunianUnit"></th>
                                    <th id="totalUsahaIMB"></th>
                                    <th id="totalUsahaUnit"></th>
                                    <th id="totalSosbudIMB"></th>
                                    <th id="totalSosbudUnit"></th>
                                    <th id="totalKhususIMB"></th>
                                    <th id="totalKhususUnit"></th>
                                    <th id="totalCampuranIMB"></th>
                                    <th id="totalCampuranUnit"></th>
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
                buttons: [{
                        extend: 'copy',
                        filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
                    },
                    {
                        extend: 'csv',
                        filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-'),
                        title: null
                    },
                    {
                        extend: 'excel',
                        filename: 'ExcelExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-'),
                        title: null,
                    },
                    {
                        extend: 'pdf',
                        filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-'),
                        title: null,
                    },
                    {
                        extend: 'print',
                        title: '',
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    // Initialize totals
                    let totalJumlahIMB = 0;
                    let totalJumlahUnit = 0;
                    let totalIndukPerumahan = 0;
                    let totalPecahan = 0;
                    let totalPerluasan = 0;
                    let totalIndukNonPerumahanPerusahaan = 0;
                    let totalIndukNonPerumahanPerorangan = 0;
                    let totalIndukNonPerumahanSosbud = 0;
                    let totalPemutihan = 0;
                    let totalBersyarat = 0;
                    let totalLainnya = 0;
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

                    // Iterate through visible rows to calculate totals
                    display.forEach(function(idx) {
                        const rowData = data[idx];
                        totalJumlahIMB += parseInt(rowData[5]) || 0; // 'JUMLAH IMB'
                        totalJumlahUnit += parseInt(rowData[6]) || 0; // 'JUMLAH UNIT'
                        totalIndukPerumahan += parseInt(rowData[7]) ||
                        0; // 'IMB INDUK PERUMAHAN'
                        totalPecahan += parseInt(rowData[8]) || 0; // 'IMB PECAHAN'
                        totalPerluasan += parseInt(rowData[9]) || 0; // 'IMB PERLUASAN'
                        totalIndukNonPerumahanPerusahaan += parseInt(rowData[10]) ||
                        0; // 'IMB NON-PERUMAHAN (PERUSAHAAN)'
                        totalIndukNonPerumahanPerorangan += parseInt(rowData[11]) ||
                        0; // 'IMB NON-PERUMAHAN (PERORANGAN)'
                        totalIndukNonPerumahanSosbud += parseInt(rowData[12]) ||
                        0; // 'IMB NON-PERUMAHAN (SOSIAL BUDAYA)'
                        totalPemutihan += parseInt(rowData[13]) || 0; // 'PEMUTIHAN'
                        totalBersyarat += parseInt(rowData[14]) || 0; // 'BERSYARAT'
                        totalLainnya += parseInt(rowData[15]) || 0; // 'LAINNYA'
                        totalHunianIMB += parseInt(rowData[16]) || 0; // 'HUNIAN IMB'
                        totalHunianUnit += parseInt(rowData[17]) || 0; // 'HUNIAN UNIT'
                        totalUsahaIMB += parseInt(rowData[18]) || 0; // 'USAHA IMB'
                        totalUsahaUnit += parseInt(rowData[19]) || 0; // 'USAHA UNIT'
                        totalSosbudIMB += parseInt(rowData[20]) || 0; // 'SOSIAL BUDAYA IMB'
                        totalSosbudUnit += parseInt(rowData[21]) || 0; // 'SOSIAL BUDAYA UNIT'
                        totalKhususIMB += parseInt(rowData[22]) || 0; // 'KHUSUS IMB'
                        totalKhususUnit += parseInt(rowData[23]) || 0; // 'KHUSUS UNIT'
                        totalCampuranIMB += parseInt(rowData[24]) || 0; // 'CAMPURAN IMB'
                        totalCampuranUnit += parseInt(rowData[25]) || 0; // 'CAMPURAN UNIT'
                    });

                    // Update footer with totals
                    $('#totalJumlahIMB').text(totalJumlahIMB);
                    $('#totalJumlahUnit').text(totalJumlahUnit);
                    $('#totalIndukPerumahan').text(totalIndukPerumahan);
                    $('#totalPecahan').text(totalPecahan);
                    $('#totalPerluasan').text(totalPerluasan);
                    $('#totalIndukNonPerumahanPerusahaan').text(totalIndukNonPerumahanPerusahaan);
                    $('#totalIndukNonPerumahanPerorangan').text(totalIndukNonPerumahanPerorangan);
                    $('#totalIndukNonPerumahanSosbud').text(totalIndukNonPerumahanSosbud);
                    $('#totalPemutihan').text(totalPemutihan);
                    $('#totalBersyarat').text(totalBersyarat);
                    $('#totalLainnya').text(totalLainnya);
                    $('#totalHunianIMB').text(totalHunianIMB);
                    $('#totalHunianUnit').text(totalHunianUnit);
                    $('#totalUsahaIMB').text(totalUsahaIMB);
                    $('#totalUsahaUnit').text(totalUsahaUnit);
                    $('#totalSosbudIMB').text(totalSosbudIMB);
                    $('#totalSosbudUnit').text(totalSosbudUnit);
                    $('#totalKhususIMB').text(totalKhususIMB);
                    $('#totalKhususUnit').text(totalKhususUnit);
                    $('#totalCampuranIMB').text(totalCampuranIMB);
                    $('#totalCampuranUnit').text(totalCampuranUnit);
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