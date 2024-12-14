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
                <h3 class="text-3xl font-bold">REKAP UNIT DAN FUNGSI PERTAHUN</h3>
                <br />
                <div class="mb-4">
                    <form method="GET" action="{{ route('rekap.RekapUnitDanFungsiPertahun') }}">
                        <div style="display:flex;gap:10px;">
                            <div>
                                <input type="number" name="year" id="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}" />
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('rekap.RekapUnitDanFungsiPertahun') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <br />

                @if (!empty($data) && count($data) > 0)
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" style="width: 100% !important;" id="IMBTable">
                            <thead>
                                <tr>
                                    <th rowspan="3">NO</th>
                                    <th rowspan="3">JENIS IMB</th>
                                    <th rowspan="3">JUMLAH IMB</th>
                                    <th rowspan="3">JUMLAH UNIT</th>
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
                                @foreach ($data as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->jenis_imb }}</td>
                                        <td>{{ $row->jumlah_imb }}</td>
                                        <td>{{ $row->jumlah_unit }}</td>
                                        <td>{{ $row->hunian_imb }}</td>
                                        <td>{{ $row->hunian_unit }}</td>
                                        <td>{{ $row->usaha_imb }}</td>
                                        <td>{{ $row->usaha_unit }}</td>
                                        <td>{{ $row->sosial_budaya_imb }}</td>
                                        <td>{{ $row->sosial_budaya_unit }}</td>
                                        <td>{{ $row->khusus_imb }}</td>
                                        <td>{{ $row->khusus_unit }}</td>
                                        <td>{{ $row->campuran_imb }}</td>
                                        <td>{{ $row->campuran_unit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th></th>
                                    <th id="totalJumlahIMB"></th>
                                    <th id="totalJumlahUnit"></th>
                                    <th id="totalHunianIMB"></th>
                                    <th id="totalHunianUnit"></th>
                                    <th id="totalUsahaIMB"></th>
                                    <th id="totalUsahaUnit"></th>
                                    <th id="totalSosBudIMB"></th>
                                    <th id="totalSosBudUnit"></th>
                                    <th id="totalKhususIMB"></th>
                                    <th id="totalKhususUnit"></th>
                                    <th id="totalCampuranIMB"></th>
                                    <th id="totalCampuranUnit"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-center">Silakan pilih tahun untuk melihat data.</p>
                @endif
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


    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
           const table = $('#IMBTable').DataTable({
                 footerCallback: function(row, data, start, end, display) {
                    // Inisialisasi total untuk setiap kolom
                    let totals = {
                        jumlahIMB: 0,
                        jumlahUnit: 0,
                        indukPerumahan: 0,
                        pecahan: 0,
                        perluasan: 0,
                        indukNonPerumahan: 0,
                        hunianIMB: 0,
                        hunianUnit: 0,
                        usahaIMB: 0,
                        usahaUnit: 0,
                        sosBudIMB: 0,
                        sosBudUnit: 0,
                        khususIMB: 0,
                        khususUnit: 0,
                        campuranIMB: 0,
                        campuranUnit: 0
                    };

                    // Iterasi melalui data untuk menghitung total
                    data.forEach(function(rowData) {
                        totals.jumlahIMB += parseInt(rowData[2]) || 0;
                        totals.jumlahUnit += parseInt(rowData[3]) || 0;
                        totals.indukPerumahan += parseInt(rowData[4]) || 0;
                        totals.pecahan += parseInt(rowData[5]) || 0;
                        totals.perluasan += parseInt(rowData[6]) || 0;
                        totals.indukNonPerumahan += parseInt(rowData[7]) || 0;
                        totals.hunianIMB += parseInt(rowData[4]) || 0;
                        totals.hunianUnit += parseInt(rowData[5]) || 0;
                        totals.usahaIMB += parseInt(rowData[6]) || 0;
                        totals.usahaUnit += parseInt(rowData[7]) || 0;
                        totals.sosBudIMB += parseInt(rowData[8]) || 0;
                        totals.sosBudUnit += parseInt(rowData[9]) || 0;
                        totals.khususIMB += parseInt(rowData[10]) || 0;
                        totals.khususUnit += parseInt(rowData[11]) || 0;
                        totals.campuranIMB += parseInt(rowData[12]) || 0;
                        totals.campuranUnit += parseInt(rowData[13]) || 0;
                    });

                    // Perbarui elemen footer
                    $('#totalJumlahIMB').text(totals.jumlahIMB);
                    $('#totalJumlahUnit').text(totals.jumlahUnit);
                    $('#totalIndukPerumahan').text(totals.indukPerumahan);
                    $('#totalPecahan').text(totals.pecahan);
                    $('#totalPerluasan').text(totals.perluasan);
                    $('#totalIndukNonPerumahan').text(totals.indukNonPerumahan);
                    $('#totalHunianIMB').text(totals.hunianIMB);
                    $('#totalHunianUnit').text(totals.hunianUnit);
                    $('#totalUsahaIMB').text(totals.usahaIMB);
                    $('#totalUsahaUnit').text(totals.usahaUnit);
                    $('#totalSosBudIMB').text(totals.sosBudIMB);
                    $('#totalSosBudUnit').text(totals.sosBudUnit);
                    $('#totalKhususIMB').text(totals.khususIMB);
                    $('#totalKhususUnit').text(totals.khususUnit);
                    $('#totalCampuranIMB').text(totals.campuranIMB);
                    $('#totalCampuranUnit').text(totals.campuranUnit);
                },
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
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            const workbook = XLSX.utils.book_new();
                            const sheetData = [
                                ['NO', 'JENIS IMB', 'JUMLAH IMB', 'JUMLAH UNIT', 'FUNGSI BANGUNAN']
                            ];
                            sheetData.push(['', '', '', '', 'HUNIAN', '', 'USAHA', '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN']);
                            sheetData.push(['', '', '', '', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT']);

                            const totals = Array(14).fill(0)
                            dt.rows({
                                search: 'applied'
                            }).every(function(rowIdx, tableLoop, rowLoop) {
                                const rowData = this.data();
                                sheetData.push([
                                    rowData[0], // NO
                                    rowData[1], // KAB/KOTA
                                    rowData[2], // KECAMATAN
                                    rowData[3], // DESA/KEL
                                    rowData[4], // TAHUN
                                    rowData[5], // JUMLAH IMB
                                    rowData[6], // JUMLAH UNIT
                                    rowData[7], // INDUK PERUMAHAN
                                    rowData[8], // PECAHAN
                                    rowData[9], // PERLUASAN
                                    rowData[10], // INDUK NON PERUMAHAN
                                    rowData[11], // HUNIAN IMB
                                    rowData[12],
                                    rowData[13],
                                ]);
                    
                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 2; i <= 13; i++) {
                                    totals[i] += parseFloat(rowData[i]) || 0; // Convert to number or default to 0
                                }
                            });
                    
                        // Push total row
                        const totalRow = ['TOTAL', '', ...totals.slice(2)];
                        sheetData.push(totalRow);

                            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                            // Add merge configuration (merging header rows for JENIS IMB and FUNGSI BANGUNAN)
                               worksheet['!merges'] = [
                                { s: { r: 0, c: 0 }, e: { r: 2, c: 0 } }, // Merge NO
                                { s: { r: 0, c: 1 }, e: { r: 2, c: 1 } }, // Merge JENIS IMB
                                { s: { r: 0, c: 2 }, e: { r: 2, c: 2 } }, // Merge JUMLAH IMB
                                { s: { r: 0, c: 3 }, e: { r: 2, c: 3 } }, // Merge JUMLAH UNIT
                                { s: { r: 0, c: 4 }, e: { r: 0, c: 13 } }, // Merge "FUNGSI BANGUNAN" (columns 4 to 13)
                                { s: { r: 1, c: 4 }, e: { r: 1, c: 5 } }, // Merge "HUNIAN"
                                { s: { r: 1, c: 6 }, e: { r: 1, c: 7 } }, // Merge "USAHA"
                                { s: { r: 1, c: 8 }, e: { r: 1, c: 9 } }, // Merge "SOSIAL DAN BUDAYA"
                                { s: { r: 1, c: 10 }, e: { r: 1, c: 11 } }, // Merge "KHUSUS"
                                { s: { r: 1, c: 12 }, e: { r: 1, c: 13 } }  // Merge "CAMPURAN"
                            ];

                            // Add worksheet to workbook
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'REKAP DATA');

                            // Save file
                            XLSX.writeFile(workbook, 'Export_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.xlsx');
                        }
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
