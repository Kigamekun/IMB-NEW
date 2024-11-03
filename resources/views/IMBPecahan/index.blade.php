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
                    <h3 class="text-3xl font-bold">Data IMB Pecahan</h3>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('IMBPecahan.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                        <button type="button" class="ml-2 btn btn-info text-white" data-bs-toggle="modal"
                            data-bs-target="#importDataModal">
                            Import Data
                        </button>
                        {{-- <a class="btn btn-warning ml-2" href="{{ route('IMBPecahan.export') }}">
                            Export Data
                        </a>
                        <a class="btn btn-success ml-2" href="{{ route('IMBPecahan.download-template') }}">
                            Download Template
                        </a> --}}
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
        $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('IMBPecahan.index') }}",
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
                <form action="{{ route('IMBPecahan.import') }}" method="POST" enctype="multipart/form-data">
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
