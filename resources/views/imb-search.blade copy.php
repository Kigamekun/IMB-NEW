<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Surat & IMB - DPKPP Kabupaten Bogor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            border: none;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #3c8dbc;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .tab-content {
            padding-top: 20px;
        }

        .logo {
            max-width: 100px;
        }

        .header-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-logo img {
            margin-right: 10px;
        }

        .btn-custom {
            background-color: #3c8dbc;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        #IMBTable th {
            white-space: nowrap;
            /* Mencegah teks menjadi beberapa baris */
            text-overflow: ellipsis;
            /* Memberikan tanda "..." jika teks terlalu panjang */
            overflow: hidden;
            /* Menyembunyikan teks yang melampaui lebar */
            max-width: 150px;
            /* Tentukan lebar maksimum kolom (opsional) */
        }

        /* Opsional: tambahkan untuk memastikan tabel tetap rapi */
        #IMBTable {
            table-layout: auto;
            width: 100%;
            /* Pastikan tabel menggunakan seluruh lebar */
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header with Logo -->
        <div class="header-logo">
            <img style="width: 300px" src="{{ asset('assets/img/logo-dpkpp.png') }}" alt="">

            {{-- <h3>DPKPP Kabupaten Bogor</h3> --}}
        </div>

        <!-- Tabs Card -->
        <div class="card">
            <div class="card-header text-center">
                <h5>Pencarian Surat & IMB</h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="searchTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-surat" data-toggle="tab"
                            data-target="#content-surat" type="button" role="tab" aria-controls="content-surat"
                            aria-selected="true"  aria-controls="content-surat" >Surat</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-imb" data-toggle="tab" data-target="#content-imb"
                            type="button" role="tab" aria-controls="content-imb" aria-selected="false">IMB</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Surat Search Form -->
                    <div class="tab-pane fade show active" id="content-surat" role="tabpanel"
                        aria-labelledby="tab-surat">
                        <form class="mt-4">
                            <div class="mb-3">
                                <label for="nomorSurat" class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" id="nomorSurat"
                                    placeholder="Masukkan Nomor Surat">
                            </div>
                            <div class="mb-3">
                                <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label>
                                <input type="text" class="form-control" id="namaPemohonSurat"
                                    placeholder="Masukkan Nama Pemohon">
                            </div>
                            <button type="submit" class="btn btn-custom">Cari Surat</button>
                        </form>
                    </div>
                    <!-- IMB Search Form -->
                    <div class="tab-pane fade" id="content-imb" role="tabpanel" aria-labelledby="tab-imb">
                        <form class="mt-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="noImb" class="form-label">Nomor IMB</label>
                                    <input type="text" class="form-control" id="noImb"
                                        placeholder="Masukkan Nomor IMB">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tanggalImb" class="form-label">Tanggal IMB</label>
                                    <input type="date" class="form-control" id="tanggalImb">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama"
                                        placeholder="Masukkan Nama">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="atasNama" class="form-label">Bertindak Atas Nama</label>
                                    <input type="text" class="form-control" id="atasNama"
                                        placeholder="Masukkan Nama">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi"
                                        placeholder="Masukkan Lokasi">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="blok" class="form-label">Blok</label>
                                    <input type="text" class="form-control" id="blok"
                                        placeholder="Masukkan Blok">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="noBlok" class="form-label">No. Blok</label>
                                    <input type="text" class="form-control" id="noBlok"
                                        placeholder="Masukkan No. Blok">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control" id="kecamatan"
                                        placeholder="Masukkan Kecamatan">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="desaKelurahan" class="form-label">Desa/Kelurahan</label>
                                    <input type="text" class="form-control" id="desaKelurahan"
                                        placeholder="Masukkan Desa/Kelurahan">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom">Cari IMB</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="card">

            <div class="card-body table-responsive">
                <table class="table table-bordered" id="IMBTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Atas Nama</th>
                            <th>No. IMB</th>
                            <th>Tgl IMB</th>
                            <th>Lokasi</th>
                            <th>Kecamatan</th>
                            <th>Desa/Kelurahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>


                <!-- Modal Detail -->
                <div class="modal fade" id="IMBDetailModal" tabindex="-1" aria-labelledby="modalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Detail IMB</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Nomor IMB</th>
                                            <td id="nomorImb"></td>
                                        </tr>
                                        <tr>
                                            <th>No. Register</th>
                                            <td id="noRegister"></td>
                                        </tr>
                                        <tr>
                                            <th>Tgl. Register</th>
                                            <td id="tglRegister"></td>
                                        </tr>
                                        <tr>
                                            <th>Blok</th>
                                            <td id="blok"></td>
                                        </tr>
                                        <tr>
                                            <th>No. Blok</th>
                                            <td id="noBlok"></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kegiatan</th>
                                            <td id="jenisKegiatan"></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis IMB</th>
                                            <td id="jenisImb"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <div id="items" class="table-responsive">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('#IMBTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('IMBIndukPerum.cari-imb') }}",
                    data: function(d) {
                        d.no_imb = $('#noImb').val();
                        d.tgl_imb = $('#tanggalImb').val();
                        d.nama = $('#nama').val();
                        d.atas_nama = $('#atasNama').val();
                        d.lokasi = $('#lokasi').val();
                        d.blok = $('#blok').val();
                        d.no_blok = $('#noBlok').val();
                        d.kecamatan = $('#kecamatan').val();
                        d.desa_kelurahan = $('#desaKelurahan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'atas_nama'
                    },
                    {
                        data: 'no_imb'
                    },
                    {
                        data: 'tgl_imb'
                    },
                    {
                        data: 'lokasi'
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
                    }
                ]
            });

            // Refresh table on filter change
            $('#content-imb input').on('change keyup', function() {
                table.ajax.reload();
            });




            $(document).ready(function() {
                $('#IMBTable').on('click', '.view-details', function() {
                    var id = $(this).data('id');
                    var type = $(this).data('type');

                    $.ajax({
                        url: `/imb/IMBIndukPerum/getIMBDetail/${id}/${type}`,
                        method: 'GET',
                        success: function(data) {
                            // Isi data ke dalam tabel
                            $('#nomorImb').text(data.imb.nomor_imb || '-');
                            $('#noRegister').text(data.imb.no_register || '-');
                            $('#tglRegister').text(data.imb.tgl_register || '-');
                            $('#blok').text(data.imb.blok || '-');
                            $('#noBlok').text(data.imb.no_blok || '-');
                            $('#jenisKegiatan').text(data.imb.jenis_kegiatan || '-');
                            $('#jenisImb').text(data.imb.jenis_imb || '-');

                            var items = data.items;

                            detailContent = '';
                            if (items.length > 0) {
                                detailContent += `
                                    <h5>Item IMB:</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Jenis Kegiatan</th>
                                                <th>Fungsi Bangunan</th>
                                                <th>Luas Bangunan</th>
                                                <th>Jumlah Unit</th>
                                                <th>Keterangan</th>
                                                <th>Scan IMB</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                `;

                                items.forEach(function(item) {
                                    detailContent += `
                                        <tr>
                                            <td>${item.jenis_kegiatan || 'N/A'}</td>
                                            <td>${item.fungsi_bangunan || 'N/A'}</td>
                                            <td>${item.luas_bangunan || 'N/A'} m²</td>
                                            <td>${item.jumlah_unit || 'N/A'}</td>
                                            <td>${item.keterangan || 'N/A'}</td>
                                            <td>${item.scan_imb ? `<a href="/path-to-scans/${item.scan_imb}" target="_blank">Lihat</a>` : 'N/A'}</td>
                                        </tr>
                                    `;
                                });

                                detailContent += `</tbody></table>`;
                            }

                            $('#items').html(detailContent);
                            // Tampilkan modal
                            $('#IMBDetailModal').modal('show');
                        },
                        error: function() {
                            alert('Gagal mengambil detail data.');
                        }
                    });
                });
            });


        });

        $(document).ready(function() {
            var table = $('#SuratTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('IMBIndukPerum.cari-imb') }}",
                    data: function(d) {
                        d.no_imb = $('#noImb').val();
                        d.tgl_imb = $('#tanggalImb').val();
                        d.nama = $('#nama').val();
                        d.atas_nama = $('#atasNama').val();
                        d.lokasi = $('#lokasi').val();
                        d.blok = $('#blok').val();
                        d.no_blok = $('#noBlok').val();
                        d.kecamatan = $('#kecamatan').val();
                        d.desa_kelurahan = $('#desaKelurahan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'atas_nama'
                    },
                    {
                        data: 'no_imb'
                    },
                    {
                        data: 'tgl_imb'
                    },
                    {
                        data: 'lokasi'
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
                    }
                ]
            });

            // Refresh table on filter change
            $('#content-imb input').on('change keyup', function() {
                table.ajax.reload();
            });




            $(document).ready(function() {
                $('#IMBTable').on('click', '.view-details', function() {
                    var id = $(this).data('id');
                    var type = $(this).data('type');

                    $.ajax({
                        url: `/imb/IMBIndukPerum/getIMBDetail/${id}/${type}`,
                        method: 'GET',
                        success: function(data) {
                            // Isi data ke dalam tabel
                            $('#nomorImb').text(data.imb.nomor_imb || '-');
                            $('#noRegister').text(data.imb.no_register || '-');
                            $('#tglRegister').text(data.imb.tgl_register || '-');
                            $('#blok').text(data.imb.blok || '-');
                            $('#noBlok').text(data.imb.no_blok || '-');
                            $('#jenisKegiatan').text(data.imb.jenis_kegiatan || '-');
                            $('#jenisImb').text(data.imb.jenis_imb || '-');

                            var items = data.items;

                            detailContent = '';
                            if (items.length > 0) {
                                detailContent += `
                                    <h5>Item IMB:</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Jenis Kegiatan</th>
                                                <th>Fungsi Bangunan</th>
                                                <th>Luas Bangunan</th>
                                                <th>Jumlah Unit</th>
                                                <th>Keterangan</th>
                                                <th>Scan IMB</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                `;

                                items.forEach(function(item) {
                                    detailContent += `
                                        <tr>
                                            <td>${item.jenis_kegiatan || 'N/A'}</td>
                                            <td>${item.fungsi_bangunan || 'N/A'}</td>
                                            <td>${item.luas_bangunan || 'N/A'} m²</td>
                                            <td>${item.jumlah_unit || 'N/A'}</td>
                                            <td>${item.keterangan || 'N/A'}</td>
                                            <td>${item.scan_imb ? `<a href="/path-to-scans/${item.scan_imb}" target="_blank">Lihat</a>` : 'N/A'}</td>
                                        </tr>
                                    `;
                                });

                                detailContent += `</tbody></table>`;
                            }

                            $('#items').html(detailContent);
                            // Tampilkan modal
                            $('#IMBDetailModal').modal('show');
                        },
                        error: function() {
                            alert('Gagal mengambil detail data.');
                        }
                    });
                });
            });


        });
    </script>

</body>

</html>
