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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

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

        #cardSurat, #cardImb {
            display: none;
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

        /* #suratTable, #IMBTable {
            display: none;
        } */

        #SuratTable th {
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
    </style>
</head>

<body>
    <style>
        th {
            word-wrap: wrap;
        }
    </style>
    <div class="container">
        <div class="header-logo">
            <img style="width: 300px" src="{{ asset('assets/img/logo-dpkpp.png') }}" alt="">
        </div>
        <div class="card">
            <div class="card-header text-center">
                <h5>Pencarian Surat & IMB</h5>
            </div>
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-surat-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-surat" type="button" role="tab" aria-controls="nav-surat"
                            aria-selected="true">Surat</button>
                        <button class="nav-link" id="nav-imb-tab" data-bs-toggle="tab" data-bs-target="#nav-imb"
                            type="button" role="tab" aria-controls="nav-imb" aria-selected="false">IMB</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-surat" role="tabpanel"
                        aria-labelledby="nav-surat-tab">

                        <form id="submitSearchSurat" class="mt-4">
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
                            <div class="mb-3">
                                <label for="lokasiPemohonSurat" class="form-label">Lokasi Bangunan</label>
                                <input type="text" class="form-control" id="lokasiPemohonSurat"
                                    placeholder="Masukkan Lokasi Pemohon">
                            </div>
                             <div class="mb-3">
                                <label for="kecamatanPemohonSurat" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatanPemohonSurat"
                                    placeholder="Masukkan Kecamatan Pemohon">
                            </div>
                            <div class="mb-3">
                                <label for="kelurahanPemohonSurat" class="form-label">Kelurahan</label>
                                <input type="text" class="form-control" id="kelurahanPemohonSurat"
                                    placeholder="Masukkan Kelurahan Pemohon">
                            </div>
                            <button type="submit" class="btn btn-custom">Cari Surat</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="nav-imb" role="tabpanel" aria-labelledby="nav-imb-tab">
                        <form id="submitSearchIMB" class="mt-4">
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
        <div class="card" id="cardSurat">
            <div class="card-body table-responsive">

                <table class="table table-bordered" id="suratTable">
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
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card" id="cardImb">
            <div class="card-body table-responsive">

                <table class="table table-bordered" style="width: 100%" id="IMBTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Atas Nama</th>
                            <th>No. IMB</th>
                            <th>Tgl IMB</th>
                            {{-- <th>Blok</th>
                            <th>No. Blok</th> --}}
                            <th>Lokasi</th>
                            <th>Kecamatan</th>
                            <th>Desa/Kelurahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="modal fade" id="IMBDetailModal" tabindex="-1" aria-labelledby="modalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Detail IMB</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                <div id="items" class="table-responsive"></div>
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
            // Tampilkan card surat secara default
            // $('#cardSurat').show();
            // $('#cardImb').hide();

            // Event listener untuk tombol "Surat"
            $('#nav-surat-tab').click(function() {
                $('#cardSurat').hide();
                $('#cardImb').hide();
            });

            // Event listener untuk tombol "IMB"
            $('#nav-imb-tab').click(function() {
                $('#cardSurat').hide();
                $('#cardImb').hide();
            });



            // Inisialisasi DataTable untuk tabel IMB
            // var imbTable = $('#IMBTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: "{{ route('IMBIndukPerum.cari-imb') }}",
            //         data: function(d) {
            //             d.no_imb = $('#noImb').val();
            //             d.tgl_imb = $('#tanggalImb').val();
            //             d.nama = $('#nama').val();
            //             d.atas_nama = $('#atasNama').val();
            //             d.lokasi = $('#lokasi').val();
            //             d.blok = $('#blok').val();
            //             d.no_blok = $('#noBlok').val();
            //             d.kecamatan = $('#kecamatan').val();
            //             d.desa_kelurahan = $('#desaKelurahan').val();
            //         }
            //     },
            //     columns: [{
            //             data: 'DT_RowIndex',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'nama'
            //         },
            //         {
            //             data: 'atas_nama'
            //         },
            //         {
            //             data: 'no_imb'
            //         },
            //         {
            //             data: 'tgl_imb'
            //         },
            //         // { data: 'blok' },
            //         // { data: 'no_blok' },
            //         {
            //             data: 'lokasi'
            //         },
            //         {
            //             data: 'kecamatan'
            //         },
            //         {
            //             data: 'kelurahan'
            //         },
            //         {
            //             data: 'action',
            //             orderable: false,
            //             searchable: false
            //         }
            //     ]
            // });

            // Initialize DataTables for IMB
            var imbTable = $('#IMBTable').DataTable({
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
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama' },
                    { data: 'atas_nama' },
                    { data: 'no_imb' },
                    { data: 'tgl_imb' },
                    { data: 'lokasi' },
                    
                    // { data: 'pecahan_blok', name: 'imb_pecahan.blok' }, // Updated to match the new field
                    // { data: 'pecahan_no_blok', name: 'imb_pecahan.no_blok' }, // Updated to match the new field
                    // { data: 'perluasan_blok', name: 'imb_perluasan.blok' }, // Updated to match the new field
                    // { data: 'perluasan_no_blok', name: 'imb_perluasan.no_blok' }, // Updated to match the new field
                    { data: 'kecamatan' },
                    { data: 'kelurahan' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            });

            // Refresh table on filter change
            $('#content-imb input').on('change keyup', function() {
                imbTable.ajax.reload(null,
                    false); // Reload DataTable dengan data baru tanpa mengubah halaman
            });

            $('#content-surat input').on('change keyup', function() {
                suratTable.ajax.reload(null,
                    false); // Reload DataTable dengan data baru tanpa mengubah halaman
            });

            // Event listener untuk form submit pada IMB
            $('#content-imb form').submit(function(e) {
                e.preventDefault(); // Cegah perilaku default form
                imbTable.ajax.reload(null,
                    false); // Reload DataTable dengan data baru tanpa mengubah halaman
            });

            // Event listener untuk form submit pada Surat
            // Show the IMB table when the form is submitted
            // $('#submitSearchIMB').on('submit', function(e) {
            //     e.preventDefault();
            //     $('#IMBTable').fadeIn();
            //     imbTable.draw();
            // });

            $('#submitSearchIMB').on('submit', function(e) {
                e.preventDefault();
                $('#cardImb').fadeIn();
                imbTable.draw();
            });

            // Untuk mengambil detailnya
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

                        var detailContent = '';
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
                                        <td>${item.scan_imb ? `<a href="/imb/storage/${item.scan_imb}" target="_blank">Lihat</a>` : 'N/A'}</td>
                                    </tr>
                                `;
                            });

                            detailContent += `</tbody></table>`;
                        }

                        $('#items').html(detailContent);
                        // Tampilkan modal
                        $('#IMBDetailModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching detail data:', error);
                        alert('Gagal mengambil detail data.');
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            var typingTimer; // Timer untuk delay
            var typingInterval = 1000; // Waktu delay dalam milidetik (1 detik)

            // Inisialisasi DataTable
            // var suratTable = $('#suratTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: "{{ route('surat.cari-surat') }}",
            //         data: function(d) {
            //             d.nomor_surat = $('#nomorSurat').val();
            //             d.nama_pemohon = $('#namaPemohonSurat').val();
            //             d.lokasi_bangunan = $('#lokasiPemohonSurat').val();
            //             d.kecamatan_pemohon = $('#kecamatanPemohonSurat').val();
            //             d.kelurahan_pemohon = $('#kelurahanPemohonSurat').val();
            //         }
            //     },
            //     columns: [{
            //             data: 'DT_RowIndex',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'tahun'
            //         },
            //         {
            //             data: 'nomorSurat'
            //         },
            //         {
            //             data: 'tanggalSurat'
            //         },
            //         {
            //             data: 'nama'
            //         },
            //         {
            //             data: 'bertindak_atas_nama'
            //         },
            //         {
            //             data: 'alamat'
            //         },
            //         {
            //             data: 'registerNomor'
            //         },
            //         {
            //             data: 'imbgNomor'
            //         },
            //         {
            //             data: 'lokasi'
            //         },
            //         {
            //             data: 'nama_kecamatan'
            //         },
            //         {
            //             data: 'nama_kelurahan'
            //         },
            //         {
            //             data: 'jenisSurat'
            //         },
            //         {
            //             data: 'action',
            //             orderable: false,
            //             searchable: false
            //         }
            //     ],
            //     order: [
            //         [2, 'desc']
            //     ]
            // });
            var suratTable = $('#suratTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat.cari-surat') }}",
                    data: function(d) {
                        d.nomor_surat = $('#nomorSurat').val();
                        d.nama_pemohon = $('#namaPemohonSurat').val();
                        d.lokasi_bangunan = $('#lokasiPemohonSurat').val();
                        d.kecamatan_pemohon = $('#kecamatanPemohonSurat').val();
                        d.kelurahan_pemohon = $('#kelurahanPemohonSurat').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tahun' },
                    { data: 'nomorSurat' },
                    { data: 'tanggalSurat' },
                    { data: 'nama' },
                    { data: 'bertindak_atas_nama' },
                    { data: 'alamat' },
                    { data: 'registerNomor' },
                    { data: 'imbgNomor' },
                    { data: 'lokasi' },
                    { data: 'nama_kecamatan' },
                    { data: 'nama_kelurahan' },
                    { data: 'jenisSurat' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[2, 'desc']]
            });
            // Fungsi untuk memulai pencarian dengan delay
            function delayedSearch() {
                clearTimeout(typingTimer); // Hentikan timer sebelumnya
                typingTimer = setTimeout(function() {
                    suratTable.draw(); // Refresh DataTable setelah delay
                }, typingInterval);
            }

            // Show the Surat table when the form is submitted
            // $('#submitSearchSurat').on('submit', function(e) {
            //     e.preventDefault();
            //     $('#cardSurat').fadeIn();
            //     suratTable.draw();
            // });
            $('#submitSearchSurat').on('submit', function(e) {
                e.preventDefault();
                $('#cardSurat').fadeIn();
                suratTable.draw();
            });

        });
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');

            if (tab === 'imb') {
                var tabElement = new bootstrap.Tab(document.querySelector('#nav-imb-tab'));
                tabElement.show();
                document.querySelector('#nav-surat-tab').style.display = 'none';
            } else {
                document.querySelector('#nav-imb-tab').style.display = 'none';
            }
            // if (tab === 'surat') {
            //     var tabElement = new bootstrap.Tab(document.querySelector('#nav-imb-tab'));
            //     tabElement.show();
            //     document.querySelector('#nav-surat-tab').style.display = 'none';
            // } else {
            //     document.querySelector('#nav-imb-tab').style.display = 'none';
            // }
        });
    </script>

</body>

</html>
