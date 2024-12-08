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
                    <h3 class="text-3xl font-bold">REGISTER SK IMBG PERBULAN</h3>
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
                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" id="SuratTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Tahun</th>
                                    <th>NO SK</th>
                                    <th>TANGGAL</th>
                                    <th>PEMOHON</th>
                                    <th>ATAS NAMA</th>
                                    <th>NO REGISTER</th>
                                    <th>NO IMBG</th>
                                    <th>LOKASI BANGUNAN</th>
                                    <th>KAB. / KOTA</th>
                                    <th>KECAMATAN</th>
                                    <th>DESA / KEL.</th>
                                    <th>JENIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td id="nomorSurat">1</td>
                                    <td id="tahun">1980</td>
                                    <td id="tanggallSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">1980</td>
                                    <td id="nomorSurat">21,300</td>
                                    <td id="nomorSurat">1,000</td>
                                </tr> --}}
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
            $('#kecamatan').select2()
            $('#kelurahan').select2()
            $.get("{{ route('rekap.ListSurat') }}", function(data) {
             $.each(data.data, function(key, val) {
               // alert(val.nama_kecamatan)
               $("#kecamatan").append("<option value='" + val.nama_kecamatan + "'>" + val.nama_kecamatan + "</option>");
               $("#kelurahan").append("<option value='" + val.nama_kelurahan + "'>" + val.nama_kelurahan + "</option>");
              })
            })
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
                    }
                ],
                processing: true,
                serverside: true,
                ajax: {
                    url: "{{ route('rekap.ListSurat') }}",
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
                    { data: 'tahun' },
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
                order: [
                    [2, 'desc']
                ]
            });


            // Filter button functionality
            $('#filterButton').on('click', function() {
                table.draw(); // Trigger DataTable redraw with filter
            });

            // Reset button functionality
            $('#resetButton').on('click', function() {
                $('#kecamatan').val('');
                $('#kelurahan').val('');
                table.draw();
            });

            // DataTable custom search function for year filtering
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
             const tableKec = data[10] || "";
             const tableKel = data[11] || "";
             const kec = $('#kecamatan').val() ? $('#kecamatan').val() : "";
             const kel = $('#kelurahan').val() ? $('#kelurahan').val() : "";
             if ((!kec || kec === tableKec) && (!kel || kel === tableKel)) {
               return true;
            }
           return false;
            });
        });
    </script>
@endsection
