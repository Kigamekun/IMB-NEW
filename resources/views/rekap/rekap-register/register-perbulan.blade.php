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
                    <h3 class="text-3xl font-bold">REGISTER SURAT KETERANGAN IMBG PERBULAN</h3>
                    <div class="mb-4">
                        <form id="filterForm" class="form-inline">
                            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                               <!-- <div>
                                    <input type="number" id="startYear" class="form-control" placeholder="Tahun Awal" />
                                </div>
                                <div>
                                    <input type="number" id="endYear" class="form-control" placeholder="Tahun Akhir" />
                                </div> -->
                                <div>
                                    <select value="" class="form-control" id="kabupaten">
                                        <option value="" hidden>Pilih kabupaten</option>
                                      {{--  @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}">{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}</option>
                                      @endforeach --}}
                                    </select>
                                </div>
                                <div>
                                    <select value="" class="form-control" id="kecamatan">
                                        <option value="" hidden>Pilih kecamatan</option>
                                        {{-- @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}">{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}</option>
                                      @endforeach --}}
                                    </select>
                                </div>
                                <div>
                                    <select name="" value="" class="form-control" id="kelurahan">
                                        <option value="" hidden>Pilih kelurahan</option>
                                        {{-- @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}">{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}</option>
                                      @endforeach --}}
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
                        <table class="table table-bordered" style="width: 100% !important; border-bottom:none !important;" id="SuratTable">
                            <thead>
                                <tr style="text-align: justify;">
                                    <th style="text-align: center; vertical-align:middle">NO</th>
                                    {{-- <th style="text-align: center; vertical-align:middle">Tahun</th> --}}
                                    <th style="text-align: center; vertical-align:middle">NO SK</th>
                                    <th style="text-align: center; vertical-align:middle">TANGGAL</th>
                                    <th style="text-align: center; vertical-align:middle">PEMOHON</th>
                                    <th style="text-align: center; vertical-align:middle">ATAS NAMA</th>
                                    <th style="text-align: center; vertical-align:middle">NO REGISTER</th>
                                    <th style="text-align: center; vertical-align:middle">NO IMBG</th>
                                    <th style="text-align: center; vertical-align:middle">LOKASI BANGUNAN</th>
                                    <th style="text-align: center; vertical-align:middle">KAB./KOTA</th>
                                    <th style="text-align: center; vertical-align:middle">KECAMATAN</th>
                                    <th style="text-align: center; vertical-align:middle">DESA/KEL.</th>
                                    <th style="text-align: center; vertical-align:middle">JENIS</th>
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


            const table = $('#SuratTable').DataTable({
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
                    },

                ],
                processing: true,
                serverside: true,
                ajax: {
                    url: "{{ route('rekap.ListSurat10') }}",
                    data: function(d) {
                        d.nomor_surat = $('#nomorSurat').val();
                        d.nama_pemohon = $('#namaPemohonSurat').val();
                        d.lokasi_bangunan = $('#lokasiPemohonSurat').val();
                        d.kecamatan_pemohon = $('#kecamatanPemohonSurat').val();
                        d.kelurahan_pemohon = $('#kelurahanPemohonSurat').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    // { data: 'tahun' },
                    { data: 'nomorSurat' },
                    { data: 'tanggalSurat' },
                    { data: 'nama' },
                    { data: 'bertindak_atas_nama' },
                    // { data: 'alamat' },
                    { data: 'registerNomor' },
                    { data: 'imbgNomor' },
                    { data: 'lokasi' },
                    { data: 'nama_kabupaten' },
                    { data: 'nama_kecamatan' },
                    { data: 'nama_kelurahan' },
                    {
                        data: 'tujuanSurat',
                        render: function(data, type, row) {
                            if (!data) {
                                return row.nama_kabupaten === 'KAB. BOGOR' ? 'Format Baru' : 'Lintas';
                            }
                            return data;
                        }
                    }
                    // {
                    //     data: null,
                    //     render: function(data, type, row) {
                    //         return row.tujuanSurat ? row.tujuanSurat : row.jenisKegiatan;
                    //     }
                    // }
                    // { data: 'jenisSurat' },
                    // { data: 'action', orderable: false, searchable: false }
                ],
                // order: [
                //     [1, 'asc']
                // ]
            });


            // Filter button functionality
            $('#filterButton').on('click', function() {
                table.draw(); // Trigger DataTable redraw with filter
            });

            // Reset button functionality
            $('#resetButton').on('click', function() {
                $('#kabupaten').val('').trigger('change');
                $('#kecamatan').val('').trigger('change');
                $('#kelurahan').val('').trigger("change");
                getKecamatan()
                getKelurahan()
                table.draw();
            });

           $.fn.dataTable.ext.search.push(function(settings, data, dataIndex){
             const tableKab = data[8] || "";
             const tableKec = data[9] || "";
             const tableKel = data[10] || "";
             const kab = $('#kabupaten').val() ? $('#kabupaten').val() : "";
             const kec = $('#kecamatan').val() ? $('#kecamatan').val() : "";
             const kel = $('#kelurahan').val() ? $('#kelurahan').val() : "";
             if ((!kab || kab === tableKab) && (!kec || kec === tableKec) && (!kel || kel === tableKel)) {
               return true;
            }
           return false;
         });

        //  setTimeout(() => {
        //      $.get("{{ route('master.kabupaten') }}", function(data) {
        //          $.each(data.data, function(key, val) {
        //             // alert(val.nama_kecamatan)
        //            $("#kabupaten").append("<option value='" + val.nama_kabupaten + "'>" + val.nama_kabupaten + "</option>");
        //            $("#kecamatan").append("<option value='" + val.nama_kecamatan + "'>" + val.nama_kecamatan + "</option>");
        //            $("#kelurahan").append("<option value='" + val.nama_kelurahan + "'>" + val.nama_kelurahan + "</option>");
        //           })
        //         })
        //  }, 3000);

        });
    </script>
@endsection
