@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="container">
                    <form action="{{ route('surat.store') }}" method="POST" class="form-container">
                        @csrf
                        <div class="section-title">JENIS SURAT</div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="jenisSurat" class="form-label">Jenis Surat:</label>
                                <select id="jenisSurat" name="jenisSurat" class="form-control form-select">
                                    <option value="format-1">FORMAT-1</option>
                                    <option value="format-3">FORMAT-3</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tahun" class="form-label">Tahun:</label>
                                <input type="text" id="tahun" name="tahun" class="form-control" value="2024"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="nomorSurat" class="form-label">Nomor Surat:</label>
                                <input type="text" id="nomorSurat" name="nomorSurat" class="form-control" value="-"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tanggalSurat" class="form-label">Tanggal Surat:</label>
                                <input type="date" id="tanggalSurat" name="tanggalSurat" class="form-control"
                                    value="2024-10-29">
                            </div>
                            <div class="col-md-4">
                                <label for="lampiran" class="form-label">Lampiran:</label>
                                <input type="text" id="lampiran" name="lampiran" class="form-control" value="-">
                            </div>
                            <div class="col-md-4">
                                <label for="sifat" class="form-label">Sifat:</label>
                                <input type="text" id="sifat" name="sifat" class="form-control" value="Biasa">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="perihal" class="form-label">Perihal:</label>
                            <textarea id="perihal" name="perihal" class="form-control" style="height: 100px" rows="2">Keterangan Izin Mendirikan Bangunan (IMBG)</textarea>
                        </div>
                        <div class="section-title">PEMOHON</div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="permohonanTanggal" class="form-label">Permohonan Tanggal:</label>
                                <input type="date" id="permohonanTanggal" name="permohonanTanggal" class="form-control"
                                    value="2024-10-29">
                            </div>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama:</label>
                                <input type="text" id="nama" name="nama" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="provinsi" class="form-label">Provinsi:</label>
                                <select id="provinsi" name="provinsi" class="form-select select2-provinsi">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kabupaten" class="form-label">Kabupaten/Kota:</label>
                                <select id="kabupaten" name="kabupaten" class="form-select select2 select2-kabupaten">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kecamatan" class="form-label">Kecamatan:</label>
                                <select id="kecamatan" name="kecamatan" class="form-select select2 select2-kecamatan">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat:</label>
                                <input type="text" id="alamat" name="alamat" class="form-control">
                            </div>
                        </div>
                        <div class="section-title">REFERENSI</div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tujuanSurat" class="form-label">Tujuan Surat:</label>
                                <select id="tujuanSurat" name="tujuanSurat" class="form-select form-control">
                                    <option value="pemutakhiran-data">Pemutakhiran Data</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="registerNomor" class="form-label">Register Nomor:</label>
                                <input type="text" id="registerNomor" name="registerNomor" class="form-control"
                                    value="-">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="registerTanggal" class="form-label">Register Tanggal:</label>
                                <input type="date" id="registerTanggal" name="registerTanggal" class="form-control"
                                    value="2024-10-29">
                            </div>
                            <div class="col-md-6">
                                <label for="imbgNomor" class="form-label">IMBG Nomor:</label>
                                <input type="text" id="imbgNomor" name="imbgNomor" class="form-control"
                                    value="-">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="imbgTanggal" class="form-label">IMBG Tanggal:</label>
                            <input type="date" id="imbgTanggal" name="imbgTanggal" class="form-control"
                                value="2024-10-29">
                        </div>
                        <div class="section-title">PENANDATANGAN</div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kepalaDinas" class="form-label">Kepala Dinas:</label>
                                <input type="text" id="kepalaDinas" name="kepalaDinas" class="form-control"
                                    value="JUANDA DIMANSYAH, SE, MM">
                            </div>
                            <div class="col-md-6">
                                <label for="nip" class="form-label">NIP:</label>
                                <input type="text" id="nip" name="nip" class="form-control"
                                    value="196503241986031011">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pangkat" class="form-label">Pangkat/Golongan:</label>
                            <input type="text" id="pangkat" name="pangkat" class="form-control"
                                value="Pembina Utama Muda">
                        </div>
                        <div class="section-title">KETERANGAN</div>
                        <div class="mb-3">
                            <label for="ket1" class="form-label">KET-1:</label>
                            <textarea id="ket1" name="ket1" class="form-control" style="height: 100px" rows="3">Nama dan Nomor Izin Mendirikan Bangunan Gedung tersebut di atas adalah benar tercatat dalam buku register Izin Mendirikan Bangunan pada Dinas Perumahan Kawasan Permukiman Dan Pertanahan Kabupaten Bogor.</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ket2" class="form-label">KET-2:</label>
                            <textarea id="ket2" name="ket2" class="form-control" style="height: 100px" rows="3">Surat Keterangan ini hanya sebagai bahan tindak lanjut persyaratan permohonan Izin Mendirikan Bangunan Gedung di Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kabupaten Bogor dan bukan merupakan Izin Mendirikan Bangunan Gedung.</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ket3" class="form-label">KET-3:</label>
                            <textarea id="ket3" name="ket3" class="form-control" style="height: 100px" rows="3">Apabila data yang Saudara berikan tidak benar, maka surat keterangan ini dianggap gugur atau tidak berlaku.</textarea>
                        </div>
                        <br>
                        <div class="container mt-4">
                            <h4 class="text-">Detail Data IMBG</h4>
                            <br>
                            {{-- <div class="form-group">
                                <label for="jumlah_item">Jumlah Item:</label>
                                <input type="number" class="form-control" id="jumlah_item" name="jumlah_item" value="3">
                            </div> --}}
                            <table class="table-bordered table ">
                                <tbody id="details">
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th colspan="2">Data IMBG</th>
                                        <th colspan="2">Pecah/Rincik </th>
                                        <th colspan="2">Belum Rincik/Pecah</th>
                                        <th colspan="3">Sudah Dimohon Surat Keterangan</th>
                                        <th rowspan="2">Hapus</th>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <th>Jumlah (Unit)</th>
                                        <th>Type</th>
                                        <th>Jumlah (Unit)</th>
                                        <th>Type</th>
                                        <th>Jumlah (Unit)</th>
                                        <th>Type</th>
                                        <th>Jumlah (Unit)</th>
                                        <th>Keterangan</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" name="details[0][type]" class="form-control" required>
                                        </td>
                                        <td><input type="text" name="details[0][jumlah]" class="form-control"
                                                required></td>
                                        <td><input type="text" name="details[0][pecah_type]" class="form-control">
                                        </td>
                                        <td><input type="text" name="details[0][pecah_jumlah]" class="form-control">
                                        </td>
                                        <td><input type="text" name="details[0][belum_rincik_type]"
                                                class="form-control"></td>
                                        <td><input type="text" name="details[0][belum_rincik_jumlah]"
                                                class="form-control"></td>
                                        <td><input type="text" name="details[0][sudah_dimohon_type]"
                                                class="form-control"></td>
                                        <td><input type="text" name="details[0][sudah_dimohon_jumlah]"
                                                class="form-control"></td>
                                        <td><input type="text" name="details[0][sudah_dimohon_keterangan]"
                                                class="form-control"></td>
                                        <td><button type="button" class="btn btn-danger remove-detail">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="add-detail" class="btn btn-success">Tambah Detail</button>
                            </div>
                            <br>
                            <div id='detail-2' style="display: none">
                                <h4 class="text-">Detail Data 2 IMBG</h4>
                                <br>
                                <table class="table-bordered table ">
                                    <tbody id="details2">
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 10%">Type</th>
                                            <th style="width: 10%">Blok</th>
                                            <th style="width: 50%">Nomor</th>
                                            <th>Jumlah</th>
                                            <th>Hapus</th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" name="details2[0][type]" class="form-control"
                                                    required>
                                            </td>
                                            <td><input type="text" name="details2[0][blok]" class="form-control"
                                                    required>
                                            </td>
                                            <td><input type="text" name="details2[0][nomor]" class="form-control">
                                            </td>
                                            <td><input type="text" name="details2[0][jumlah]" class="form-control">
                                            </td>
                                            <td><button type="button"
                                                    class="btn btn-danger remove-detail-2">Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="add-detail-2" class="btn btn-success">Tambah
                                        Detail</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan dan Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


    <script>
        $('.select2').select2();


        function initializeSelect2WithAjax() {
            // Provinsi Select2 with AJAX
            $('.select2-provinsi').select2({
                width: '100%',
                placeholder: 'Pilih Provinsi',
                minimumInputLength: 2,
                ajax: {
                    url: '/master/get-provinsi', // URL to fetch provinsi data
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        console.log("Fetched provinsi data:", data); // Check data structure here
                        return {
                            results: data.items.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                }
            }).on('select2:select', function(e) {
                console.log("Selected Provinsi:", e.params.data);
                loadKabupaten(e.params.data.id); // Load kabupaten based on selected provinsi
            });

            // Kabupaten Select2 with AJAX
            function loadKabupaten(provinsiId) {
                $('.select2-kabupaten').select2({
                    width: '100%',
                    placeholder: 'Pilih Kabupaten/Kota',
                    minimumInputLength: 2,
                    ajax: {
                        url: '/master/get-kabupaten', // URL to fetch kabupaten data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                provinsi_id: provinsiId, // Pass the selected provinsi ID
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched kabupaten data:", data); // Check data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kabupaten/Kota:", e.params.data);
                    loadKecamatan(e.params.data.id); // Load kecamatan based on selected kabupaten
                });
            }

            // Kecamatan Select2 with AJAX
            function loadKecamatan(kabupatenId) {
                $('.select2-kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    minimumInputLength: 2,
                    ajax: {
                        url: '/master/get-kecamatan', // URL to fetch kecamatan data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kabupaten_id: kabupatenId, // Pass the selected kabupaten ID
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched kecamatan data:", data); // Check kecamatan data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', function(e) {
                    console.log("Selected Kecamatan:", e.params.data);
                    loadKelurahan(e.params.data.id); // Load kelurahan based on selected kecamatan
                });
            }

            // Kelurahan Select2 with AJAX
            function loadKelurahan(kecamatanId) {
                $('.select2-kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    minimumInputLength: 2,
                    ajax: {
                        url: '/master/get-kelurahan', // URL to fetch kelurahan data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kecamatan_id: kecamatanId, // Pass the selected kecamatan ID
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            console.log("Fetched kelurahan data:", data); // Check kelurahan data structure here
                            return {
                                results: data.items.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });
            }
        }

        $(document).ready(function() {
            initializeSelect2WithAjax();
        });


        let detailCount = 1;
        let detail2Count = 1;

        $('#add-detail').on('click', function() {
            const detailHtml = `
        <tr>
            <td>${detailCount + 1}</td>
            <td><input type="text" name="details[${detailCount}][type]" class="form-control" required></td>
            <td><input type="text" name="details[${detailCount}][jumlah]" class="form-control" required></td>
            <td><input type="text" name="details[${detailCount}][pecah_type]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][pecah_jumlah]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][belum_rincik_type]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][belum_rincik_jumlah]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][sudah_dimohon_type]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][sudah_dimohon_jumlah]" class="form-control"></td>
            <td><input type="text" name="details[${detailCount}][sudah_dimohon_keterangan]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-detail">Hapus</button></td>
        </tr>
    `;
            $('#details').append(detailHtml);
            detailCount++;
        });


        $('#add-detail-2').on('click', function() {
            const detailHtml = `
        <tr>
            <td>${detailCount + 1}</td>
            <td><input type="text" name="details2[${detailCount}][type]" class="form-control" required></td>
            <td><input type="text" name="details2[${detailCount}][blok]" class="form-control" required></td>
            <td><input type="text" name="details2[${detailCount}][nomor]" class="form-control"></td>
            <td><input type="text" name="details2[${detailCount}][jumlah]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-detail-2">Hapus</button></td>
        </tr>
    `;
            $('#details2').append(detailHtml);
            detail2Count++;
        });

        $(document).on('click', '.remove-detail-2', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.remove-detail', function() {
            $(this).closest('tr').remove();
        });

        $('#jenisSurat').on('change', function() {
                if ($(this).val() === 'format-1') {
                    $('#detail-2').hide();

                } else {
                    $('#detail-2').show();
                }
        });
    </script>
@endsection
