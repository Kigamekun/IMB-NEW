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
                    <h3 class="text-3xl font-bold">REKAP UNIT DAN FUNGSI</h3>
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

                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;"
                            id="IMBTable">

                            <thead>
                                <tr>
                                    <th rowspan="3">No</th>
                                    <th rowspan="3">Tahun</th>
                                    <th rowspan="3">Jumlah IMB</th>
                                    <th rowspan="3">Jumlah Unit</th>
                                    <th colspan="4" rowspan="2" style="text-align: center;">Jenis IMB</th>
                                    <th colspan="10" style="text-align: center">Fungsi Bangunan</th>
                                    {{-- <th rowspan="3">NO</th>
                                    <th rowspan="3">TAHUN</th>
                                    <th rowspan="3">JUMLAH IMB</th>
                                    <th rowspan="3">JUMLAH UNIT</th>
                                    <th colspan="4" rowspan="2" style="text-align: center;">JENIS IMB</th>
                                    <th colspan="10" style="text-align: center">FUNGSI BANGUNAN</th> --}}
                                </tr>
                                <tr>
                                    {{-- <th colspan="2">HUNIAN</th>
                                    <th colspan="2">USAHA</th>
                                    <th colspan="2">SOSIAL DAN BUDAYA</th>
                                    <th colspan="2">KHUSUS</th>
                                    <th colspan="2">CAMPURAN</th> --}}
                                    <th colspan="2">Hunian</th>
                                    <th colspan="2">Usaha</th>
                                    <th colspan="2">Sosial dan Budaya</th>
                                    <th colspan="2">Khusus</th>
                                    <th colspan="2">Campuran</th>
                                </tr>
                                <tr>
                                    {{-- <th>INDUK PERUMAHAN</th>
                                    <th>PECAHAN</th>
                                    <th>PERLUASAN</th>
                                    <th>INDUK NON PERUMAHAN</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th>
                                    <th>ITEM IMB</th>
                                    <th>UNIT</th> --}}
                                    <th>Induk Perumahan</th>
                                    <th>Pecahan</th>
                                    <th>Perluasan</th>
                                    <th>Induk Non Perumahan</th>
                                    <th>Item IMB</th>
                                    <th>Unit</th>
                                    <th>Item IMB</th>
                                    <th>Unit</th>
                                    <th>Item IMB</th>
                                    <th>Unit</th>
                                    <th>Item IMB</th>
                                    <th>Unit</th>
                                    <th>Item IMB</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
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
                                    <th colspan="2">Total</th>
                                    <th id="totalJumlahIMB">0</th>
                                    <th id="totalJumlahUnit">0</th>
                                    <th id="totalIndukPerumahan">0</th>
                                    <th id="totalPecahan">0</th>
                                    <th id="totalPerluasan">0</th>
                                    <th id="totalIndukNonPerumahan">0</th>
                                    <th id="totalHunianIMB">0</th>
                                    <th id="totalHunianUnit">0</th>
                                    <th id="totalUsahaIMB">0</th>
                                    <th id="totalUsahaUnit">0</th>
                                    <th id="totalSosBudIMB">0</th>
                                    <th id="totalSosBudUnit">0</th>
                                    <th id="totalKhususIMB">0</th>
                                    <th id="totalKhususUnit">0</th>
                                    <th id="totalCampuranIMB">0</th>
                                    <th id="totalCampuranUnit">0</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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
                 /*   {
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            const workbook = XLSX.utils.book_new();
                            const sheetData = [
                                ['NO', 'TAHUN', 'JUMLAH IMB', 'JUMLAH UNIT', 'JENIS IMB', '',
                                    '', '', 'FUNGSI BANGUNAN',
                                ]
                            ];
                            sheetData.push(['', '', '', '', '', '', '', '', 'HUNIAN', '', 'USAHA',
                                '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN'
                            ]);
                            sheetData.push(['', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN',
                                'PERLUASAN', 'INDUK NON PERUMAHAN', 'ITEM IMB', 'UNIT',
                                'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT',
                                'ITEM IMB', 'UNIT'
                            ]);

                            dt.rows({
                                search: 'applied'
                            }).every(function(rowIdx, tableLoop, rowLoop) {
                                const rowData = this.data();
                                sheetData.push([
                                    rowData[0], // NO
                                    rowData[1], // TAHUN
                                    rowData[2], // JUMLAH IMB
                                    rowData[3], // JUMLAH UNIT
                                    rowData[4], // INDUK PERUMAHAN
                                    rowData[5], // PECAHAN
                                    rowData[6], // PERLUASAN
                                    rowData[7], // INDUK NON PERUMAHAN
                                    rowData[8], // HUNIAN IMB
                                    rowData[9], // HUNIAN UNIT
                                    rowData[10], // USAHA IMB
                                    rowData[11], // USAHA UNIT
                                    rowData[12], // SOSIAL IMB
                                    rowData[13], // SOSIAL UNIT
                                    rowData[14], // KHUSUS IMB
                                    rowData[15], // KHUSUS UNIT
                                    rowData[16], // CAMPURAN IMB
                                    rowData[17], // CAMPURAN UNIT
                                ]);
                            });

                            sheetData.push([ 'TOTAL','', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' ]);

                            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                            // Add merge configuration (merging header rows)
                            worksheet['!merges'] = [{
                                    s: {
                                        r: 0,
                                        c: 0
                                    },
                                    e: {
                                        r: 2,
                                        c: 0
                                    }
                                }, // Merge NO
                                {
                                    s: {
                                        r: 0,
                                        c: 1
                                    },
                                    e: {
                                        r: 2,
                                        c: 1
                                    }
                                }, // Merge TAHUN
                                {
                                    s: {
                                        r: 0,
                                        c: 2
                                    },
                                    e: {
                                        r: 2,
                                        c: 2
                                    }
                                }, // Merge JUMLAH IMB
                                {
                                    s: {
                                        r: 0,
                                        c: 3
                                    },
                                    e: {
                                        r: 2,
                                        c: 3
                                    }
                                }, // Merge JUMLAH UNIT
                                {
                                    s: {
                                        r: 0,
                                        c: 4
                                    },
                                    e: {
                                        r: 1,
                                        c: 7
                                    }
                                }, // Merge JENIS IMB
                                {
                                    s: {
                                        r: 0,
                                        c: 8
                                    },
                                    e: {
                                        r: 0,
                                        c: 17
                                    }
                                }, // Merge FUNGSI BANGUNAN
                            ];

                            // Optional: Adjust column widths
                            worksheet['!cols'] = [{
                                    wpx: 50
                                }, // NO
                                {
                                    wpx: 60
                                }, // TAHUN
                                {
                                    wpx: 100
                                }, // JUMLAH IMB
                                {
                                    wpx: 100
                                }, // JUMLAH UNIT
                                {
                                    wpx: 120
                                }, // INDUK PERUMAHAN
                                {
                                    wpx: 120
                                }, // PECAHAN
                                {
                                    wpx: 120
                                }, // PERLUASAN
                                {
                                    wpx: 120
                                }, // INDUK NON PERUMAHAN
                                {
                                    wpx: 80
                                }, // FUNGSI BANGUNAN
                            ];

                            // Add worksheet to workbook
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'REKAP DATA');

                            // Save file
                            XLSX.writeFile(workbook, 'Export_' + new Date().toISOString().slice(0,
                                19).replace(/:/g, '-') + '.xlsx');
                        }
                    },*/
                    {
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            const workbook = XLSX.utils.book_new();
                            const sheetData = [
                                ['NO', 'TAHUN', 'JUMLAH IMB', 'JUMLAH UNIT', 'JENIS IMB', '', '', '', 'FUNGSI BANGUNAN'],
                                ['', '', '', '', '', '', '', '', 'HUNIAN', '', 'USAHA', '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN'],
                                ['', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN', 'PERLUASAN', 'INDUK NON PERUMAHAN', 'ITEM IMB', 'UNIT',
                                    'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT']
                            ];

                            // Process rows
                            const totals = Array(18).fill(0); // Initialize totals array
                            dt.rows({ search: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
                                const rowData = this.data();
                                const rowToPush = [
                                    rowData[0], // NO
                                    rowData[1], // TAHUN
                                    rowData[2], // JUMLAH IMB
                                    rowData[3], // JUMLAH UNIT
                                    rowData[4], // INDUK PERUMAHAN
                                    rowData[5], // PECAHAN
                                    rowData[6], // PERLUASAN
                                    rowData[7], // INDUK NON PERUMAHAN
                                    rowData[8], // HUNIAN IMB
                                    rowData[9], // HUNIAN UNIT
                                    rowData[10], // USAHA IMB
                                    rowData[11], // USAHA UNIT
                                    rowData[12], // SOSIAL IMB
                                    rowData[13], // SOSIAL UNIT
                                    rowData[14], // KHUSUS IMB
                                    rowData[15], // KHUSUS UNIT
                                    rowData[16], // CAMPURAN IMB
                                    rowData[17]  // CAMPURAN UNIT
                                ];
                                sheetData.push(rowToPush);

                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 2; i <= 17; i++) {
                                    totals[i] += parseFloat(rowData[i]) || 0; // Convert to number or default to 0
                                }
                            });

                            // Push total row
                            const totalRow = ['TOTAL', '', ...totals.slice(2)];
                            sheetData.push(totalRow);

                            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                            // Add merge configuration (merging header rows)
                            worksheet['!merges'] = [
                                { s: { r: 0, c: 0 }, e: { r: 2, c: 0 } }, // Merge NO
                                { s: { r: 0, c: 1 }, e: { r: 2, c: 1 } }, // Merge TAHUN
                                { s: { r: 0, c: 2 }, e: { r: 2, c: 2 } }, // Merge JUMLAH IMB
                                { s: { r: 0, c: 3 }, e: { r: 2, c: 3 } }, // Merge JUMLAH UNIT
                                { s: { r: 0, c: 4 }, e: { r: 1, c: 7 } }, // Merge JENIS IMB
                                { s: { r: 0, c: 8 }, e: { r: 0, c: 17 } }  // Merge FUNGSI BANGUNAN
                            ];

                            // Optional: Adjust column widths
                            worksheet['!cols'] = [
                                { wpx: 50 }, // NO
                                { wpx: 60 }, // TAHUN
                                { wpx: 100 }, // JUMLAH IMB
                                { wpx: 100 }, // JUMLAH UNIT
                                { wpx: 120 }, // INDUK PERUMAHAN
                                { wpx: 120 }, // PECAHAN
                                { wpx: 120 }, // PERLUASAN
                                { wpx: 120 }, // INDUK NON PERUMAHAN
                                { wpx: 80 }  // FUNGSI BANGUNAN
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
                ],
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
                    display.forEach(function(idx) {
                        const rowData = data[idx];
                        totals.jumlahIMB += parseInt(rowData[2]) || 0;
                        totals.jumlahUnit += parseInt(rowData[3]) || 0;
                        totals.indukPerumahan += parseInt(rowData[4]) || 0;
                        totals.pecahan += parseInt(rowData[5]) || 0;
                        totals.perluasan += parseInt(rowData[6]) || 0;
                        totals.indukNonPerumahan += parseInt(rowData[7]) || 0;
                        totals.hunianIMB += parseInt(rowData[8]) || 0;
                        totals.hunianUnit += parseInt(rowData[9]) || 0;
                        totals.usahaIMB += parseInt(rowData[10]) || 0;
                        totals.usahaUnit += parseInt(rowData[11]) || 0;
                        totals.sosBudIMB += parseInt(rowData[12]) || 0;
                        totals.sosBudUnit += parseInt(rowData[13]) || 0;
                        totals.khususIMB += parseInt(rowData[14]) || 0;
                        totals.khususUnit += parseInt(rowData[15]) || 0;
                        totals.campuranIMB += parseInt(rowData[16]) || 0;
                        totals.campuranUnit += parseInt(rowData[17]) || 0;
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
