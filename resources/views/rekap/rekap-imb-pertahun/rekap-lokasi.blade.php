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
                <h3 class="text-3xl font-bold">REKAP LOKASI PERTAHUN</h3>
                <br />
                <div class="mb-4">
                    <form method="GET" action="{{ route('rekap.RekapLokasiPertahun') }}">
                        <div style="display:flex;gap:10px;">
                            <div>
                                <input type="number" name="year" id="year" class="form-control" placeholder="Tahun" value="{{ request('year') }}" />
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('rekap.RekapLokasiPertahun') }}" class="btn btn-secondary">Reset</a>
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
                                    <th rowspan="2">NO</th>
                                    <th rowspan="2">KAB/KOTA</th>
                                    <th rowspan="2">KECAMATAN</th>
                                    <th rowspan="2">DESA/KEL</th>
                                    <th rowspan="2">JUMLAH IMB</th>
                                    <th colspan="9" style="text-align: center">JENIS IMB</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ 'BOGOR' }}</td> {{-- Sesuaikan jika data kabupaten tersedia --}}
                                        <td>{{ DB::table('master_district')->where('code', $row->kecamatan)->value('name') }}</td>
                                        <td>{{ DB::table('master_subdistrict')->where('code', $row->desa_kelurahan)->value('name') }}</td>
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
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: left;">Total:</th>
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
                    data.forEach(function(rowData) {
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
