@extends('layouts.base')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                    <div class="d-flex justify-content-end mb-5">
                        <a href="{{ route('IMBIndukPerum.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                        <button type="button" class="ml-2 btn btn-info text-white" data-toggle="modal"
                            data-target="#importDataModal">
                            Import Data
                        </button>
                        <a class="btn btn-warning ml-2" href="{{ route('IMBIndukPerum.export') }}">
                            Export Data
                        </a>
                        <a class="btn btn-success ml-2" href="{{ route('IMBIndukPerum.download-template') }}">
                            Download Template
                        </a>
                    </div>
                    @if (!empty(Session::get('failures')))
                        <div class="alert alert-danger mt-2">
                            <h6>Import data berhasil, namun terdapat kesalahan baris (data baris ini akan dimasukan ke
                                _lama), berikut baris yang gagal diimport:</h6>
                            <ul>
                                @foreach (Session::get('failures') as $failure)
                                    <li>Baris ke-{{ $failure['baris'] }}: {{ $failure['message'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <br>
                    <div class="table-responsive py-3 ">
                        <div class="filters py-3 row mb-5" style="margin-bottom: 10px">
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-atas_nama" class="form-label">Atas Nama:</label> --}}
                                <input type="text" class="form-control" id="filter-atas_nama" placeholder="Atas Nama">
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-lokasi" class="form-label">Lokasi Perumahan:</label> --}}
                                <input type="text" class="form-control" id="filter-lokasi"
                                    placeholder="Lokasi Perumahan">
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-kabupaten" class="form-label">Kabupaten:</label> --}}
                                <select class="form-control select2-kabupaten" id="filter-kabupaten"
                                    placeholder="Kabupaten">
                                    <option value="" disabled selected>Pilih Kabupaten</option>
                                    <!-- Tambahkan opsi kabupaten di sini -->
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-kecamatan" class="form-label">Kecamatan:</label> --}}
                                <select class="form-control select2-kecamatan" id="filter-kecamatan"
                                    placeholder="Kecamatan">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                    <!-- Tambahkan opsi kecamatan di sini -->
                                </select>
                            </div>


                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                {{-- <label for="filter-kelurahan" class="form-label">Kelurahan:</label> --}}
                                <select class="form-control select2-kelurahan" id="filter-kelurahan"
                                    style="margin-bottom: 10px" placeholder="Kelurahan">
                                    <option value="" disabled selected>Pilih Kelurahan</option>
                                    <!-- Tambahkan opsi kelurahan di sini -->
                                </select>
                            </div>
                        </div>
                        <br>
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
                                    <th>Kabupaten/Kota</th>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>



    <script>
        var table = $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('IMBIndukPerum.index') }}",
                data: function(d) {
                    d.kabupaten = $('#filter-kabupaten').val(); // ID kabupaten dari select2
                    d.kecamatan = $('#filter-kecamatan').val(); // ID kecamatan dari select2
                    d.kelurahan = $('#filter-kelurahan').val(); // ID kelurahan dari select2
                }
            },
            columns: [{
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
                    data: 'kabupaten'
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
                            <td>
                              ${item.scan_imb ?
                                `<a href="/imb/storage/${item.scan_imb}" download="${item.scan_imb}">${item.scan_imb}</a>`
                                : '-'}
                            </td>
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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


    <script>
        initializeSelect2WithAjax();

        function initializeSelect2WithAjax() {
            $('.select2-kabupaten').select2({
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
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function(e) {
                table.draw(); // Refresh the table when kabupaten is selected
                loadKecamatan(e.params.data.id)
            });

            loadKecamatan(document.querySelector('.select2-kabupaten').value)
            loadKelurahan(document.querySelector('.select2-kecamatan').value)

            function loadKecamatan(kabId) {
                // Kecamatan Select2 with AJAX
                $('.select2-kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    // minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kecamatan') }}", // URL to fetch kecamatan data
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
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kecamatan:", e.params.data);
                    table.draw(); // Refresh the table when kabupaten is selected

                    loadKelurahan(e.params.data.id); // Load kelurahan based on selected kecamatan
                });
            }

            // Kelurahan Select2 with AJAX
            function loadKelurahan(kecamatanId) {
                $('.select2-kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    //minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kelurahan') }}", // URL to fetch kelurahan data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kecamatan_id: kecamatanId, // Pass the selected kecamatan ID
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched kelurahan data:", data); // Check kelurahan data structure
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    table.draw(); // Refresh the table when kabupaten is selected
                });
            }
        }
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="importDataModal" tabindex="-1" aria-labelledby="importDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="importDataModalLabel">Import Data </h5>

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
