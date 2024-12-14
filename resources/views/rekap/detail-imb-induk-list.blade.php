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
                    <div class="table-responsive py-3">
                        <h2>Data</h2>
                        <br />
                       <!-- <div class="mb-4">
                            <form id="filterForm" class="form-inline">
                                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                    <div>
                                        <input type="number" id="startYear" name="" class="form-control"
                                            placeholder="Tahun" />
                                    </div>
                                    {{-- <div>
                                        <input type="number" id="endYear" class="form-control"
                                            placeholder="Tahun Akhir" />
                                    </div> --}}
                                    {{--   <div>
                                        <select name="" value="" class="form-control" id="kecamatan">
                                            <option value="" hidden>Pilih kecamatan</option>

                                        </select>
                                    </div>
                                    <div>
                                        <select name="" value="" class="form-control" id="kelurahan">
                                            <option value="" hidden>Pilih kelurahan</option>

                                        </select>
                                    </div> --}}
                                    <div>
                                        <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                                        <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div> -->
                        <table id="table2" class="table table-bordered"
                            style="width: 100% !important;border-bottom:none !important;" class="display">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TAHUN</th>
                                    <th>NAMA PENGEMBANG</th>
                                    <th>JUMLAH IMB</th>
                                    <th>IMB INDUK PERUMAHAN</th>
                                    <th>IMB PECAHAN</th>
                                    <th>IMB PERLUASAN</th>
                                    <th>IMB INDUK NON PERUMAHAN (PERUSAHAAN)</th>
                                    <th>IMB INDUK NON PERUMAHAN (PERORANGAN)</th>
                                    <th>IMB INDUK NON PERUMAHAN (SOSIAL DAN BUDAYA)</th>
                                    <th>IMB PEMUTIHAN</th>
                                    <th>IMB BERSYARAT</th>
                                    <th>IMB LAINNYA</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th id="totalJumlahIMB"></th>
                                    <th id="totalIndukPerumahan"></th>
                                    <th id="totalPecahan"></th>
                                    <th id="totalPerluasan"></th>
                                    <th id="totalNonPerumahan"></th>
                                    <th id="totalPerorangan"></th>
                                    <th id="totalSosbud"></th>
                                    <th id="totalPemutihan"></th>
                                    <th id="totalBersyarat"></th>
                                    <th id="totalLainnya"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="backup-nama-perumahan"></div>
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
    <script>
        const arrayTahun = []
        const table = $('#table2').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
                data: function(d) {
                    d.nama_perumahan = $('#backup-nama-perumahan').text();
                    d.startYear = $('#startYear').val();
                    d.endYear = $('#endYear').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tahun',
                    orderable: false,
                    searchable: false,
                    id: "tahun"
                },
                {
                    data: 'NAMA_PENGEMBANG',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'jumlah_imb',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_induk_perumahan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_pecahan',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'imb_perluasan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_non_perusahaan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_perorangan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_sosbud',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_pemutihan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_bersyarat',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'imb_lainnya',
                    orderable: false,
                    searchable: false
                },
            ],
            pageLength: 20,
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
            footerCallback: function(row, data, start, end, display) {
                // Inisialisasi total untuk setiap kolom
                let totalJumlahIMB = 0;
                let totalIndukPerumahan = 0;
                let totalPecahan = 0;
                let totalPerluasan = 0;
                let totalNonPerumahan = 0;
                let totalPerorangan = 0;
                let totalSosbud = 0;
                let totalPemutihan = 0;
                let totalBersyarat = 0;
                let totalLainnya = 0;

                // Iterasi melalui data untuk menghitung total
                display.forEach(function(idx) {
                    // console.log(data)
                    const rowData = data[idx];
                    arrayTahun.push(rowData.tahun)

                    // Pastikan kita mengakses data di kolom yang benar, misalnya untuk 'jumlah_imb' (kolom ke-3) adalah rowData[3]
                    totalJumlahIMB += parseFloat(rowData.jumlah_imb) || 0; // Kolom Jumlah IMB
                    totalIndukPerumahan += parseFloat(rowData.imb_induk_perumahan) ||
                        0; // Kolom Induk Perumahan
                    totalPecahan += parseFloat(rowData.imb_pecahan) || 0; // Kolom Pecahan
                    totalPerluasan += parseFloat(rowData.imb_perluasan) || 0; // Kolom Perluasan
                    totalNonPerumahan += parseFloat(rowData.imb_non_perumahan) ||
                        0; // Kolom Non Perumahan
                    totalPerorangan += parseFloat(rowData.imb_perorangan) || 0; // Kolom Perorangan
                    totalSosbud += parseFloat(rowData.imb_sosbud) || 0; // Kolom Sosbud
                    totalPemutihan += parseFloat(rowData.imb_pemutihan) || 0; // Kolom Pemutihan
                    totalBersyarat += parseFloat(rowData.imb_bersyarat) || 0; // Kolom Bersyarat
                    totalLainnya += parseFloat(rowData.imb_lainnya) || 0; // Kolom Lainnya
                });

                // Update elemen footer dengan total nilai
                $('#totalJumlahIMB').text(totalJumlahIMB.toFixed(
                    0)); // Menampilkan total dalam format tanpa desimal
                $('#totalIndukPerumahan').text(totalIndukPerumahan.toFixed(0));
                $('#totalPecahan').text(totalPecahan.toFixed(0));
                $('#totalPerluasan').text(totalPerluasan.toFixed(0));
                $('#totalNonPerumahan').text(totalNonPerumahan.toFixed(0));
                $('#totalPerorangan').text(totalPerorangan.toFixed(0));
                $('#totalSosbud').text(totalSosbud.toFixed(0));
                $('#totalPemutihan').text(totalPemutihan.toFixed(0));
                $('#totalBersyarat').text(totalBersyarat.toFixed(0));
                $('#totalLainnya').text(totalLainnya.toFixed(0));
            }

        });

        // Filter button functionality
        $('#filterButton').on('click', function() {
            const startYear = parseInt($('#startYear').val(), 10);

            // Filter logic
            table.draw(); // Trigger DataTable redraw with filter
        });

        // Reset button functionality
        $('#resetButton').on('click', function() {
            $('#startYear').val('');
            table.draw();
        });
        // $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        //     const tahun = parseInt(data[1], 10); // Kolom tahun (index 1)

        //     const startYear = parseInt($('#startYear').val(), 10);
        //     const endYear = parseInt($('#endYear').val(), 10);

        //     arrayTahun.forEach(function(item) {

        //         if (
        //             (!startYear || item >= startYear) &&
        //             (!endYear || item <= endYear)
        //         ) {
        //             return true;
        //         }
        //     })

        //     // Filter tahun

        //     return false;
        // });
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const startYear = parseInt($('#startYear').val(), 10);

            // Jika startYear kosong, tampilkan semua data
            if (isNaN(startYear)) {
                return false;
            }

            // Periksa apakah startYear ada di arrayTahun
            return arrayTahun.some(function(item) {
                return item === startYear;
            });
        });

    </script>
@endsection
