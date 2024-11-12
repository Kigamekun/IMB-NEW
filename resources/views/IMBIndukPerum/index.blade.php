@extends('layouts.base')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
    <style>
        th {
            white-space: nowrap;
        }
    </style>
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Data IMB Induk Perum</h3>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('IMBIndukPerum.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                        <button type="button" class="ml-2 btn btn-info text-white" data-bs-toggle="modal"
                            data-bs-target="#importDataModal">
                            Import Data
                        </button>
                        <a class="btn btn-warning ml-2" href="{{ route('IMBIndukPerum.export') }}">
                            Export Data
                        </a>
                        <a class="btn btn-success ml-2" href="{{ route('IMBIndukPerum.download-template') }}">
                            Download Template
                        </a>
                    </div>
                    <br>
                    @if (!empty(Session::get('failures')))
                        <div class="alert alert-danger">
                            <h6>Import data gagal, berikut baris yang gagal diimport:</h6>
                            <ul>
                                @foreach (Session::get('failures') as $failure)
                                    <li>Baris ke-{{ $failure['baris'] }}: {{ $failure['message'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <br>
                    <div class="table-responsive py-3">
                        <div class="filters py-3 row">
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-atas_nama" class="form-label">Atas Nama:</label> --}}
                                <input type="text" class="form-control" id="filter-atas_nama"
                                    placeholder="Filter Atas Nama">
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-lokasi" class="form-label">Lokasi Perumahan:</label> --}}
                                <input type="text" class="form-control" id="filter-lokasi"
                                    placeholder="Filter Lokasi Perumahan">
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-kecamatan" class="form-label">Kecamatan:</label> --}}
                                <input type="text" class="form-control" id="filter-kecamatan"
                                    placeholder="Filter Kecamatan">
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-kelurahan" class="form-label">Kelurahan:</label> --}}
                                <input type="text" class="form-control" id="filter-kelurahan"
                                    placeholder="Filter Kelurahan">
                            </div>
                        </div>
                        <table id="IMBTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Item</th>
                                    <th>IMB Induk</th>
                                    <th>Tgl. IMB Induk</th>
                                    <th>No. Register</th>
                                    <th>Tgl. Register</th>
                                    <th>Nama</th>
                                    <th>Atas Nama</th>
                                    <th>Lokasi / Perumahan</th>
                                    <th>Kecamatan</th>
                                    <th>Desa / Kelurahan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script src="{{ url('assets/js/dataTables.rowsGroup.js') }}"></script>
    <script>
        var table = $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('IMBIndukPerum.index') }}",
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-primary show-items-btn btn-sm" data-id="${row.id}"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/></svg></button>`;
                    }
                },
                {
                    data: 'imb_induk'
                },
                {
                    data: 'tgl_imb_induk'
                },
                {
                    data: 'no_register'
                },
                {
                    data: 'tgl_register'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'atas_nama'
                },
                {
                    data: 'lokasi_perumahan'
                },
                {
                    data: 'kecamatan'
                },
                {
                    data: 'kelurahan'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            pageLength: 20
        });

        $('#filter-lokasi').on('keyup', function() {
            table.column(8).search(this.value).draw(); // 8 is the index for 'lokasi_perumahan'
        });

        // Custom filtering for 'Kecamatan'
        $('#filter-kecamatan').on('keyup', function() {
            table.column(9).search(this.value).draw(); // 9 is the index for 'kecamatan'
        });

        // Custom filtering for 'Kelurahan'
        $('#filter-kelurahan').on('keyup', function() {
            table.column(10).search(this.value).draw(); // 10 is the index for 'kelurahan'
        });

        // Custom filtering for 'Atas Nama'
        $('#filter-atas_nama').on('keyup', function() {
            table.column(7).search(this.value).draw(); // 7 is the index for 'atas_nama'
        });
    </script>

    <script>
        $(document).on('click', '.show-items-btn', function() {
            var recordId = $(this).data('id'); // Get the record ID
            var row = $(this).closest('tr'); // Get the closest row of the clicked button
            if (row.next().hasClass('item-row')) {
                row.next().remove();
                return; // Stop the function if the items row already exists
            }
            $.ajax({
                url: '{{ route('IMBIndukPerum.items') }}' + '?id=' +
                    recordId, // Define a route to fetch items by record ID
                method: 'GET',
                success: function(data) {
                    var itemsHtml = '';
                    console.log(data);
                    data.forEach(function(item) {
                        itemsHtml += `<tr>
                            <td>${item.jenis_kegiatan}</td>
                            <td>${item.fungsi_bangunan}</td>
                            <td>${item.type}</td>
                            <td>${item.luas_bangunan}</td>
                            <td>${item.jumlah_unit}</td>
                            <td>${item.keterangan}</td>
                            <td>${item.scan_imb}</td>
                        </tr>`;
                    });
                    var newRow = `
                        <tr class="item-row">
                            <td colspan="11">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Jenis Kegiatan</th>
                                            <th>Fungsi Bangunan</th>
                                            <th>Type</th>
                                            <th>Luas Bangunan</th>
                                            <th>Jumlah Unit</th>
                                            <th>Keterangan</th>
                                            <th>Scan IMB</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${itemsHtml}
                                    </tbody>
                                </table>
                            </td>
                        </tr>
            `;
                    row.after(newRow);
                },
                error: function(error) {
                    console.log('Error fetching items:', error);
                }
            });
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="importDataModal" tabindex="-1" aria-labelledby="importDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="importDataModalLabel">Import Data </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('IMBIndukPerum.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control dropify" id="file" name="file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
