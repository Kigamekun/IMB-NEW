@extends('layouts.base')

@section('content')
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
                    <h3 class="text-3xl font-bold">Data IMB Pecahan</h3>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('IMBPecahan.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                        <button type="button" class="ml-2 btn btn-info text-white" data-toggle="modal"
                            data-target="#importDataModal">
                            Import Data
                        </button>
                        <a class="btn btn-warning ml-2" href="{{ route('IMBPecahan.export') }}">
                            Export Data
                        </a>
                        <a class="btn btn-success ml-2" href="{{ route('IMBPecahan.download-template') }}">
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
                                <input type="text" class="form-control" id="filter-atas_nama" style="margin-bottom: 10px;" placeholder="Atas Nama">
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
                                style="margin-top: 10px; margin-bottom:10px" placeholder="Kelurahan">
                                    <option value="" disabled selected>Pilih Kelurahan</option>
                                    <!-- Tambahkan opsi kelurahan di sini -->
                                </select>
                            </div>
                            <div class="mb-3 col-md-3" style=" margin-bottom:10px">
                                {{-- <label for="filter-blok" class="form-label">blok:</label> --}}
                                <input type="text" class="form-control" id="filter-blok" placeholder="Blok">
                            </div>
                            <div class="mb-3 col-md-3" >
                                {{-- <label for="filter-no_blok" class="form-label">no_blok:</label> --}}
                                <input type="text" class="form-control" id="filter-no_blok" placeholder="No Blok">
                            </div>
                            <div class="mb-3 col-md-3" >
                                {{-- <label for="filter-no_blok" class="form-label">no_blok:</label> --}}
                                <input type="text" class="form-control" id="filter-imb_induk_id" placeholder="IMB Induk">
                            </div>
                        </div>
                        <table class="table table-bordered" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>IMB Induk</th>
                                    <th>Tanggal IMB Induk</th>
                                    <th>IMB Pecahan</th>
                                    <th>Tanggal IMB Pecahan</th>
                                    <th>No Register</th>
                                    <th>Tanggal Register</th>
                                    <th>Nama</th>
                                    <th>Atas Nama</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Lokasi Perumahan</th>
                                    <th>Kabupaten/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Desa/Kelurahan</th>
                                    <th>Type</th>
                                    <th>Luas</th>
                                    <th>Blok</th>
                                    <th>No Blok</th>
                                    <th>Keterangan</th>
                                    <th>Scan IMB</th>
                                    <th>Action</th>
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

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>

    <script>
        var table = $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('IMBPecahan.index') }}",
                data: function(d) {
                    d.kabupaten = $('#filter-kabupaten').val(); // ID kabupaten dari select2
                    d.kecamatan = $('#filter-kecamatan').val(); // ID kecamatan dari select2
                    d.kelurahan = $('#filter-kelurahan').val(); // ID kelurahan dari select2
                },
                dataSrc: function(res) {
                    if (res.code == 5500) {
                        console.log(res.data);
                        return InternalServerEror()
                    } else {
                        console.log(res.data);
                        return res.data
                    }
                },
                error: function() {
                    // return InternalServerEror()
                    console.log("Server error occurred.");
                    alert("Server error occurred. Please try again later.");
                }
            },
            columns: [{
                    data: 'imb_induk_id',
                    title: 'IMB Induk'
                },
                {
                    data: 'tgl_imb_induk',
                    title: 'Tanggal IMB Induk'
                },
                {
                    data: 'imb_pecahan',
                    title: 'IMB Pecahan'
                },
                {
                    data: 'tgl_imb_pecahan',
                    title: 'Tanggal IMB Pecahan'
                },
                {
                    data: 'no_register',
                    title: 'No Register'
                },
                {
                    data: 'tgl_register',
                    title: 'Tanggal Register'
                },
                {
                    data: 'nama',
                    title: 'Nama'
                },
                {
                    data: 'atas_nama',
                    title: 'Atas Nama'
                },
                {
                    data: 'jenis_kegiatan',
                    title: 'Jenis Kegiatan'
                },
                {
                    data: 'lokasi_perumahan',
                    title: 'Lokasi Perumahan'
                },
                {
                    data: 'kabupaten',
                    title: 'Kabupaten/Kota'
                },
                {
                    data: 'kecamatan',
                    title: 'Kecamatan'
                },
                {
                    data: 'kelurahan',
                    title: 'Desa/Kelurahan'
                },
                {
                    data: 'type',
                    title: 'Type'
                },
                {
                    data: 'luas',
                    title: 'Luas'
                },
                {
                    data: 'blok',
                    title: 'Blok'
                },
                {
                    data: 'no_blok',
                    title: 'No Blok'
                },
                {
                    data: 'keterangan',
                    title: 'Keterangan'
                },
                {
                    data: 'scan_imb',
                    title: 'Scan IMB'
                },
                {
                    data: 'action',
                    title: 'Action'
                }
            ],
            order: [
                [2, 'desc']
            ]
        });

        $('#filter-lokasi').on('keyup', function() {
            table.column(9).search(this.value).draw(); // 8 is the index for 'lokasi_perumahan'
        });

        // Custom filtering for 'Atas Nama'
        $('#filter-atas_nama').on('keyup', function() {
            table.column(7).search(this.value).draw(); // 7 is the index for 'atas_nama'
        });

        // Custom filtering for 'Blok'
        $('#filter-blok').on('keyup', function() {
            table.column(15).search(this.value).draw(); // 13 is the index for 'blok'
        });

        // Custom filtering for 'No Blok'
        $('#filter-no_blok').on('keyup', function() {
            table.column(16).search(this.value).draw(); // 14 is the index for 'no_blok'
        });

        // Custom filtering for 'IMB Induk'
        $('#filter-imb_induk_id').on('keyup', function() {
            table.column(0).search(this.value).draw(); // 0 is the index for 'imb_induk_id'
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
                <form action="{{ route('IMBPecahan.import') }}" method="POST" enctype="multipart/form-data">
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
