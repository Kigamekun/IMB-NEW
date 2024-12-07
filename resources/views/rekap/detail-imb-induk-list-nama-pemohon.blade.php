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
                        <div style="display: flex;justify-content:space-between;align-items:center">
                        <h2>Data</h2>
                           <div>
                            <a href="{{ route('rekap.DetailIMBIndukNamaPemohon', ['nama_pemohon'=>$nama_pemohon]) }}" class="btn btn-info">
                                Daftar IMB
                            </a>
                           </div>
                        </div>

                        <table id="table2" class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" class="display">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TAHUN</th>
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
                            <tbody>
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
    <script>
            $('#table2').DataTable({
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
                    data: 'tahun',
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
            pageLength: 20
        });
    </script>
@endsection
