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
                    <div style="margin-top:20px;">
                        <a href="{{ route('surat.create') }}" type="button" class="btn btn-primary">
                            Tambah Data
                        </a>
                        {{-- <a type="button" href="{{ route('surat.cetakHalaman', ['page' => request()->get('page', 1)]) }}" onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;" class="btn btn-primary">
                            Cetak Halaman
                        </a> --}}
                        <a type="button" id="cetakHalamanBtn" class="btn btn-primary">
                            Cetak Halaman
                        </a>
                        <a type="button" href="{{ route('surat.previewIndex') }}" onclick="window.open(this.href, '_blank', 'width=800,height=600'); return false;" class="btn btn-primary">
                            Cetak Data
                        </a>
                        {{-- <a href="{{ route('surat.create') }}" type="button" class="btn btn-primary">
                            Copy Data
                        </a> --}}
                        <a type="button" data-url="' . route('surat.copyData', $row->id) . '" class="btn btn-primary text-white copyDataModal"
                            data-toggle="modal" data-target="#copyDataModal">
                            {{-- <i class="fas fa-upload"></i> --}}
                            Copy Data
                        </a>
                    </div>

                    <div class="mt-6" style="display: grid; gap:10px; margin-top:20px; grid-template-columns: repeat(4, minmax(0, 1fr));" id="form-search">
                        {{-- <button type="submit" class="btn btn-primary" style="height: 35px">Cari Surat</button> --}}
                        <div class="mb-3">
                            {{-- <label for="nomorSurat" class="form-label">Nomor Surat</label> --}}
                            <input type="text" class="form-control" id="nomorSurat" placeholder="Masukkan Nomor Surat">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="nomorSurat" class="form-label">Nomor Surat</label> --}}
                            <input type="text" class="form-control" id="nomorIMBG" placeholder="Masukkan Nomor IMB">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label> --}}
                            <input type="text" class="form-control" id="namaPemohonSurat"
                                placeholder="Masukkan Nama Pemohon">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label> --}}
                            <input type="text" class="form-control" id="lokasiPemohonSurat"
                                placeholder="Masukkan Lokasi Pembangunan ">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label> --}}
                            <input type="text" class="form-control" id="kabupatenPemohonSurat"
                                placeholder="Masukkan Kabupaten Pemohon">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label> --}}
                            <input type="text" class="form-control" id="kecamatanPemohonSurat"
                                placeholder="Masukkan Kecamatan Pemohon">
                        </div>
                        <div class="mb-3">
                            {{-- <label for="namaPemohonSurat" class="form-label">Nama Pemohon</label> --}}
                            <input type="text" class="form-control" id="kelurahanPemohonSurat"
                                placeholder="Masukkan Kelurahan Pemohon">
                        </div>
                    </div>

                    <br />

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
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>Jenis</th>
                                    {{-- <th>Status</th> --}}
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
        var table = $('#IMBTable').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: window.location.href,
                // url: window.location.href + '?_=' + new Date().getTime(), // Hindari cache
                data: function(d) {
                    console.log('Data Sent:', d); // Debug data yang dikirim
                    // d.perPage = $('#IMBTable').DataTable().page.len(); // Ambil jumlah entri per halaman
                    d.nomor_surat = $('#nomorSurat').val();
                    d.nama_pemohon = $('#namaPemohonSurat').val();
                    d.nomor_imbg = $('#nomorIMBG').val();
                    d.lokasi_bangunan = $('#lokasiPemohonSurat').val();
                    d.kabupaten_pemohon = $('#kabupatenPemohonSurat').val();
                    d.kecamatan_pemohon = $('#kecamatanPemohonSurat').val();
                    d.kelurahan_pemohon = $('#kelurahanPemohonSurat').val();
                },
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
                {
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
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
                    title: 'Lokasi Bangunan'
                },
                {
                    data: 'nama_kabupaten',
                    title: 'Kabupaten / Kota'
                },
                {
                    data: 'nama_kecamatan',
                    title: 'Kecamatan'
                },
                {
                    data: 'nama_kelurahan',
                    title: 'Kelurahan'
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
        });

        $('#form-search input').on('keyup change', function() {
            console.log('Filter Changed:', $(this).val()); // Debug nilai input
            table.ajax.reload(null, false); // Reload dengan data baru
        });

        $('#cetakHalamanBtn').on('click', function() {
            var page = table.page.info().page + 1; // Halaman saat ini
            var perPage = table.page.len(); // Jumlah data per halaman

            // Ambil nilai filter dari input
            var nomorSurat = $('#nomorSurat').val();
            var namaPemohon = $('#namaPemohonSurat').val();
            var nomorIMBG = $('#nomorIMBG').val();
            var lokasiPemohon = $('#lokasiPemohonSurat').val();
            var kabupatenPemohon = $('#kabupatenPemohonSurat').val();
            var kecamatanPemohon = $('#kecamatanPemohonSurat').val();
            var kelurahanPemohon = $('#kelurahanPemohonSurat').val();

            // Bangun URL dengan parameter filter
            var url = "{{ route('surat.cetakHalaman') }}";
            url += "?page=" + page + "&perPage=" + perPage;
            url += "&nomor_surat=" + encodeURIComponent(nomorSurat || '');
            url += "&nama_pemohon=" + encodeURIComponent(namaPemohon || '');
            url += "&nomor_imbg=" + encodeURIComponent(nomorIMBG || '');
            url += "&lokasi_bangunan=" + encodeURIComponent(lokasiPemohon || '');
            url += "&kabupaten_pemohon=" + encodeURIComponent(kabupatenPemohon || '');
            url += "&kecamatan_pemohon=" + encodeURIComponent(kecamatanPemohon || '');
            url += "&kelurahan_pemohon=" + encodeURIComponent(kelurahanPemohon || '');

            // Buka URL cetak di tab baru
            window.open(url, '_blank', 'width=800,height=600');
        });

    </script>
