@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="container">
                    <form id="suratForm" action="{{ route('surat.store') }}" method="POST" class="form-container">
                        @csrf
                        <div class="section-title">JENIS SURAT</div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="jenisSurat" class="form-label">Jenis Surat:</label>
                                <select id="jenisSurat" name="jenisSurat" class="form-control form-select">
                                    <option value="format-1">FORMAT-1</option>
                                    <option value="format-2">FORMAT-2</option>
                                    <option value="format-3">FORMAT-3</option>
                                    <option value="format-4">FORMAT-4</option>
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
                                <label for="bertindak_atas_nama" class="form-label">Bertindak Atas Nama:</label>
                                <input type="text" id="bertindak_atas_nama" name="bertindak_atas_nama"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat:</label>
                                <input type="text" id="alamat" name="alamat" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="provinsiPemohon" class="form-label">Provinsi:</label>
                                <select id="provinsiPemohon" name="provinsiPemohon"
                                    class="form-select select2-provinsi-pemohon">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kabupatenPemohon" class="form-label">Kabupaten/Kota:</label>
                                <select id="kabupatenPemohon" name="kabupatenPemohon"
                                    class="form-select select2 select2-kabupaten-pemohon">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kecamatanPemohon" class="form-label">Kecamatan:</label>
                                <select id="kecamatanPemohon" name="kecamatanPemohon"
                                    class="form-select select2 select2-kecamatan-pemohon">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kelurahanPemohon" class="form-label">Kelurahan:</label>
                                <select id="kelurahanPemohon" name="kelurahanPemohon"
                                    class="form-select select2 select2-kelurahan-pemohon">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                        </div>
                        <div class="section-title">REFERENSI</div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="izin_mendirikan_bangunan_atas_nama" class="form-label">Izin Mendirikan
                                    Bangunan atas nama :</label>
                                <input type="text" id="izin_mendirikan_bangunan_atas_nama"
                                    name="izin_mendirikan_bangunan_atas_nama" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="lokasi" class="form-label">Lokasi:</label>
                                <input type="text" id="lokasi" name="lokasi" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tujuanSurat" class="form-label">Tujuan Surat:</label>
                                <select id="tujuanSurat" name="tujuanSurat" class="form-select form-control select2">
                                    <option value="">--- PILIH ---</option>
                                    @foreach (DB::table('master_tujuan_surat')->get() as $tujuan)
                                        <option value="{{ $tujuan->nama }}">{{ $tujuan->nama }}</option>
                                    @endforeach
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
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="provinsi" class="form-label">Provinsi:</label>
                                <select id="provinsi" name="provinsi" class="form-select select2-provinsi">
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kabupaten" class="form-label">Kabupaten/Kota:</label>
                                <select id="kabupaten" name="kabupaten" class="form-select select2 select2-kabupaten">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="kabupaten-terdahulu" class="form-label">Kabupaten/Kota Terdahulu:</label>
                                <input type="text" id="kabupaten-terdahulu" name="kabupaten-terdahulu"
                                    class="form-control" value="">
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
                                <label for="kecamatan-terdahulu" class="form-label">Kecamatan Terdahulu:</label>
                                <input type="text" id="kecamatan-terdahulu" name="kecamatan-terdahulu"
                                    class="form-control" value="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kelurahan" class="form-label">Kelurahan:</label>
                                <select id="kelurahan" name="kelurahan" class="form-select select2 select2-kelurahan">
                                    <option value="">--- PILIH ---</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kelurahan-terdahulu" class="form-label">Kelurahan Terdahulu:</label>
                                <input type="text" id="kelurahan-terdahulu" name="kelurahan-terdahulu"
                                    class="form-control" value="">
                            </div>
                        </div>
                        <div class="section-title">PENANDATANGAN</div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kepalaDinas" class="form-label">Penandatangan:</label>
                                <select id="kepalaDinas" name="kepalaDinas" class="form-select form-control select2">
                                    <option value="">--- PILIH ---</option>
                                    @foreach (DB::table('app_md_user')->whereNotIn('nip_user', ['-', '', '.'])->get() as $user)
                                        <option
                                            value="{{ $user->nip_user . ' | ' . strtoupper($user->name_user) . $user->gelar_blk }}">
                                            {{ $user->nip_user . ' | ' . strtoupper($user->name_user) . $user->gelar_blk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatan" class="form-label">Jabatan:</label>
                                <select id="jabatan" name="jabatan" class="form-select form-control select2">
                                    <option value="">--- PILIH ---</option>
                                    @foreach (DB::table('app_md_jabatan')->get() as $jabatan)
                                        <option value="{{ $jabatan->name_jabatan }}">{{ $jabatan->name_jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pangkat" class="form-label">Pangkat/Golongan:</label>
                            <select id="pangkat" name="pangkat" class="form-select form-control select2">
                                <option value="">--- PILIH ---</option>
                                @foreach (DB::table('app_md_golongan')->get() as $golongan)
                                    <option value="{{ $golongan->name_golongan }}">{{ $golongan->name_golongan }}</option>
                                @endforeach
                            </select>
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
                            <div id="detail" style="display: none">
                                <table class="table-bordered table ">
                                    <tbody id="details">
                                        <tr>
                                            <th colspan="2">Data IMBG</th>
                                            <th colspan="2">Pecah/Rincik </th>
                                            <th colspan="2">Belum Rincik/Pecah</th>
                                            <th colspan="2">Sudah Dimohon Surat Keterangan</th>
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
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="details[0][type]" style="width: 50px"
                                                    class="form-control">
                                            </td>
                                            <td><input type="text" name="details[0][jumlah]" style="width: 50px"
                                                    class="form-control"></td>
                                            <td><input type="text" name="details[0][pecah_type]" style="width: 50px"
                                                    class="form-control">
                                            </td>
                                            <td><input type="text" name="details[0][pecah_jumlah]" style="width: 50px"
                                                    class="form-control">
                                            </td>
                                            <td><input type="text" name="details[0][belum_rincik_type]"
                                                    style="width: 50px" class="form-control"></td>
                                            <td><input type="text" name="details[0][belum_rincik_jumlah]"
                                                    style="width: 50px" class="form-control"></td>
                                            <td><input type="text" name="details[0][sudah_dimohon_type]"
                                                    style="width: 50px" class="form-control"></td>
                                            <td><input type="text" name="details[0][sudah_dimohon_jumlah]"
                                                    style="width: 50px" class="form-control"></td>
                                            <td rowspan="2"><button type="button"
                                                    class="btn btn-danger remove-detail">-</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" class="p-3">
                                                <textarea type="text" name="details[0][sudah_dimohon_keterangan]" style="width: 100%;height:100px"
                                                    class="form-control"></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="add-detail" class="btn btn-success">Tambah Detail</button>
                                </div>
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
                                            <td><input type="text" name="details2[0][type]" class="form-control">
                                            </td>
                                            <td><input type="text" name="details2[0][blok]" class="form-control">
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
                            <button type="button" id="preview-surat" class="btn btn-primary">Preview Surat</button>
                            <button type="button" id="submit-surat" class="btn btn-success">Simpan dan Cetak</button>
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
            $('.select2-kabupaten').select2({
                width: '100%',
                placeholder: 'Pilih kabupaten',
                minimumInputLength: 2,
                ajax: {
                    url: '{{  route("master.kabupaten") }}', // URL to fetch kabupaten data
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
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
                console.log("Selected Provinsi:", e.params.data);
                loadKecamatan(e.params.data.id); // Load kabupaten based on selected provinsi
            });


            // Kecamatan Select2 with AJAX
            function loadKecamatan(kabupatenId) {
                $('.select2-kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route("master.kecamatan") }}', // URL to fetch kecamatan data
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
                        url: '{{ route("master.kelurahan") }}', // URL to fetch kelurahan data
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

        function initializeSelect2WithAjaxTerdahulu() {
            // Provinsi Select2 with AJAX
            $('.select2-provinsi-pemohon').select2({
                width: '100%',
                placeholder: 'Pilih Provinsi',
                minimumInputLength: 2,
                ajax: {
                    url: '{{  route("master.provinsi") }}', // URL to fetch provinsi data
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
                $('.select2-kabupaten-pemohon').select2({
                    width: '100%',
                    placeholder: 'Pilih Kabupaten/Kota',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route("master.kabupaten") }}', // URL to fetch kabupaten data
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
                $('.select2-kecamatan-pemohon').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route("master.kecamatan") }}', // URL to fetch kecamatan data
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
                $('.select2-kelurahan-pemohon').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    minimumInputLength: 2,
                    ajax: {
                        url: '{{ route("master.kelurahan") }}', // URL to fetch kelurahan data
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
            initializeSelect2WithAjaxTerdahulu();
        });


        let detailCount = 1;
        let detail2Count = 1;

        $('#add-detail').on('click', function() {
            const detailHtml = `
        <tr>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][type]" class="form-control" required></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][jumlah]" class="form-control" required></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][pecah_type]" class="form-control"></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][pecah_jumlah]" class="form-control"></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][belum_rincik_type]" class="form-control"></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][belum_rincik_jumlah]" class="form-control"></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][sudah_dimohon_type]" class="form-control"></td>
            <td><input type="text" style="width: 50px" name="details[${detailCount}][sudah_dimohon_jumlah]" class="form-control"></td>
            <td rowspan="2"><button type="button" class="btn btn-danger remove-detail">-</button></td>
        </tr>
        <tr>
            <td colspan="8" class="p-3">
                <textarea name="details[${detailCount}][sudah_dimohon_keterangan]" style="width: 100%; height: 100px" class="form-control"></textarea>
            </td>
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

        $('#details').on('click', '.remove-detail', function() {
            $(this).closest('tr').next().remove(); // Menghapus baris keterangan di bawahnya
            $(this).closest('tr').remove(); // Menghapus baris utama
        });

        $('#jenisSurat').on('change', function() {
            // if ($(this).val() === 'format-1') {
            //     $('#detail-2').hide();
            //     $

            // } else {
            //     $('#detail-2').show();
            // }

            if ($(this).val() === 'format-1') {
                $('#detail-2').hide();
                $('#detail').hide();
            } else if ($(this).val() === 'format-2') {
                $('#detail-2').hide();
                $('#detail').show();
            } else {
                $('#detail-2').show();
                $('#detail').show();
            }

        });
    </script>


    <script>
        $(document).ready(function() {
            $('#preview-surat').click(function() {
                // Initialize FormData with the form element
                let form = document.getElementById('suratForm');
                let formData = new FormData(form);

                // Add custom entries for details and details2 arrays
                // $('#details tr').each(function(index) {
                //     $(this).find('input, textarea').each(function() {
                //         let name = $(this).attr('name');
                //         let value = $(this).val();
                //         formData.append(`details[${index}][${name}]`, value);
                //     });
                // });

                // $('#details2 tr').each(function(index) {
                //     $(this).find('input').each(function() {
                //         let name = $(this).attr('name');
                //         let value = $(this).val();
                //         formData.append(`details2[${index}][${name}]`, value);
                //     });
                // });

                // Send data with AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('surat.preview') }}", true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.responseType = 'blob';

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var newWindow = window.open('', '_blank', 'width=800,height=600');
                        var blob = new Blob([xhr.response], {
                            type: 'application/pdf'
                        });
                        var url = URL.createObjectURL(blob);
                        newWindow.location.href = url;
                    } else {
                        Swal.close(); // Hide Swal loading indicator
                    }
                };

                xhr.send(formData);
            });

            // $('#submit-surat').click(function() {
            //     // Initialize FormData with the form element
            //     let form = document.getElementById('suratForm');
            //     let formData = new FormData(form);
            //     // Send data with AJAX
            //     var xhr = new XMLHttpRequest();
            //     xhr.open('POST', "{{ route('surat.store') }}", true);
            //     xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            //     xhr.responseType = 'blob';

            //     xhr.onload = function() {
            //         if (xhr.status === 200) {
            //             var newWindow = window.open('', '_blank', 'width=800,height=600');
            //             var blob = new Blob([xhr.response], {
            //                 type: 'application/pdf'
            //             });
            //             var url = URL.createObjectURL(blob);
            //             newWindow.location.href = url;
            //         } else {
            //             Swal.close(); // Hide Swal loading indicator
            //         }
            //     };

            //     xhr.send(formData);
            // });


            $('#submit-surat').click(function() {
                Swal.fire({
                    title: 'Menghasilkan Surat...',
                    text: 'Mohon tunggu, surat sedang diproses.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let form = document.getElementById('suratForm');
                let formData = new FormData(form);

                fetch("{{ route('surat.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();

                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Surat Berhasil Dibuat!',
                                text: 'Surat akan diunduh secara otomatis.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Mengunduh file secara otomatis
                            const fileUrl = `{{ asset('storage/surat/') }}/${data.file}`;
                            const link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = data.file; // Nama file yang diunduh
                            link.click();
                        } else {
                            Swal.fire({
                                title: 'Gagal Membuat Surat',
                                text: 'Terjadi kesalahan saat memproses surat.',
                                icon: 'error',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            title: 'Gagal Membuat Surat',
                            text: 'Terjadi kesalahan koneksi atau server.',
                            icon: 'error',
                        });
                    });
            });



        });
    </script>
@endsection
