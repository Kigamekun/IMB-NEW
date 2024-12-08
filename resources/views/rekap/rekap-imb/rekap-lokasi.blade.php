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
                    <h3 class="text-3xl font-bold">REKAP LOKASI</h3>
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
                                    <select value="" class="form-control" id="kecamatan">
                                        <option value="" hidden>Pilih kecamatan</option>
                                        @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}">{{ DB::table('master_district')->where('code',$row->kecamatan)->first()->name }}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="" value="" class="form-control" id="kelurahan">
                                        <option value="" hidden>Pilih kelurahan</option>
                                        @foreach ($data as $index => $row)
                                        <option value="{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}">{{ DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name }}</option>
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
                                    <th>No</th>
                                    <th>Kabupaten / Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Desa / Kelurahan</th>
                                    <th>Tahun</th>
                                    <th>Jumlah IMB</th>
                                    <th>Induk Perumahan</th>
                                    <th>Pecahan</th>
                                    <th>Perluasan</th>
                                    <th>Non Perumahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ 'BOGOR' }}</td> {{-- Sesuaikan jika ada data kabupaten --}}
                                    <td>{{
                                    DB::table('master_district')->where('code',$row->kecamatan)->first()->name
                                    }}</td>
                                    <td>{{
                                    DB::table('master_subdistrict')->where('code',$row->desa_kelurahan)->first()->name

                                    }}</td>
                                    <td>{{ $row->tahun }}</td>
                                    <td>{{ $row->jumlah_imb }}</td>
                                    <td>{{ $row->imb_induk_perum }}</td>
                                    <td>{{ $row->imb_pecahan }}</td>
                                    <td>{{ $row->imb_perluasan }}</td>
                                    <td>{{ $row->imb_non_perumahan }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align: left;">Total:</th>
                                    <th id="totalJumlahIMB">0</th>
                                    <th id="totalIndukPerumahan">0</th>
                                    <th id="totalPecahan">0</th>
                                    <th id="totalPerluasan">0</th>
                                    <th id="totalNonPerumahan">0</th>
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kecamatan').select2()
            $('#kelurahan').select2()
            const table = $('#IMBTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'csv', filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'excel', filename: 'ExcelExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'pdf', filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') },
                    { extend: 'print', title: '' },
                ],
                footerCallback: function(row, data, start, end, display) {
                    // Inisialisasi total untuk setiap kolom
                    let totalJumlahIMB = 0;
                    let totalIndukPerumahan = 0;
                    let totalPecahan = 0;
                    let totalPerluasan = 0;
                    let totalNonPerumahan = 0;

                    // Iterasi melalui data untuk menghitung total
                    display.forEach(function(idx) {
                        const rowData = data[idx]
                        totalJumlahIMB += parseFloat(rowData[5]) || 0; // Kolom Jumlah IMB
                        totalIndukPerumahan += parseFloat(rowData[6]) || 0; // Kolom Induk Perumahan
                        totalPecahan += parseFloat(rowData[7]) || 0; // Kolom Pecahan
                        totalPerluasan += parseFloat(rowData[8]) || 0; // Kolom Perluasan
                        totalNonPerumahan += parseFloat(rowData[9]) || 0; // Kolom Non Perumahan
                    });

                    // Update elemen footer dengan total nilai
                    $('#totalJumlahIMB').text(totalJumlahIMB);
                    $('#totalIndukPerumahan').text(totalIndukPerumahan);
                    $('#totalPecahan').text(totalPecahan);
                    $('#totalPerluasan').text(totalPerluasan);
                    $('#totalNonPerumahan').text(totalNonPerumahan);
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
