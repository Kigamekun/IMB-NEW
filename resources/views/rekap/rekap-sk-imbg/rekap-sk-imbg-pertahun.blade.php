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
                    <h3 class="text-3xl font-bold">REKAP SURAT KETERANGAN IMBG PERTAHUN</h3>
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
                                    <th>NO</th>
                                    <th>TAHUN</th>
                                    <th>BERKAS MASUK</th>
                                    <th>JUMLAH SURAT</th>
                                    <th>PEMUTAKHIRAN DATA</th>
                                    <th>BALIK NAMA</th>
                                    <th>PENGGANTI SK IMBG HILANG</th>
                                    <th>BALIK NAMA & PENGGANTI SK IMBG HILANG</th>
                                    <th>PECAH & BALIK NAMA</th>
                                    <th>LEGALISIR</th>
                                    <th>FORMAT BARU</th>
                                    <th>LINTAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('rekap.RekapSuratPerbulan', ['tahun' => $item->TAHUN]) }}">
                                                {{ $item->TAHUN }}
                                            </a>
                                        </td>
                                        <td>{{ $item->BERKAS_MASUK }}</td>
                                        <td>{{ $item->JUMLAH_SURAT }}</td>
                                        <td>{{ $item->PEMUTAKHIRAN_DATA }}</td>
                                        <td>{{ $item->BALIK_NAMA }}</td>
                                        <td>{{ $item->PENGGANTI_SK_IMBG_HILANG }}</td>
                                        <td>{{ $item->BALIK_NAMA_PENGGANTI_SK_IMBG_HILANG }}</td>
                                        <td>{{ $item->PECAH_BALIK_NAMA }}</td>
                                        <td>{{ $item->LEGALISIR }}</td>
                                        <td>{{ $item->FORMAT_BARU }}</td>
                                        <td>{{ $item->LINTAS }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th id="total-berkas-masuk"></th>
                                    <th id="total-jumlah-surat"></th>
                                    <th id="total-pemutakhiran-data"></th>
                                    <th id="total-balik-nama"></th>
                                    <th id="total-pengganti-sk-imbg-hilang"></th>
                                    <th id="total-balik-nama-pengganti-sk-imbg-hilang"></th>
                                    <th id="total-pecah-balik-nama"></th>
                                    <th id="total-legalisir"></th>
                                    <th id="total-format-baru"></th>
                                    <th id="total-lintas"></th>
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
                buttons: [{
                        extend: 'copy',
                        filename: 'Copy_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-')
                    },
                    {
                        extend: 'csv',
                        filename: 'CSVExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-')
                    },
                    {
                        extend: 'excel',
                        filename: 'ExcelExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-')
                    },
                    {
                        extend: 'pdf',
                        filename: 'PDFExport_' + new Date().toISOString().slice(0, 19).replace(/:/g,
                            '-')
                    },
                    {
                        extend: 'print',
                        title: ''
                    }
                ],
                drawCallback: function() {
                    // Hitung total manual untuk setiap kolom
                    const columns = [
                        'total-no-sk',
                        'total-tanggal',
                        'total-pemohon',
                        'total-atas-nama',
                        'total-no-register',
                        'total-no-imbg',
                        'total-lokasi-bangunan',
                        'total-kab-kota',
                        'total-kecamatan',
                        'total-desa-kel',
                        'total-jenis',
                    ];

                    columns.forEach((id, index) => {
                        let total = 0;
                        $('#IMBTable tbody tr').each(function() {
                            const cellValue = $(this).find(`td:nth-child(${index + 2})`)
                                .text().replace(/,/g, '') || 0;
                            total += parseInt(cellValue);
                        });

                        // Update total di tfoot
                        $(`#${id}`).text(total.toLocaleString());
                    });
                },
                footerCallback: function(row, data, start, end, display) {
                    let totalBerkasMasuk = 0;
                    let totalJumlahSurat = 0;
                    let totalPemutakhiranData = 0;
                    let totalBalikNama = 0;
                    let totalPenggantiSKIMBGHilang = 0;
                    let totalBalikNamaPengganti = 0;
                    let totalPecahBalikNama = 0;
                    let totalLegalisir = 0;
                    let totalFormatBaru = 0;
                    let totalLintas = 0;

                    // Iterasi data untuk menghitung total
                    display.forEach(function(idx) {
                        const rowData = data[idx]
                        totalBerkasMasuk += parseInt(rowData[2] || 0);
                        totalJumlahSurat += parseInt(rowData[3].replace(/,/g, '') || 0);
                        totalPemutakhiranData += parseInt(rowData[4].replace(/,/g, '') || 0);
                        totalBalikNama += parseInt(rowData[5].replace(/,/g, '') || 0);
                        totalPenggantiSKIMBGHilang += parseInt(rowData[6].replace(/,/g, '') ||
                            0);
                        totalBalikNamaPengganti += parseInt(rowData[7].replace(/,/g, '') || 0);
                        totalPecahBalikNama += parseInt(rowData[8].replace(/,/g, '') || 0);
                        totalLegalisir += parseInt(rowData[9].replace(/,/g, '') || 0);
                        totalFormatBaru += parseInt(rowData[10].replace(/,/g, '') || 0);
                        totalLintas += parseInt(rowData[11].replace(/,/g, '') || 0);
                    });

                    // Update nilai total di footer
                    $('#total-berkas-masuk').text(totalBerkasMasuk.toLocaleString());
                    $('#total-jumlah-surat').text(totalJumlahSurat.toLocaleString());
                    $('#total-pemutakhiran-data').text(totalPemutakhiranData.toLocaleString());
                    $('#total-balik-nama').text(totalBalikNama.toLocaleString());
                    $('#total-pengganti-sk-imbg-hilang').text(totalPenggantiSKIMBGHilang
                        .toLocaleString());
                    $('#total-balik-nama-pengganti-sk-imbg-hilang').text(totalBalikNamaPengganti
                        .toLocaleString());
                    $('#total-pecah-balik-nama').text(totalPecahBalikNama.toLocaleString());
                    $('#total-legalisir').text(totalLegalisir.toLocaleString());
                    $('#total-format-baru').text(totalFormatBaru.toLocaleString());
                    $('#total-lintas').text(totalLintas.toLocaleString());
                }
            });


            // Filter button functionality
            $('#filterButton').on('click', function() {
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);

                table.draw(); // Trigger DataTable redraw with filter
            });

            // Reset button functionality
            $('#resetButton').on('click', function() {
                $('#startYear').val('');
                $('#endYear').val('');
                $('#kecamatan').val('').trigger("change");
                $('#kelurahan').val('').trigger("change");
                table.draw(); // Reset and redraw table
            });

            // Custom filter for year range
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const year = parseInt(data[1]) || 0; // Assume column 1 is 'TAHUN'
                const startYear = parseInt($('#startYear').val(), 10);
                const endYear = parseInt($('#endYear').val(), 10);

                return (!startYear || year >= startYear) && (!endYear || year <= endYear);
            });
        });
    </script>
@endsection
