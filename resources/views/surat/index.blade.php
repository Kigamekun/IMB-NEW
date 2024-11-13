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
                    <h3 class="text-3xl font-bold">Data Surat</h3>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('surat.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
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

                        <table class="table table-bordered" id="IMBTable">
                            <thead>


                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>No SK</th>
                                    <th>Tanggal</th>
                                    <th>Pemohon</th>
                                    <th>Atas Nama</th>
                                    <th>Alamat Pemohon</th>
                                    <th>No Register</th>
                                    <th>No IMBG</th>
                                    <th>Lokasi Bangunan</th>
                                    <th>Jenis</th>
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
                url: "{{ route('surat.index') }}",
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

            columns: [
                // {
                //     data: 'jenisSurat',
                //     title: 'IMB Induk'
                // },
                // {
                //     data: 'nomorSurat',
                //     title: 'Nomor Surat'
                // },
                // {
                //     data: 'tanggalSurat',
                //     title: 'Tanggal Surat'
                // },
                // {
                //     data: 'perihal',
                //     title: 'Perihal'
                // },
                // {
                //     data: 'sudah_upload',
                //     title: 'Sudah Upload'
                // },
                // {
                //     data: 'action',
                //     title: 'Action'
                // }

                {
                    data: 'DT_RowIndex',
                    title: 'No',

                },
                {
                    data: 'tahun',
                    title: 'Tahun'
                },
                {
                    data: 'nomorSurat',
                    title: 'No SK'
                },
                {
                    data: 'tanggalSurat',
                    title: 'Tanggal'
                },
                {
                    data: 'nama',
                    title: 'Pemohon'
                },
                {
                    data: 'bertindak_atas_nama',
                    title: 'Atas Nama'
                },
                {
                    data: 'alamat',
                    title: 'Alamat Pemohon'
                },
                {
                    data: 'registerNomor',
                    title: 'No Register'
                },
                {
                    data: 'imbgNomor',
                    title: 'No IMBG'
                },
                {
                    data: 'lokasi',
                    title : 'Lokasi Bangunan'
                },
                {
                    data: 'jenisSurat',
                    title: 'Jenis'
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
    <div class="modal fade" id="uploadSuratModal" tabindex="-1" aria-labelledby="uploadSuratModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="uploadSuratModalLabel">Upload Surat </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="upload-data" action="" method="POST" enctype="multipart/form-data">
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

    <div class="modal fade" id="updateNomorModal" tabindex="-1" aria-labelledby="updateNomorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="updateNomorModalLabel">Update Nomor Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update-nomor-form" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nomor_surat" class="form-label">Nomor Surat</label>
                            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" placeholder="Masukkan nomor surat">
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for modal show event
            const uploadModal = document.getElementById('uploadSuratModal');
            uploadModal.addEventListener('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                const button = event.relatedTarget;
                // Extract the data-url attribute
                const url = button.getAttribute('data-url');
                // Set the form action to the data-url
                const form = document.getElementById('upload-data');
                form.setAttribute('action', url);
            });
        });
    </script>
    <script>
         document.addEventListener('DOMContentLoaded', function() {
        // Listen for show event for upload and update modals
        const uploadModal = document.getElementById('uploadSuratModal');
        const updateModal = document.getElementById('updateNomorModal');

        uploadModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const form = document.getElementById('upload-data');
            form.setAttribute('action', url);
        });

        updateModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-url');
            const form = document.getElementById('update-nomor-form');
            form.setAttribute('action', url);
        });
    });
    </script>
@endsection
