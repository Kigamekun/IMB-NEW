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
                    data: 'JUMLAH_IMB',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_INDUK_PERUMAHAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_PECAHAN',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'IMB_PERLUASAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_NON_PERUMAHAN_PERUSAHAAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_NON_PERUMAHAN_PERORANGAN',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_NON_PERUMAHAN_SOSIAL_BUDAYA',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_BERSYARAT',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_BERSYARAT',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'IMB_LAINNYA',
                    orderable: false,
                    searchable: false
                },
            ],
            pageLength: 20
        });
    </script>
@endsection
