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
                    <h3 class="text-3xl font-bold">Data Rekap</h3>
                    <br />
                    <div class="mb-4">
                        <form id="filterForm" class="form-inline">
                            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                <div>
                                    <input type="number" id="berkas-masuk" class="form-control"
                                        placeholder="Berkas masuk" />
                                </div>
                                <div>
                                    <input type="number" id="jumlah-surat" class="form-control"
                                        placeholder="Jumlah surat" />
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
                                    <th>BULAN</th>
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
                                            {{ $item->BULAN }}
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
                    // Hitung total kolom manual
                    const columns = [
                        'total-berkas-masuk',
                        'total-jumlah-surat',
                        'total-pemutakhiran',
                        'total-balik-nama',
                        'total-sk-hilang',
                        'total-balik-sk-hilang',
                        'total-pecah-balik',
                        'total-legalisir',
                        'total-format-baru',
                    ];

                    columns.forEach((id, index) => {
                        let total = 0;
                        $('#IMBTable tbody tr').each(function() {
                            const cellValue = parseInt($(this).find(
                                `td:nth-child(${index + 2})`).text().replace(
                                /,/g, '') || 0);
                            total += cellValue;
                        });

                        $(`#${id}`).text(total.toLocaleString());
                    });
                }
            });

            // Filter dan reset tombol tetap sama
            $('#filterButton').on('click', function() {
                const berkasMasuk = parseInt($('#berkas-masuk').val());
                const jumlahSurat = parseInt($('#jumlah-surat').val());
                table.draw();
            });

            $('#resetButton').on('click', function() {
                $('#berkas-masuk').val('');
                $('#jumlah-surat').val('');
                table.draw();
            });

            // Filter manual
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const tableBM = parseInt(data[1]) || 0;
                const tableJS = parseInt(data[2]) || 0;
                const berkasMasuk = parseInt($('#berkas-masuk').val());
                const jumlahSurat = parseInt($('#jumlah-surat').val());

                if ((berkasMasuk === tableBM) && (jumlahSurat === tableJS)) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
