@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Ubah Data IMB Pecahan</h3>
                    <br>
                    @if ($imbPecahan == null)
                        <div class="alert alert-danger" role="alert">
                            IMB Induk Tidak Ditemukan Mohon Input Terlebih Dahulu
                        </div>
                    @endif
                    <form id="mainForm" action="{{ route('IMBPerluasan.update', ['id' => $data->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="imb_pecahan" class="form-label">No. IMB Induk:</label>
                                <select name="imb_pecahan" id="imb_pecahan" class="form-select select2-imb-pecahan"
                                    required>
                                    <option value="{{ $imbPecahan ? $imbPecahan->imb_pecahan : $data->imb_pecahan }}"
                                        selected>
                                        {{ $imbPecahan ? $imbPecahan->imb_pecahan : $data->imb_pecahan }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_imb_pecahan" class="form-label">Tgl. IMB Induk:</label>
                                <input type="date" name="tgl_imb_pecahan" class="form-control" id="tgl_imb_pecahan"
                                    value="{{ $imbPecahan ? $imbPecahan->tgl_imb_pecahan : $data->tgl_imb_pecahan }}"
                                    readonly required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="imb_perluasan" class="form-label">No. IMB Pecahan:</label>
                                <input type="text" name="imb_perluasan" class="form-control" id="imb_perluasan"
                                    value="{{ $data->imb_pecahan }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_imb_perluasan" class="form-label">Tgl. Pecahan:</label>
                                <input type="date" name="tgl_imb_perluasan" class="form-control" id="tgl_imb_perluasan"
                                    value="{{ $data->tgl_imb_perluasan }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="no_register" class="form-label">No Register:</label>
                                <input type="text" name="no_register" class="form-control" id="no_register"
                                    value="{{ $data->no_register }}">
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_register" class="form-label">Tgl Register:</label>
                                <input type="date" name="tgl_register" class="form-control" id="tgl_register"
                                    value="{{ $data->tgl_register }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama:</label>
                                <input type="text" name="nama" class="form-control" id="nama"
                                    value="{{ $data->nama }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="atas_nama" class="form-label">Atas Nama:</label>
                                <input type="text" name="atas_nama" class="form-control" id="atas_nama"
                                    value="{{ $data->atas_nama }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan:</label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select select2 " required>
                                    @foreach (DB::table('app_md_jeniskeg')->get() as $jenis_keg)
                                        <option value="{{ $jenis_keg->name_jeniskeg }}"
                                            {{ $jenis_keg->id_jeniskeg == $data->jenis_kegiatan ? 'selected' : '' }}>
                                            {{ $jenis_keg->name_jeniskeg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fungsi_bangunan" class="form-label">Fungsi Bangunan:</label>
                                <select name="fungsi_bangunan" id="fungsi_bangunan" class="form-select select2" required>
                                    @foreach (DB::table('app_md_fungsibang')->get() as $fungsi_bang)
                                        <option value="{{ $fungsi_bang->id_fungsibang }}"
                                            {{ $fungsi_bang->id_fungsibang == $data->fungsi_bangunan ? 'selected' : '' }}>
                                            {{ $fungsi_bang->name_fungsibang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kecamatan" class="form-label">Kecamatan:</label>
                                <select name="kecamatan" id="kecamatan" class="form-select select2-kecamatan" required>
                                    <option value="{{ $data->kecamatan_code }}" selected>{{ $data->kecamatan }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="desa_kelurahan" class="form-label">Desa / Kelurahan:</label>
                                <select name="desa_kelurahan" id="desa_kelurahan"
                                    class="form-select select2 select2-kelurahan" required>
                                    @foreach (DB::table('master_subdistrict')->where('district_code', $data->kecamatan_code)->get() as $kel)
                                        <option value="{{ $kel->code }}"
                                            {{ $kel->code == $data->kelurahan_code ? 'selected' : '' }}>
                                            {{ $kel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type:</label>
                                <input type="text" name="type" class="form-control" id="type"
                                    value="{{ $data->type }}">
                            </div>
                            <div class="col-md-6">
                                <label for="luas_bangunan_perluasan" class="form-label">Luas Bangunan Perluasan:</label>
                                <input type="text" pattern="^\d+(\.\d+)?$" name="luas_bangunan_perluasan"
                                    class="form-control" value="{{ $data->luas_bangunan_perluasan }}"
                                    id="luas_bangunan_perluasan">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 ">
                                <label class="form-label">Luas Bangunan Lama:</label>
                                <input type="text" pattern="^\d+(\.\d+)?$" name="luas_bangunan_lama"
                                    class="form-control" value="{{ $data->luas_bangunan_lama }}"
                                    id="luas_bangunan_lama">
                            </div>
                            <div class="col-md-6">
                                <label for="blok" class="form-label">Blok:</label>
                                <input type="text" name="blok" class="form-control" id="blok"
                                    value="{{ $data->blok }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="no_blok" class="form-label">No Blok:</label>
                                <input type="text" name="no_blok" class="form-control" id="no_blok"
                                    value="{{ $data->no_blok }}">
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan" class="form-label">Keterangan:</label>
                                <textarea name="keterangan" class="form-control" id="keterangan">{{ $data->keterangan }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lokasi_perumahan" class="form-label">Lokasi / Perumahan:</label>
                                <input type="text" name="lokasi_perumahan" class="form-control" id="lokasi_perumahan"
                                    value="{{ $data->lokasi_perumahan }}">
                            </div>
                            <div class="col-md-6">
                                <label for="scan_imb" class="form-label">Scan IMB:</label>
                                <input type="file" name="scan_imb" class="form-control" id="scan_imb"
                                    accept=".pdf, .jpg, .jpeg, .png">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

        function initializeSelect2WithAjax() {
            // Kecamatan Select2 with AJAX
            $('.select2-kecamatan').select2({
                width: '100%',
                placeholder: 'Pilih Kecamatan',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('master.kecamatan') }}", // URL to fetch kecamatan data
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        console.log("Fetched data:", data); // Check data structure here
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

            // Kelurahan Select2 with AJAX
            function loadKelurahan(kecamatanId) {
                $('.select2-kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kelurahan') }}", // URL to fetch kelurahan data
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
                            console.log("Fetched kelurahan data:", data); // Check kelurahan data structure
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


        function initializeSelect2() {
            $('.select2-imb-pecahan').select2({
                width: '100%',
                placeholder: 'Pilih IMB Induk',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('master.imb-pecahan') }}", // URL to fetch kecamatan data
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        console.log("Fetched data:", data); // Check data structure here
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
                console.log("Selected IMB:", e.params.data);
                split = e.params.data.text.split(' | ');

                $('#tgl_imb_pecahan').val(split[1]);


            });
        }

        // Initialize Select2 on page load for any pre-existing select elements
        $(document).ready(function() {
            initializeSelect2();
            initializeSelect2WithAjax();
        });
    </script>
@endsection