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
                    <div class="table-responsive py-3">
                        <h2>Data</h2>
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

                                        </select>
                                    </div>
                                    <div>
                                        <select name="" value="" class="form-control" id="kelurahan">
                                            <option value="" hidden>Pilih kelurahan</option>

                                        </select>
                                    </div>
                                    <div>
                                        <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                                        <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <table id="table2" class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" class="display">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TAHUN</th>
                                    <th>NAMA PENGEMBANG</th>
                                    <th>JUMLAH IMB</th>
                                    <th>IMB INDUK PERUMAHAN</th>
                                    <th>IMB PECAHAN</th>
                                    <th>IMB PERLUASAN</th>
                                    <th>IMB INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th>IMB INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th>IMB INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th>IMB PEMUTIHAN</th>
                                    <th>IMB BERSYARAT</th>
                                    <th>IMB LAINNYA</th>
                                </tr>
                            </thead>
                            <tbody>
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
            const table = $('#table2').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tahun',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'NAMA_PENGEMBANG',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'jumlah_imb',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_induk_perumahan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_pecahan',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'imb_perluasan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_non_perusahaan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_perorangan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_sosbud',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_pemutihan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_bersyarat',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_lainnya',
                    orderable: false,
                    searchable: false
                },
            ],
            pageLength: 20,
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
    </script>
@endsection
