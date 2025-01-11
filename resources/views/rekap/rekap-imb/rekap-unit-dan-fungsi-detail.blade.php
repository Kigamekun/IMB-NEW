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
                                <th style="text-align: center; vertical-align:middle" rowspan="3">No</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">Tahun</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">Jumlah IMB</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">Jumlah Unit</th>
                                <th style="text-align: center; vertical-align:middle" colspan="9" rowspan="2" style="text-align: center; vertical-align:middle">Jenis IMB</th>
                                <th style="text-align: center; vertical-align:middle" colspan="10" style="text-align: center; vertical-align:middle">Fungsi Bangunan</th>
                                {{-- <th style="text-align: center; vertical-align:middle" rowspan="3">NO</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">TAHUN</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">JUMLAH IMB</th>
                                <th style="text-align: center; vertical-align:middle" rowspan="3">JUMLAH UNIT</th>
                                <th style="text-align: center; vertical-align:middle" colspan="9" rowspan="2" style="text-align: center; vertical-align:middle">JENIS IMB</th>
                                <th style="text-align: center; vertical-align:middle" colspan="10" style="text-align: center; vertical-align:middle">FUNGSI BANGUNAN</th> --}}
                            </tr>
                            <tr>
                                <th style="text-align: center; vertical-align:middle" colspan="2">Hunian</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">Usaha</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">Sosial dan Budaya</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">Khusus</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">Campuran</th>
                                {{-- <th style="text-align: center; vertical-align:middle" colspan="2">HUNIAN</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">USAHA</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">SOSIAL DAN BUDAYA</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">KHUSUS</th>
                                <th style="text-align: center; vertical-align:middle" colspan="2">CAMPURAN</th> --}}
                            </tr>
                            <tr>
                                <th style="text-align: center; vertical-align:middle">Induk Perumahan</th>
                                <th style="text-align: center; vertical-align:middle">Pecahan</th>
                                <th style="text-align: center; vertical-align:middle">Perluasan</th>
                                <th style="text-align: center; vertical-align:middle">Induk Non Perumahan (Perusahaan)</th>
                                <th style="text-align: center; vertical-align:middle">Induk Non Perumahan (Perorangan)</th>
                                <th style="text-align: center; vertical-align:middle">Induk Non Perumahan (Sosial dan Budaya)</th>
                                <th style="text-align: center; vertical-align:middle">Pemutihan</th>
                                <th style="text-align: center; vertical-align:middle">Bersyarat</th>
                                <th style="text-align: center; vertical-align:middle">Lainnya</th>
                                <th style="text-align: center; vertical-align:middle">Item IMB</th>
                                <th style="text-align: center; vertical-align:middle">Unit</th>
                                <th style="text-align: center; vertical-align:middle">Item IMB</th>
                                <th style="text-align: center; vertical-align:middle">Unit</th>
                                <th style="text-align: center; vertical-align:middle">Item IMB</th>
                                <th style="text-align: center; vertical-align:middle">Unit</th>
                                <th style="text-align: center; vertical-align:middle">Item IMB</th>
                                <th style="text-align: center; vertical-align:middle">Unit</th>
                                <th style="text-align: center; vertical-align:middle">Item IMB</th>
                                <th style="text-align: center; vertical-align:middle">Unit</th>
                                {{-- <th>INDUK PERUMAHAN</th>
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
                                <th>UNIT</th> --}}
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
                        <tfoot>
                            <tr>
                                <th style="text-align: center" >Total</th>
                                <th></th> <!-- Placeholder untuk kolom "Tahun" -->
                                <th id="total-jumlah-imb"></th>
                                <th id="total-jumlah-unit"></th>
                                <th id="total-imb-induk-perumahan"></th>
                                <th id="total-imb-pecahan"></th>
                                <th id="total-imb-perluasan"></th>
                                <th id="total-imb-non-perumahan-perusahaan"></th>
                                <th id="total-imb-non-perumahan-perorangan"></th>
                                <th id="total-imb-non-perumahan-sosial-budaya"></th>
                                <th id="total-pemutihan"></th>
                                <th id="total-bersyarat"></th>
                                <th id="total-lainnya"></th>
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
                {
                    text: 'Excel',
                    action: function(e, dt, button, config) {
                        const workbook = XLSX.utils.book_new();
                        const sheetData = [
                            ['NO', 'TAHUN', 'JUMLAH IMB', 'JUMLAH UNIT', 'JENIS IMB', '', '', '', '', '', '', '', '', 'FUNGSI BANGUNAN', '', '', '', '', '', '', '']
                        ];
                        sheetData.push(['', '', '', '', '', '', '', '', '', '', '', '', '', 'HUNIAN', '', 'USAHA', '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN']);
                        sheetData.push(['', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN', 'PERLUASAN', 'PERUSAHAAN', 'PERORANGAN', 'SOSBUD' , 'PEMUTIHAN', 'BERSYARAT', 'LAINNYA', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT']);

                            // Process rows
                            const totals = Array(23).fill(0); // Initialize totals array
                        dt.rows({
                            search: 'applied'
                        }).every(function(rowIdx, tableLoop, rowLoop) {
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
                                rowData[17], // CAMPURAN UNIT
                                rowData[18], // CAMPURAN UNIT
                                rowData[19], // CAMPURAN UNIT
                                rowData[20], // CAMPURAN UNIT
                                rowData[21], // CAMPURAN UNIT
                                rowData[22], // CAMPURAN UNIT
                            ];
                            sheetData.push(rowToPush);

                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 2; i <= 22; i++) {
                                    totals[i] += parseFloat(rowData[i]) || 0; // Convert to number or default to 0
                                }
                        });

                        // Push total row
                        const totalRow = ['TOTAL', '', ...totals.slice(2)];
                        sheetData.push(totalRow);

                        const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                        // Add merge configuration (merging header rows)
                        worksheet['!merges'] = [
                            {
                                s: { r: 0, c: 0 },
                                e: { r: 2, c: 0 }
                            }, // Merge NO
                            {
                                s: { r: 0, c: 1 },
                                e: { r: 2, c: 1 }
                            }, // Merge TAHUN
                            {
                                s: { r: 0, c: 2 },
                                e: { r: 2, c: 2 }
                            }, // Merge JUMLAH IMB
                            {
                                s: { r: 0, c: 3 },
                                e: { r: 2, c: 3 }
                            }, // Merge JUMLAH UNIT
                            {
                                s: { r: 0, c: 4 },
                                e: { r: 0, c: 12 }
                            }, // Merge JENIS IMB (columns 4 to 12)
                         /*   {
                                s: { r: 1, c: 4 },
                                e: { r: 2, c: 4 }
                            },
                            {
                                s: { r: 1, c: 5 },
                                e: { r: 2, c: 5 }
                            },
                            {
                                s: { r: 1, c: 6 },
                                e: { r: 2, c: 6 }
                            },
                            {
                                s: { r: 1, c: 7 },
                                e: { r: 2, c: 7 }
                            },
                            {
                                s: { r: 1, c: 8 },
                                e: { r: 2, c: 8 }
                            },
                            {
                                s: { r: 1, c: 9 },
                                e: { r: 2, c: 9 }
                            },
                            {
                                s: { r: 1, c: 10 },
                                e: { r: 2, c: 10 }
                            },
                            {
                                s: { r: 1, c: 11 },
                                e: { r: 2, c: 11 }
                            },
                            {
                                s: { r: 1, c: 12 },
                                e: { r: 2, c: 12 }
                            },  */
                            {
                                s: { r: 0, c: 13 },
                                e: { r: 0, c: 22 }
                            }, // Merge FUNGSI BANGUNAN (columns 13 to 22)
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
                            { wpx: 80 }, // HUNIAN IMB
                            { wpx: 80 }, // HUNIAN UNIT
                            { wpx: 80 }, // USAHA IMB
                            { wpx: 80 }, // USAHA UNIT
                            { wpx: 80 }, // SOSIAL IMB
                            { wpx: 80 }, // SOSIAL UNIT
                            { wpx: 80 }, // KHUSUS IMB
                            { wpx: 80 }, // KHUSUS UNIT
                            { wpx: 80 }, // CAMPURAN IMB
                            { wpx: 80 }, // CAMPURAN UNIT
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
                }],
            footerCallback: function(row, data, start, end, display) {
                // Inisialisasi variabel total untuk setiap kolom
                let totalJumlahIMB = 0;
                let totalJumlahUnit = 0;
                let totalIMBIndukPerumahan = 0;
                let totalIMBPecahan = 0;
                let totalIMBPerluasan = 0;
                let totalIMBNonPerumahanPerusahaan = 0;
                let totalIMBNonPerumahanPerorangan = 0;
                let totalIMBNonPerumahanSosBud = 0;
                let totalPemutihan = 0;
                let totalBersyarat = 0;
                let totalLainnya = 0;
                let totalHunianIMB = 0;
                let totalHunianUnit = 0;
                let totalUsahaIMB = 0;
                let totalUsahaUnit = 0;
                let totalSosBudIMB = 0;
                let totalSosBudUnit = 0;
                let totalKhususIMB = 0;
                let totalKhususUnit = 0;
                let totalCampuranIMB = 0;
                let totalCampuranUnit = 0;

                // Iterasi melalui data tabel
                display.forEach(function(idx) {
                    const rowData = data[idx]
                    totalJumlahIMB += parseInt(rowData[2]) || 0;
                    totalJumlahUnit += parseInt(rowData[3]) || 0;
                    totalIMBIndukPerumahan += parseInt(rowData[4]) || 0;
                    totalIMBPecahan += parseInt(rowData[5]) || 0;
                    totalIMBPerluasan += parseInt(rowData[6]) || 0;
                    totalIMBNonPerumahanPerusahaan += parseInt(rowData[7]) || 0;
                    totalIMBNonPerumahanPerorangan += parseInt(rowData[8]) || 0;
                    totalIMBNonPerumahanSosBud += parseInt(rowData[9]) || 0;
                    totalPemutihan += parseInt(rowData[10]) || 0;
                    totalBersyarat += parseInt(rowData[11]) || 0;
                    totalLainnya += parseInt(rowData[12]) || 0;
                    totalHunianIMB += parseInt(rowData[13]) || 0;
                    totalHunianUnit += parseInt(rowData[14]) || 0;
                    totalUsahaIMB += parseInt(rowData[15]) || 0;
                    totalUsahaUnit += parseInt(rowData[16]) || 0;
                    totalSosBudIMB += parseInt(rowData[17]) || 0;
                    totalSosBudUnit += parseInt(rowData[18]) || 0;
                    totalKhususIMB += parseInt(rowData[19]) || 0;
                    totalKhususUnit += parseInt(rowData[20]) || 0;
                    totalCampuranIMB += parseInt(rowData[21]) || 0;
                    totalCampuranUnit += parseInt(rowData[22]) || 0;
                });

                // Perbarui elemen footer secara manual
                $('#total-jumlah-imb').text(totalJumlahIMB.toLocaleString());
                $('#total-jumlah-unit').text(totalJumlahUnit.toLocaleString());
                $('#total-imb-induk-perumahan').text(totalIMBIndukPerumahan.toLocaleString());
                $('#total-imb-pecahan').text(totalIMBPecahan.toLocaleString());
                $('#total-imb-perluasan').text(totalIMBPerluasan.toLocaleString());
                $('#total-imb-non-perumahan-perusahaan').text(totalIMBNonPerumahanPerusahaan.toLocaleString());
                $('#total-imb-non-perumahan-perorangan').text(totalIMBNonPerumahanPerorangan.toLocaleString());
                $('#total-imb-non-perumahan-sosial-budaya').text(totalIMBNonPerumahanSosBud.toLocaleString());
                $('#total-pemutihan').text(totalPemutihan.toLocaleString());
                $('#total-bersyarat').text(totalBersyarat.toLocaleString());
                $('#total-lainnya').text(totalLainnya.toLocaleString());
                $('#total-hunian-imb').text(totalHunianIMB.toLocaleString());
                $('#total-hunian-unit').text(totalHunianUnit.toLocaleString());
                $('#total-usaha-imb').text(totalUsahaIMB.toLocaleString());
                $('#total-usaha-unit').text(totalUsahaUnit.toLocaleString());
                $('#total-sosbud-imb').text(totalSosBudIMB.toLocaleString());
                $('#total-sosbud-unit').text(totalSosBudUnit.toLocaleString());
                $('#total-khusus-imb').text(totalKhususIMB.toLocaleString());
                $('#total-khusus-unit').text(totalKhususUnit.toLocaleString());
                $('#total-campuran-imb').text(totalCampuranIMB.toLocaleString());
                $('#total-campuran-unit').text(totalCampuranUnit.toLocaleString());
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