@endsection



@section('modal')
    <div class="modal fade" id="uploadSuratModal" tabindex="-1" aria-labelledby="uploadSuratModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="uploadSuratModalLabel">Upload Surat </h5>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="copyDataModal" tabindex="-1" aria-labelledby="copyDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="copyDataModalLabel">Copy Data</h4>
                </div>


                <form id="copy-data-form" action="{{ route('surat.copyData') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select id="tahun" name="tahun" class="form-control">
                                <option value="">Pilih Tahun...</option>
                                <?php
                                $currentYear = date('Y'); // Tahun sekarang
                                $tahun = $currentYear - 50; // 50 tahun ke belakang
                                for ($year = $currentYear; $year >= $tahun; $year--) {
                                    echo "<option value='$year'>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3" style="margin-top: 10px">
                            <label for="nomor_surat" class="form-label">Nomor SK/Pemohon</label>
                            <select id="nomorSK-Pemohon" name="nomorSK-Pemohon" class="form-control">
                                <option value="">-- PILIH --</option>
                            </select>
                        </div>
                        <input type="hidden" id="namaPemohonId" name="namaPemohonId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                </div>
                <form id="update-nomor-form" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nomor_surat" class="form-label">Nomor Surat</label>
                            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                                placeholder="Masukkan nomor surat">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>



    <script>
        $(document).ready(function() {

            $('#tahun').change(function() {
                var selectedYear = $(this).val();
                var surat = @json($surat);

                // Kosongkan opsi sebelumnya
                $('#nomorSK-Pemohon').empty().append('<option value="">-- PILIH --</option>');

                var filteredSurat = surat.filter(function(item) {
                    return item.tahun == selectedYear;
                });

                // Urutkan secara descending berdasarkan id
                filteredSurat.sort(function(a, b) {
                    return b.id - a.id;
                });

                if (filteredSurat.length > 0) {
                    filteredSurat.forEach(function(item) {
                    $('#nomorSK-Pemohon').append('<option value="' + item.id + '">' + item.nomorSurat + ' an. ' + item.nama + '</option>');
                    });
                } else {
                    $('#nomorSK-Pemohon').append('<option value="">Tidak ada data</option>');
                }
                });

                $('#copyDataModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);

                // Set the hidden input value to the ID of the selected item
                modal.find('#namaPemohonId').val(id);
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
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
            updateModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const url = button.getAttribute('data-url');
                const form = document.getElementById('copy-data-form');
                form.setAttribute('action', url);
            });
        });
    </script>

    <script>
        $('#IMBTable').on('click', '.uploadSuratModal', function() {
            const url = $(this).attr('data-url');

            const form = document.getElementById('upload-data');
            form.setAttribute('action', url);
        });

        $('#IMBTable').on('click', '.updateNomorModal', function(event) {
            // Ambil data-id dari tombol yang diklik
            const dataId = $(this).attr('data-id');

            // Ambil data-url dari tombol yang diklik
            const url = $(this).attr('data-url');

            // Set action pada form
            const form = document.getElementById('update-nomor-form');
            form.setAttribute('action', url);

        });
    </script>
@endsection
