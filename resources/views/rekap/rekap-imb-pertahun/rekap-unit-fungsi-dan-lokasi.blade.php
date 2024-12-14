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
                    <h3 class="text-3xl font-bold">REKAP UNIT FUNGSI DAN LOKASI PERTAHUN</h3>
                    <br />
                    <div class="mb-4">
                        <form method="GET" action="{{ route('rekap.RekapUnitFungsiDanLokasiPertahun') }}">
                            <div style="display:flex;gap:10px;">
                                <div>
                                    <input type="number" name="year" id="year" class="form-control"
                                        placeholder="Tahun" value="{{ request('year') }}" />
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('rekap.RekapUnitFungsiDanLokasiPertahun') }}"
                                        class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br />

                    @if (!empty($data) && count($data) > 0)
                        <div class="table-responsive py-3">

                            <table class="table table-bordered"
                                style="width: 100% !important;border-bottom:none !important;" id="IMBTable">

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
                                            <td>{{ DB::table('master_district')->where('code', $item->kecamatan)->first()->name }}
                                            </td>
                                            <td>{{ DB::table('master_subdistrict')->where('code', $item->desa_kelurahan)->first()->name }}
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
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            const workbook = XLSX.utils.book_new();
                            const sheetData = [
                                ['NO', 'KAB/KOTA', 'KECAMATAN', 'DESA/KEL', 'TAHUN', 'JUMLAH IMB', 'JUMLAH UNIT', 'JENIS IMB','', '', '', '', '', '', '', '', 'FUNGSI BANGUNAN',]
                            ];
                            sheetData.push(['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'HUNIAN', '', 'USAHA', '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN']);
                            sheetData.push(['', '', '', '', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN', 'PERLUASAN', 'INDUK NON PERUMAHAN (PERUSAHAAN)', 'INDUK NON PERUMAHAN (PERORANGAN)', 'INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)', 'PEMUTIHAN', 'BERSYARAT', 'LAINNYA', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT']);

                            const totals = Array(26).fill(0)
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
                                    rowData[12], // HUNIAN UNIT
                                    rowData[13], // USAHA IMB
                                    rowData[14], // USAHA UNIT
                                    rowData[15], // SOSIAL IMB
                                    rowData[16], // SOSIAL UNIT
                                    rowData[17], // KHUSUS IMB
                                    rowData[18], // KHUSUS UNIT
                                    rowData[19], // CAMPURAN IMB
                                    rowData[20], // CAMPURAN UNIT
                                    rowData[21], // CAMPURAN UNIT
                                    rowData[22], // CAMPURAN UNIT
                                    rowData[23], // CAMPURAN UNIT
                                    rowData[24], // CAMPURAN UNIT
                                    rowData[25], // CAMPURAN UNIT
                                ]);
                    
                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 5; i <= 25; i++) {
                                    totals[i] += parseFloat(rowData[i]) || 0; // Convert to number or default to 0
                                }
                            });
                    
                        // Push total row
                        const totalRow = ['TOTAL', '', "", "", "", ...totals.slice(5)];
                        sheetData.push(totalRow);

                            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                            // Add merge configuration (merging header rows for JENIS IMB and FUNGSI BANGUNAN)
                            worksheet['!merges'] = [
                                {
                                    s: { r: 0, c: 0 }, e: { r: 2, c: 0 }  // Merge NO
                                },
                                {
                                    s: { r: 0, c: 1 }, e: { r: 2, c: 1 }  // Merge KAB/KOTA
                                },
                                {
                                    s: { r: 0, c: 2 }, e: { r: 2, c: 2 }  // Merge KECAMATAN
                                },
                                {
                                    s: { r: 0, c: 3 }, e: { r: 2, c: 3 }  // Merge DESA/KEL
                                },
                                {
                                    s: { r: 0, c: 4 }, e: { r: 2, c: 4 }  // Merge TAHUN
                                },
                                {
                                    s: { r: 0, c: 5 }, e: { r: 2, c: 5 }  // Merge JUMLAH IMB
                                },
                                {
                                    s: { r: 0, c: 6 }, e: { r: 2, c: 6 }  // Merge JUMLAH UNIT
                                },
                                {
                                    s: { r: 0, c: 7 }, e: { r: 1, c: 15 }  // Merge INDUK PERUMAHAN and PECAHAN
                                },
                                {
                                    s: { r: 0, c: 16 }, e: { r: 0, c: 25 } // Merge FUNGSI BANGUNAN (columns 11 to 20)
                                }
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
                    let totalJumlahIMB = 0;
                    let totalJumlahUNIT = 0;
                    let totalIndukPerumahan = 0;
                    let totalPecahan = 0;
                    let totalPerluasan = 0;
                    let totalNonPerusahaan = 0;
                    let totalNonPerorangan = 0;
                    let totalNonSosialBudaya = 0;
                    let totalPemutihan = 0;
                    let totalBersyarat = 0;
                    let totalLainnya = 0;
                    let hunianImb = 0;
                    let hunianUnit = 0;
                    let usahaImb = 0;
                    let usahaUnit = 0;
                    let sosbudImb = 0;
                    let sosbudUnit = 0;
                    let khususImb = 0;
                    let khususUnit = 0;
                    let campuranImb = 0;
                    let campuranUnit = 0;

                    // Hitung total dari kolom tertentu
                    data.forEach(function(rowData) {
                        totalJumlahIMB += parseFloat(rowData[5]) || 0;
                        totalJumlahUNIT += parseFloat(rowData[6]) || 0;
                        totalIndukPerumahan += parseFloat(rowData[7]) || 0;
                        totalPecahan += parseFloat(rowData[8]) || 0;
                        totalPerluasan += parseFloat(rowData[9]) || 0;
                        totalNonPerusahaan += parseFloat(rowData[10]) || 0;
                        totalNonPerorangan += parseFloat(rowData[11]) || 0;
                        totalNonSosialBudaya += parseFloat(rowData[12]) || 0;
                        totalPemutihan += parseFloat(rowData[13]) || 0;
                        totalBersyarat += parseFloat(rowData[14]) || 0;
                        totalLainnya += parseFloat(rowData[15]) || 0;
                        hunianImb += parseFloat(rowData[16]) || 0;
                        hunianUnit += parseFloat(rowData[17]) || 0;
                        usahaImb += parseFloat(rowData[18]) || 0;
                        usahaUnit += parseFloat(rowData[19]) || 0;
                        sosbudImb += parseFloat(rowData[20]) || 0;
                        sosbudUnit += parseFloat(rowData[21]) || 0;
                        khususImb += parseFloat(rowData[22]) || 0;
                        khususUnit += parseFloat(rowData[23]) || 0;
                        campuranImb += parseFloat(rowData[24]) || 0;
                        campuranUnit += parseFloat(rowData[25]) || 0;
                    });

                    // Update nilai di footer
                    $('#total-jumlah-imb').text(totalJumlahIMB.toLocaleString());
                    $('#total-jumlah-unit').text(totalJumlahUNIT.toLocaleString());
                    $('#total-imb-induk-perumahan').text(totalIndukPerumahan.toLocaleString());
                    $('#total-imb-pecahan').text(totalPecahan.toLocaleString());
                    $('#total-imb-perluasan').text(totalPerluasan.toLocaleString());
                    $('#total-imb-non-perumahan-perusahaan').text(totalNonPerusahaan.toLocaleString());
                    $('#total-imb-non-perumahan-perorangan').text(totalNonPerorangan.toLocaleString());
                    $('#total-imb-non-perumahan-sosial-budaya').text(totalNonSosialBudaya
                        .toLocaleString());
                    $('#total-pemutihan').text(totalPemutihan.toLocaleString());
                    $('#total-bersyarat').text(totalBersyarat.toLocaleString());
                    $('#total-lainnya').text(totalLainnya.toLocaleString());
                    $('#total-hunian-imb').text(hunianImb.toLocaleString());
                    $('#total-hunian-unit').text(hunianUnit.toLocaleString());
                    $('#total-usaha-imb').text(usahaImb.toLocaleString());
                    $('#total-usaha-unit').text(usahaUnit.toLocaleString());
                    $('#total-sosbud-imb').text(sosbudImb.toLocaleString());
                    $('#total-sosbud-unit').text(sosbudUnit.toLocaleString());
                    $('#total-khusus-imb').text(khususImb.toLocaleString());
                    $('#total-khusus-unit').text(khususUnit.toLocaleString());
                    $('#total-campuran-imb').text(campuranImb.toLocaleString());
                    $('#total-campuran-unit').text(campuranUnit.toLocaleString());
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
