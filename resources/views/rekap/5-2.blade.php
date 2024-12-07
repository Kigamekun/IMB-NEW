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
                    <h3 class="text-3xl font-bold">Data Rekap Detail</h3>
                    <br />
                    <div class="table-responsive py-3">
                        <table class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" id="IMBTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>JENIS IMB</th>
                                    <th>NO. IMB</th>
                                    <th>TGL. IMB</th>
                                    <th>LOKASI</th>
                                    <th>KECAMATAN</th>
                                    <th>DESA/KEL</th>
                                    <th>JENIS KEGIATAN</th>
                                    <th>FUNGSI BANGUNAN</th>
                                    <th>JUMLAH UNIT</th>
                                </tr>
                            </thead>

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
    <script>
        $(document).ready(function() {
            $('#IMBTable').DataTable(
                {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'JENIS_IMB',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'NO_IMB',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'TGL_IMB',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'LOKASI',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'KECAMATAN',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'DESA_KEL',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'JENIS_KEGIATAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'FUNGSI_BANGUNAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'JUMLAH_UNIT',
                    orderable: false,
                    searchable: false
                },

            ],
            pageLength: 20
        }
            );
        });
    </script>
@endsection
