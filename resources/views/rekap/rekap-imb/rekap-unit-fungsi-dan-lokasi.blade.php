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
                                    <option
                                        value="{{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}">
                                        {{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}
                                    </option>
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
                                    <option
                                        value="{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}">
                                        {{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}
                                    </option>
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
                                {{-- <th rowspan="3">NO</th>
                                <th rowspan="3">KAB/KOTA</th>
                                <th rowspan="3">KECAMATAN</th>
                                <th rowspan="3">DESA/KEL</th>
                                <th rowspan="3">TAHUN</th>
                                <th rowspan="3">JUMLAH IMB</th>
                                <th rowspan="3">JUMLAH UNIT</th>
                                <th colspan="4" style="text-align: center" rowspan="2">JENIS IMB</th>
                                <th colspan="10" style="text-align: center">FUNGSI BANGUNAN</th> --}}
                                <th rowspan="3">No</th>
                                <th rowspan="3">Kab/Kota</th>
                                <th rowspan="3">Kecamatan</th>
                                <th rowspan="3">Desa/Kel</th>
                                <th rowspan="3">Tahun</th>
                                <th rowspan="3">Jumlah IMB</th>
                                <th rowspan="3">Jumlah Unit</th>
                                <th colspan="4" style="text-align: center" rowspan="2">Jenis IMB</th>
                                <th colspan="10" style="text-align: center">Fungsi Bangunan</th>
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
                                {{-- <td>{{ 'BOGOR' }}</td>
                                <td>{{ DB::table('master_district')->where('code', $row->kecamatan)->first()->name }}
                                </td>
                                <td>{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->first()->name }}
                                </td> --}}
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
            buttons: [{
                extend: 'copy',
                filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
            },
                {
                    extend: 'csv',
                    filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-'),
                    title: null
                },
                {
                    text: 'Excel',
                    action: function(e, dt, button, config) {
                        const workbook = XLSX.utils.book_new();
                        const sheetData = [
                            ['NO', 'KAB/KOTA', 'KECAMATAN', 'DESA/KEL', 'TAHUN', 'JUMLAH IMB', 'JUMLAH UNIT', 'JENIS IMB', '', '', '', 'FUNGSI BANGUNAN', '', '', '', '', '', '', '']
                        ];
                        sheetData.push(['', '', '', '', '', '', '', '', '', '', '', 'HUNIAN', '', 'USAHA', '', 'SOSIAL DAN BUDAYA', '', 'KHUSUS', '', 'CAMPURAN']);
                        sheetData.push(['', '', '', '', '', '', '', 'INDUK PERUMAHAN', 'PECAHAN',
                                                'PERLUASAN', 'INDUK NON PERUMAHAN', 'ITEM IMB', 'UNIT',
                                                'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT', 'ITEM IMB', 'UNIT',
                                                'ITEM IMB', 'UNIT'
                                            ]);
                        const totals = Array(21).fill(0)
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
                            ]);

                                // Accumulate totals for numerical columns (from index 2 to 17)
                                for (let i = 5; i <= 20; i++) {
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
                                s: { r: 0, c: 7 }, e: { r: 0, c: 8 }  // Merge INDUK PERUMAHAN and PECAHAN
                            },
                            {
                                s: { r: 0, c: 9 }, e: { r: 0, c: 9 }  // Merge PERLUASAN (single column)
                            },
                            {
                                s: { r: 0, c: 10 }, e: { r: 0, c: 10 }  // Merge INDUK NON PERUMAHAN
                            },
                            {
                                s: { r: 0, c: 11 }, e: { r: 0, c: 20 } // Merge FUNGSI BANGUNAN (columns 11 to 20)
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
                            { wpx: 100 }, // JUMLAH UNIT
                            { wpx: 120 }, // INDUK PERUMAHAN
                            { wpx: 120 }, // PECAHAN
                            { wpx: 120 }, // PERLUASAN
                            { wpx: 120 }, // INDUK NON PERUMAHAN
                            { wpx: 80 },  // HUNIAN IMB
                            { wpx: 80 },  // HUNIAN UNIT
                            { wpx: 80 },  // USAHA IMB
                            { wpx: 80 },  // USAHA UNIT
                            { wpx: 80 },  // SOSIAL IMB
                            { wpx: 80 },  // SOSIAL UNIT
                            { wpx: 80 },  // KHUSUS IMB
                            { wpx: 80 },  // KHUSUS UNIT
                            { wpx: 80 }   // CAMPURAN IMB
                        ];

                        // Add worksheet to workbook
                        XLSX.utils.book_append_sheet(workbook, worksheet, 'REKAP DATA');

                        // Save file
                        XLSX.writeFile(workbook, 'Export_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.xlsx');
                    }
                },
                {
                    extend: 'pdf',
                    filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-'),
                    title: null
                },
                {
                    extend: 'print',
                    title: ''
                }],
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
            $('#kabupaten').val('').trigger('change');
            $('#kecamatan').val('').trigger('change');
            $('#kelurahan').val('').trigger('change');

            getKecamatan()
            getKelurahan()

            table.draw();
        });
            // DataTable custom search function for year filtering
            /*$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
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

                // Filter berdasarkan kabupaten kecamatan dan kelurahan
                const matchesKab = !kab || kab === tableKab;
                const matchesKec = !kec || kec === tableKec;
                const matchesKel = !kel || kel === tableKel;

                // Kembalikan true jika memenuhi salah satu filter
                return inYearRange && matchesKab && matchesKec && matchesKel;
            });
    });
</script>
@endsection
