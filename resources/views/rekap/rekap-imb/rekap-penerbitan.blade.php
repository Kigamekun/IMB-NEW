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
                    <br>
                    <br>
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Jumlah IMB</th>
                                    <th>IMB Induk Perumahan</th>
                                    <th>IMB Pecahan</th>
                                    <th>IMB Perluasan</th>
                                    <th>IMB Induk Non Perumahan</th>
                                    {{-- <th>NO</th>
                                    <th>TAHUN</th>
                                    <th>JUMLAH IMB</th>
                                    <th>IMB INDUK PERUMAHAN</th>
                                    <th>IMB PECAHAN</th>
                                    <th>IMB PERLUASAN</th>
                                    <th>IMB INDUK NON PERUMAHAN</th> --}}
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
                                        <td>{{ $row->imb_induk_non_perumahan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td>-</td>
                                    <td><strong id="totalJumlahIMB"></strong></td>
                                    <td><strong id="totalIndukPerumahan"></strong></td>
                                    <td><strong id="totalPecahan"></strong></td>
                                    <td><strong id="totalPerluasan"></strong></td>
                                    <td><strong id="totalIndukNonPerumahan"></strong></td>
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
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script>
      $(document).ready(function() {
    // Initialize DataTable
    const table = $('#IMBTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
            },
            {
                extend: 'csv',
                filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
            },
            {
                extend: 'excel',
                filename: 'ExcelExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
            },
            {
                extend: 'pdf',
                filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
            },
            {
                extend: 'print'
            }
        ],
        footerCallback: function(row, data, start, end, display) {
            // Initialize totals
            let totalJumlahIMB = 0;
            let totalIndukPerumahan = 0;
            let totalPecahan = 0;
            let totalPerluasan = 0;
            let totalIndukNonPerumahan = 0;

            // Iterate through visible rows to calculate totals
            display.forEach(function(idx) {
                const rowData = data[idx];
                totalJumlahIMB += parseFloat(rowData[2]) || 0; // 'JUMLAH IMB'
                totalIndukPerumahan += parseFloat(rowData[3]) || 0; // 'IMB INDUK PERUMAHAN'
                totalPecahan += parseFloat(rowData[4]) || 0; // 'IMB PECAHAN'
                totalPerluasan += parseFloat(rowData[5]) || 0; // 'IMB PERLUASAN'
                totalIndukNonPerumahan += parseFloat(rowData[6]) || 0; // 'IMB INDUK NON PERUMAHAN'
            });

            // Update footer with totals
            $('#totalJumlahIMB').text(totalJumlahIMB);
            $('#totalIndukPerumahan').text(totalIndukPerumahan);
            $('#totalPecahan').text(totalPecahan);
            $('#totalPerluasan').text(totalPerluasan);
            $('#totalIndukNonPerumahan').text(totalIndukNonPerumahan);
        }
    });

    // Filter button functionality
    $('#filterButton').on('click', function() {
        const startYear = parseInt($('#startYear').val(), 10);
        const endYear = parseInt($('#endYear').val(), 10);

        table.draw(); // Trigger DataTable redraw with filter
    });

    // Reset button functionality
    $('#resetButton').on('click', function() {
        $('#startYear').val('');
        $('#endYear').val('');
        table.draw(); // Reset and redraw table
    });

    // Custom filter for year range
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const year = parseInt(data[1]) || 0; // Assume column 1 is 'TAHUN'
        const startYear = parseInt($('#startYear').val(), 10);
        const endYear = parseInt($('#endYear').val(), 10);

        return (!startYear || year >= startYear) && (!endYear || year <= endYear);
    });
});

    </script>
@endsection
