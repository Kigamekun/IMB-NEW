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
                    <h3 class="text-3xl font-bold">Data IMB
                        @isset($_GET['type'])
                            @switch($_GET['type'])
                                @case('')
                                    Induk
                                @break

                                @case('pecahan')
                                    Pecahan
                                @break

                                @case('perluasan')
                                    Perluasan
                                @break

                                @case('non_perum')
                                    Induk Non Perum
                                @break

                                @default
                            @endswitch
                        @else
                            Induk
                        @endisset
                        Tidak Lengkap</h3>
                    <div class="d-flex justify-content-end">
                        @isset($_GET['type'])
                            @switch($_GET['type'])
                                @case('')
                                    <a href="{{ route('SinkronisasiLokasiIMB.hubungkan') }}" class="btn btn-primary">
                                        Hubungkan Data
                                    </a>
                                @break

                                @case('pecahan')
                                    <a href="{{ route('SinkronisasiLokasiIMB.hubungkan') }}?type=pecahan" type="button">
                                        Hubungkan Data
                                    </a>
                                @break

                                @case('perluasan')
                                    <a href="{{ route('SinkronisasiLokasiIMB.hubungkan') }}?type=perluasan" type="button">
                                        Hubungkan Data
                                    </a>
                                @break

                                @case('non_perum')
                                    <a href="{{ route('SinkronisasiLokasiIMB.hubungkan') }}?type=non_perum" type="button">
                                        Hubungkan Data
                                    </a>
                                @break

                                @default
                            @endswitch
                        @else
                            <a href="{{ route('SinkronisasiLokasiIMB.hubungkan') }}" class="btn btn-primary">
                                Hubungkan Data
                            </a>
                        @endisset
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
                        <table class="table table-bordered" id="IMBTable">
                            <thead>
                                @isset($_GET['type'])
                                    @switch($_GET['type'])
                                        @case('')
                                            <tr>
                                                <th>IMB Induk</th>
                                                <th>Tgl. IMB Induk</th>
                                                <th>No. Register</th>
                                                <th>Tgl. Register</th>
                                                <th>Nama</th>
                                                <th>Atas Nama</th>
                                                <th>Lokasi / Perumahan</th>
                                                <th>Kecamatan</th>
                                                <th>Desa / Kelurahan</th>
                                            </tr>
                                        @break

                                        @case('pecahan')
                                            <tr>
                                                <th>IMB Induk</th>
                                                <th>Tgl. IMB Induk</th>
                                                <th>IMB Pecahan</th>
                                                <th>Tgl. IMB Pecahan</th>
                                                <th>No. Register</th>
                                                <th>Tgl. Register</th>
                                                <th>Nama</th>
                                                <th>Atas Nama</th>
                                                <th>Jenis Kegiatan</th>
                                                <th>Lokasi / Perumahan</th>
                                                <th>Kecamatan</th>
                                                <th>Desa / Kelurahan</th>
                                                <th>Type</th>
                                                <th>Luas</th>
                                                <th>Blok</th>
                                                <th>No Blok</th>
                                                <th>Keterangan</th>
                                                <th>Scan IMB</th>
                                                <th>Action</th>
                                            </tr>
                                        @break

                                        @case('perluasan')
                                            <tr>
                                                <th>IMB Pecahan</th>
                                                <th>No IMB Perluasan</th>
                                                <th>Tgl IMB Perluasan</th>
                                                <th>No Register</th>
                                                <th>Tgl Register</th>
                                                <th>Nama</th>
                                                <th>Atas Nama</th>
                                                <th>Jenis Kegiatan</th>
                                                <th>Lokasi Perumahan</th>
                                                <th>Kecamatan</th>
                                                <th>Desa/Kelurahan</th>
                                                <th>Type</th>
                                                <th>Luas Bangunan Lama</th>
                                                <th>Luas Bangunan Perluasan</th>
                                                <th>Blok</th>
                                                <th>No Blok</th>
                                                <th>Keterangan</th>
                                                <th>Scan IMB</th>
                                                <th>Action</th>

                                            </tr>
                                        @break

                                        @case('non_perum')
                                            <tr>
                                                <th>IMB Induk Non Perum</th>
                                                <th>Tgl. IMB Induk Non Perum</th>
                                                <th>No. Register</th>
                                                <th>Tgl. Register</th>
                                                <th>Nama</th>
                                                <th>Atas Nama</th>
                                                <th>Lokasi / Perumahan</th>
                                                <th>Kecamatan</th>
                                                <th>Desa / Kelurahan</th>
                                                <th>Action</th>
                                            </tr>
                                        @break

                                        @default
                                    @endswitch
                                @else
                                    <tr>
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
                                @endisset

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>



    <script>
        const params = new URLSearchParams(window.location.search);
        type = params.get('type') || '';
        switch (type) {
            case '':
                $('#IMBTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('SinkronisasiLokasiIMB.index') }}?type=" + type,
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
                            return InternalServerEror()
                        }
                    },
                    columns: [{
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
                            data: 'kecamatan_lama'
                        },
                        {
                            data: 'kelurahan_lama'
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
                break;
            case 'pecahan':
                $('#IMBTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('SinkronisasiLokasiIMB.index') }}?type=" + type,
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
                            return InternalServerEror()
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
                            data: 'kecamatan_lama',
                            title: 'Kecamatan'
                        },
                        {
                            data: 'kelurahan_lama',
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
                break
            case 'perluasan':
                $('#IMBTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('SinkronisasiLokasiIMB.index') }}?type=" + type,
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
                            return InternalServerEror()
                        }
                    },
                    columns: [{
                            data: 'imb_pecahan',
                            title: 'IMB Induk'
                        },
                        {
                            data: 'imb_perluasan',
                            title: 'No IMB Perluasan'
                        },
                        {
                            data: 'tgl_imb_perluasan',
                            title: 'Tanggal IMB Perluasan'
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
                            data: 'kecamatan_lama',
                            title: 'Kecamatan'
                        },
                        {
                            data: 'kelurahan_lama',
                            title: 'Desa/Kelurahan'
                        },
                        {
                            data: 'type',
                            title: 'Type'
                        },
                        {
                            data: 'luas_bangunan_lama',
                            title: 'Luas Bangunan Lama'
                        },
                        {
                            data: 'luas_bangunan_perluasan',
                            title: 'Luas Bangunan Perluasan'
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
                break;
            case 'non_perum':
                $('#IMBTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('SinkronisasiLokasiIMB.index') }}?type=" + type,
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
                            return InternalServerEror()
                        }
                    },
                    columns: [{
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
                            data: 'kecamatan_lama'
                        },
                        {
                            data: 'kelurahan_lama'
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
            default:
                break;
        }
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
                <form action="{{ route('SinkronisasiLokasiIMB.import') }}" method="POST" enctype="multipart/form-data">
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
