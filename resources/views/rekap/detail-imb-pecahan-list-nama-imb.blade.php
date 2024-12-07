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
                        </div>

                        <table id="table2" class="table table-bordered" style="width: 100% !important;border-bottom:none !important;" class="display">
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
@endsection



@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
    <script>
        var table = $('#table2').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
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

        $('#filter-lokasi').on('keyup', function() {
            table.column(9).search(this.value).draw(); // 8 is the index for 'lokasi_perumahan'
        });

        // Custom filtering for 'Kecamatan'
        $('#filter-kecamatan').on('keyup', function() {
            table.column(10).search(this.value).draw(); // 9 is the index for 'kecamatan'
        });

        // Custom filtering for 'Kelurahan'
        $('#filter-kelurahan').on('keyup', function() {
            table.column(11).search(this.value).draw(); // 10 is the index for 'kelurahan'
        });

        // Custom filtering for 'Atas Nama'
        $('#filter-atas_nama').on('keyup', function() {
            table.column(7).search(this.value).draw(); // 7 is the index for 'atas_nama'
        });

        // Custom filtering for 'Blok'
        $('#filter-blok').on('keyup', function() {
            table.column(13).search(this.value).draw(); // 13 is the index for 'blok'
        });

        // Custom filtering for 'No Blok'
        $('#filter-no_blok').on('keyup', function() {
            table.column(14).search(this.value).draw(); // 14 is the index for 'no_blok'
        });

        // Custom filtering for 'IMB Induk'
        $('#filter-imb_induk_id').on('keyup', function() {
            table.column(0).search(this.value).draw(); // 0 is the index for 'imb_induk_id'
        });
    </script>
@endsection
