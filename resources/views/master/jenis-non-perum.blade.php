@extends('layouts.base')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.bootstrap5.css">
    <style>
        .table> :not(:first-child) {
            border-top: none;
        }

        .dt-paging-button.active .page-link {
            color: white;
        }

        table.dataTable td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <br>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Data Jenis Non Perum</h3>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                            Tambah Data
                        </button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="datatable-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
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



    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>


    <script>
        $('#datatable-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jenis-non-perum.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ]
        });
    </script>
@endsection


@section('scripts')
    <script>
        $('#updateData').on('shown.bs.modal', function(e) {
            var html = `
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Data Tujuan Surat</h5>
                    <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span class="text-danger">*</span> wajib diisi.</small>
                </div>
            </div>
            <form action="${$(e.relatedTarget).data('url')}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                            <label for="name" class="fw-semibold">Name <span class="ml-1 text-danger">*</span></label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="${$(e.relatedTarget).data('name')}"
                                id="name" name="name" placeholder="Masukan Name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        `;
            $('#modal-content').html(html);
        });
    </script>
@endsection

@section('modal')
    <div class="modal fade" id="tambahDataModal" tabindex="-1" role="dialog" aria-labelledby="tambahDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('jenis-non-perum.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="updateDataLabel" aria-hidden="true">
        <div class="modal-dialog" id="updateDialog">
            <div id="modal-content" class="modal-content">
                <div class="modal-body">
                    Loading..
                </div>
            </div>
        </div>
    </div>
@endsection
