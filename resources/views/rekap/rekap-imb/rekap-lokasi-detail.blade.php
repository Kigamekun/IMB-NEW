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
                    <h3 class="text-3xl font-bold">REKAP LOKASI DETAIL</h3>
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
                                    <select value="" class="form-control select2" id="kabupaten">
                                        <option value="" hidden>Pilih kabupaten</option>
                                        {{-- @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}">{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}</option>
                                      @endforeach --}}
                                       @php
                                            $regencies = DB::table('master_regency')->get()->keyBy('code');
                                        @endphp
                                        {{-- @foreach ($data as $index => $row)
                                            @if(isset($row->kabupaten) && isset($regencies[$row->kabupaten]))
                                                <option value="{{ $regencies[$row->kabupaten]->name }}">{{ $regencies[$row->kabupaten]->name }}</option>
                                            @endif
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div>
                                    <select name="" value="" class="form-control select2" id="kecamatan">
                                        <option value="" hidden>Pilih kecamatan</option>
                                        {{-- @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}">{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}</option>
                                      @endforeach --}}
                                       @php
                                            $districts = DB::table('master_district')->get()->keyBy('code');
                                        @endphp

                                        {{-- @foreach ($data as $index => $row)
                                            @if(isset($row->kecamatan) && isset($districts[$row->kecamatan]))
                                                <option value="{{ $districts[$row->kecamatan]->name }}">{{ $districts[$row->kecamatan]->name }}</option>
                                            @endif
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div>
                                    <select name="" value="" class="form-control select2" id="kelurahan">
                                        <option value="" hidden>Pilih kelurahan</option>
                                        {{-- @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}">{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}</option>
                                      @endforeach --}}
                                       @php
                                            $subdistrict = DB::table('master_subdistrict')->get()->keyBy('code');
                                        @endphp

                                        @foreach ($data as $index => $row)
                                            @if(isset($row->desa_kelurahan) && isset($subdistrict[$row->desa_kelurahan]))
                                                <option value="{{ $subdistrict[$row->desa_kelurahan]->name }}">{{ $subdistrict[$row->desa_kelurahan]->name }}</option>
                                            @endif
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
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Kab/Kota</th>
                                    <th rowspan="2">Kecamatan</th>
                                    <th rowspan="2">Desa/Kel</th>
                                    <th rowspan="2">Tahun</th>
                                    <th rowspan="2">Jumlah IMB</th>
                                    <th colspan="9" style="text-align: center">Jenis IMB</th>
                                    {{-- <th rowspan="2">NO</th>
                                    <th rowspan="2">KAB/KOTA</th>
                                    <th rowspan="2">KECAMATAN</th>
                                    <th rowspan="2">DESA/KEL</th>
                                    <th rowspan="2">TAHUN</th>
                                    <th rowspan="2">JUMLAH IMB</th>
                                    <th colspan="9" style="text-align: center">JENIS IMB</th> --}}
                                </tr>
                                <tr>
                                    {{-- <th >INDUK PERUMAHAN</th>
                                    <th >PECAHAN</th>
                                    <th >PERLUASAN</th>
                                    <th >INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th >INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th >INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th >PEMUTIHAN</th>
                                    <th >BERSYARAT</th>
                                    <th >LAINNYA</th> --}}
                                    <th>Induk Perumahan</th>
                                    <th>Pecahan</th>
                                    <th>Perluasan</th>
                                    <th>Induk Non Perumahan (Perusahaan)</th>
                                    <th>Induk Non Perumahan (Perorangan)</th>
                                    <th>Induk Non Perumahan (Sosial dan Budaya)</th>
                                    <th>Pemutihan</th>
                                    <th>Bersyarat</th>
                                    <th>Lainnya</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    {{-- <td>{{ 'BOGOR' }}</td>
                                    <td>{{
                                        DB::table('master_district')->where('code',$row->kecamatan)->first()->name
                                        }}</td>
                                        <td>{{
                                        DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name

                                        }}</td> --}}
                                    <td>
                                        @if(isset($row->kabupaten) && isset($regencies[$row->kabupaten]))
                                            <option value="{{ $regencies[$row->kabupaten]->name }}">{{ $regencies[$row->kabupaten]->name }}</option>
                                        @endif
                                    </td> {{-- Sesuaikan jika ada data kabupaten --}}
                                    <td>
                                        @if(isset($row->kecamatan) && isset($districts[$row->kecamatan]))
                                             <option value="{{ $districts[$row->kecamatan]->name }}">{{ $districts[$row->kecamatan]->name }}</option>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($row->desa_kelurahan) && isset($subdistrict[$row->desa_kelurahan]))
                                            <option value="{{ $subdistrict[$row->desa_kelurahan]->name }}">{{ $subdistrict[$row->desa_kelurahan]->name }}</option>
                                        @endif
                                    </td>
                                    <td>{{ $row->tahun }}</td>
                                    <td>{{ $row->jumlah_imb }}</td>
                                    <td>{{ $row->imb_induk_perum }}</td>
                                    <td>{{ $row->imb_pecahan }}</td>
                                    <td>{{ $row->imb_perluasan }}</td>
                                    <td>{{ $row->imb_non_perusahaan }}</td>
                                    <td>{{ $row->imb_non_perorangan }}</td>
                                    <td>{{ $row->imb_non_sosial_budaya }}</td>
                                    <td>{{ $row->imb_pemutihan }}</td>
                                    <td>{{ $row->imb_bersyarat }}</td>
                                    <td>{{ $row->imb_lainnya }}</td>
                                </tr>
                            @endforeach
                            </tbody><tfoot>
                                <tr>
                                    <th colspan="5" style="text-align: left;">Total:</th>
                                    <th id="totalJumlahIMB">0</th>
                                    <th id="totalIndukPerumahan">0</th>
                                    <th id="totalPecahan">0</th>
                                    <th id="totalPerluasan">0</th>
                                    <th id="totalNonPerusahaan">0</th>
                                    <th id="totalNonPerorangan">0</th>
                                    <th id="totalNonSosialBudaya">0</th>
                                    <th id="totalPemutihan">0</th>
                                    <th id="totalBersyarat">0</th>
                                    <th id="totalLainnya">0</th>
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#kabupaten').select2({
                width: '100%',
                placeholder: 'Pilih Kabupaten',
                //minimumInputLength: 2,
                ajax: {
                    url: "{{ route('master.kabupaten') }}", // URL to fetch kabupaten data
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        console.log("Fetched data:", data); // Check data structure here
                        return {
                            results: data.items.map(function(item) {
                                return {
                                    id: item.text,
                                    code: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function(e) {
                console.log("Selected Kabupaten:", e.params.data);
                loadKecamatan(e.params.data.code)
            });

            $('#kelurahan').select2({
                width: '100%',
                placeholder: 'Pilih Kelurahan',
            })

            function getKecamatan() {
                $('#kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    //minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kecamatan') }}", // URL to fetch kabupaten data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched data:", data); // Check data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.text,
                                        code: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kabupaten:", e.params.data);
                    loadKelurahan(e.params.data.code)
                });
            }

            getKecamatan()

            function getKelurahan() {
                $('#kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    //minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kelurahan') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                        //  console.log("Fetched data:", data); // Check data structure here
                        const limitedItems = data.items.slice(0, 80); // Batasi hanya 80 data pertama supaya tidak lag

                            return {
                                results: limitedItems.map(function(item) {
                                    return {
                                        id: item.text,
                                        code: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kelurahan:", e.params.data);
                });
            }

            function loadKecamatan(kabId) {
                $('#kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    //minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kecamatan') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kabupaten_id: kabId,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched data:", data); // Check data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.text,
                                        code: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kabupaten:", e.params.data);
                    //loadKecamatan(e.params.data.id)
                });

            }

            function loadKelurahan(kecId) {
                $('#kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    //minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kelurahan') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kecamatan_id: kecId,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched data:", data); // Check data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.text,
                                        code: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kabupaten:", e.params.data);
                    //loadKecamatan(e.params.data.id)
                });

            }

            const table = $('#IMBTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'csv', filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    {
                        text: 'Excel',
                        action: function(e, dt, button, config) {
                            const workbook = XLSX.utils.book_new();
                            const sheetData = [
                                ['NO', 'KAB/KOTA', 'KECAMATAN', 'DESA/KEL', 'TAHUN', 'JUMLAH IMB', 'JENIS IMB', '', '', '', '', '', '', '']
                            ];
                            sheetData.push(['', '', '', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN', 'PERLUASAN', 'INDUK NON PERUMAHAN (PERUSAHAAN)', 'INDUK NON PERUMAHAN (PERORANGAN)', 'INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)', 'PEMUTIHAN', 'BERSYARAT', 'LAINNYA']);

                            // Process rows
                            const totals = Array(15).fill(0);
                            // Loop through DataTable rows to add data
                            dt.rows({ search: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
                                const rowData = this.data();
                                const rowToPush = [
                                    rowData[0], // NO
                                    rowData[1], // KAB/KOTA
                                    rowData[2], // KECAMATAN
                                    rowData[3], // DESA/KEL
                                    rowData[4], // TAHUN
                                    rowData[5], // JUMLAH IMB
                                    rowData[6], // INDUK PERUMAHAN
                                    rowData[7], // PECAHAN
                                    rowData[8], // PERLUASAN
                                    rowData[9], // INDUK NON PERUMAHAN (PERUSAHAAN)
                                    rowData[10], // INDUK NON PERUMAHAN (PERORANGAN)
                                    rowData[11], // INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)
                                    rowData[12], // PEMUTIHAN
                                    rowData[13], // BERSYARAT
                                    rowData[14] // LAINNYA
                                ];
                                sheetData.push(rowToPush)

                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 5; i <= 14; i++) {
                                    totals[i] += parseFloat(rowData[i]) || 0; // Convert to number or default to 0
                                }
                            });

                        // Push total row
                        const totalRow = ['TOTAL', '', "", "", "", ...totals.slice(5)];
                        sheetData.push(totalRow);

                            const worksheet = XLSX.utils.aoa_to_sheet(sheetData);

                            // Add merge configuration (merging header rows for JENIS IMB columns)
                            worksheet['!merges'] = [
                                {
                                    s: { r: 0, c: 0 }, e: { r: 1, c: 0 }  // Merge NO
                                },
                                {
                                    s: { r: 0, c: 1 }, e: { r: 1, c: 1 }  // Merge KAB/KOTA
                                },
                                {
                                    s: { r: 0, c: 2 }, e: { r: 1, c: 2 }  // Merge KECAMATAN
                                },
                                {
                                    s: { r: 0, c: 3 }, e: { r: 1, c: 3 }  // Merge DESA/KEL
                                },
                                {
                                    s: { r: 0, c: 4 }, e: { r: 1, c: 4 }  // Merge TAHUN
                                },
                                {
                                    s: { r: 0, c: 5 }, e: { r: 1, c: 5 }  // Merge JUMLAH IMB
                                },
                                {
                                    s: { r: 0, c: 6 }, e: { r: 0, c: 14 }  // Merge JENIS IMB (columns 6 to 14)
                                }
                            ];

                            // Optional: Adjust column widths
                            worksheet['!cols'] = [
                                { wpx: 50 },  // NO
                                { wpx: 100 }, // KAB/KOTA
                                { wpx: 100 }, // KECAMATAN
                                { wpx: 100 }, // DESA/KEL
                                { wpx: 60 },  // TAHUN
                                { wpx: 100 }, // JUMLAH IMB
                                { wpx: 120 }, // INDUK PERUMAHAN
                                { wpx: 120 }, // PECAHAN
                                { wpx: 120 }, // PERLUASAN
                                { wpx: 120 }, // INDUK NON PERUMAHAN (PERUSAHAAN)
                                { wpx: 120 }, // INDUK NON PERUMAHAN (PERORANGAN)
                                { wpx: 120 }, // INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)
                                { wpx: 120 }, // PEMUTIHAN
                                { wpx: 120 }, // BERSYARAT
                                { wpx: 120 }  // LAINNYA
                            ];

                            // Add worksheet to workbook
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'REKAP DATA');

                            // Save file
                            XLSX.writeFile(workbook, 'Export_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.xlsx');
                        }
                    },
                    { extend: 'pdf', filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'print', title: '' },
                ],
                footerCallback: function(row, data, start, end, display) {
                    let totalJumlahIMB = 0;
                    let totalIndukPerumahan = 0;
                    let totalPecahan = 0;
                    let totalPerluasan = 0;
                    let totalNonPerusahaan = 0;
                    let totalNonPerorangan = 0;
                    let totalNonSosialBudaya = 0;
                    let totalPemutihan = 0;
                    let totalBersyarat = 0;
                    let totalLainnya = 0;

                    // Hitung total dari kolom tertentu
                    display.forEach(function(idx) {
                        const rowData = data[idx]
                        totalJumlahIMB += parseFloat(rowData[5]) || 0;
                        totalIndukPerumahan += parseFloat(rowData[6]) || 0;
                        totalPecahan += parseFloat(rowData[7]) || 0;
                        totalPerluasan += parseFloat(rowData[8]) || 0;
                        totalNonPerusahaan += parseFloat(rowData[9]) || 0;
                        totalNonPerorangan += parseFloat(rowData[10]) || 0;
                        totalNonSosialBudaya += parseFloat(rowData[11]) || 0;
                        totalPemutihan += parseFloat(rowData[12]) || 0;
                        totalBersyarat += parseFloat(rowData[13]) || 0;
                        totalLainnya += parseFloat(rowData[14]) || 0;
                    });

                    // Update nilai di footer
                    $('#totalJumlahIMB').text(totalJumlahIMB);
                    $('#totalIndukPerumahan').text(totalIndukPerumahan);
                    $('#totalPecahan').text(totalPecahan);
                    $('#totalPerluasan').text(totalPerluasan);
                    $('#totalNonPerusahaan').text(totalNonPerusahaan);
                    $('#totalNonPerorangan').text(totalNonPerorangan);
                    $('#totalNonSosialBudaya').text(totalNonSosialBudaya);
                    $('#totalPemutihan').text(totalPemutihan);
                    $('#totalBersyarat').text(totalBersyarat);
                    $('#totalLainnya').text(totalLainnya);
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
                $('#kabupaten').val('').trigger('change');
                $('#kecamatan').val('').trigger('change');
                $('#kelurahan').val('').trigger('change');

                getKecamatan()
                getKelurahan()

                table.draw();
            });
            // DataTable custom search function for year filtering
           /* $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const tahun = parseInt(data[4]) || 0;
                const tableKec = data[2] || "";
                const tableKel = data[3] || "";
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);
                const kec = $('#kecamatan').val();
                const kel = $('#kelurahan').val();

               if (
                    ((!startYear || tahun >= startYear) &&
                    (!endYear || tahun <= endYear)) || (kec === tableKec && kel === tableKel)
                ) {
                    return true;
                }
                 const inYearRange = (!startYear || tahun >= startYear) && (!endYear || tahun <= endYear);
                 const matchesKecKel = kec === tableKec && kel === tableKel;
                 if (inYearRange) {
                   return true; // Data sesuai rentang tahun
                 } else if (kec && kel && tahun >= (startYear || 0) && tahun <= (endYear || Infinity)) {
                 return matchesKecKel;
                }
                return false;
            });*/
            // DataTable custom search function for year filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const tahun = parseInt(data[4]) || 0;
                const tableKab = data[1] || "";
                const tableKec = data[2] || "";
                const tableKel = data[3] || "";
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);
                const kab = $('#kabupaten').val();
                const kec = $('#kecamatan').val();
                const kel = $('#kelurahan').val();

                // Filter berdasarkan tahun
                const inYearRange = (!startYear || tahun >= startYear) && (!endYear || tahun <= endYear);

                const matchesKab = !kab || kab === tableKab;
                const matchesKec = !kec || kec === tableKec;
                const matchesKel = !kel || kel === tableKel;

                // Kembalikan true jika memenuhi salah satu filter
                return inYearRange && matchesKab && matchesKec && matchesKel;
            });
        });
    </script>
@endsection
