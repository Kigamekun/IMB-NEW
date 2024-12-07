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
                    <h3 class="text-3xl font-bold">REKAP UNIT DAN FUNGSI DETAIL</h3>
                    <br />
                    <div class="mb-4">
                        <form id="filterForm" class="form-inline">
                            <div style="display:flex;gap:10px;">
                                <div>
                                    <input type="number" id="startYear" class="form-control" placeholder="Tahun Awal" />
                                </div>
                                <div>
                                    <input type="number" id="endYear" class="form-control" placeholder="Tahun Akhir" />
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

                        <table class="table table-bordered" style="width: 100% !important; border-bottom:none !important;" id="IMBTable">
                            <thead>
                                <tr>
                                    <th rowspan="3">NO</th>
                                    <th rowspan="3">TAHUN</th>
                                    <th rowspan="3">JUMLAH IMB</th>
                                    <th rowspan="3">JUMLAH UNIT</th>
                                    <th colspan="9" rowspan="2"  style="text-align: center">JENIS IMB</th>
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
                                    <th>INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th>INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th>INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th>PEMUTIHAN</th>
                                    <th>BERSYARAT</th>
                                    <th>LAINNYA</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->tahun }}</td>
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
                                                $item->imb_lainnya
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
                                                $item->unit_lainnya
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

    <script>
        $(document).ready(function() {
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
                ]
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
                table.draw();
            });

            // DataTable custom search function for year filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const tahun = parseInt(data[1]) || 0; // Assume column 1 is 'TAHUN'
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);

                if (
                    (!startYear || tahun >= startYear) &&
                    (!endYear || tahun <= endYear)
                ) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
