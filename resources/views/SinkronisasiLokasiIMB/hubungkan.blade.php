@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="py-12">
        <div style="width: 90%;margin:auto">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Ubah Data IMB
                        @isset($_GET['type'])
                            @switch($_GET['type'])
                                @case('')
                                    Induk
                                @break

                                @case('pecahan')
                                    Pecahan
                                @break

                                @case('perluasan')
                                    Perluasan
                                @break

                                @case('non_perum')
                                    Induk Non Perum
                                @break

                                @default
                            @endswitch
                        @else
                            Induk
                        @endisset
                    </h3>
                    <br>

                    @isset($_GET['type'])
                        @switch($_GET['type'])
                            @case('')
                                <form id="mainForm" action="{{ route('SinkronisasiLokasiIMB.hubungkanStore') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="no_imb_induk" class="form-label">Kecamatans Lama:</label>
                                            <select name="kecamatan_lama" id="kecamatan_lama" class="form-control select2-kecamatan">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach (DB::table('imb_pecahan')->groupBy('kecamatan_lama')->where('kecamatan_lama', '!=', '')->select('kecamatan_lama')->get() as $kecamatan)
                                                    <option value="{{ $kecamatan->kecamatan_lama }}">{{ $kecamatan->kecamatan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 " >
                                            <label for="tgl_imb_induk" class="form-label">Kelurahan Lama:</label>
                                            <select name="kelurahan_lama" id="kelurahan_lama" class="form-control select2-kelurahan">
                                                <option value="">Pilih Kelurahan</option>
                                                @foreach (DB::table('imb_induk_perum')->groupBy('kelurahan_lama')->where('kelurahan_lama', '!=', '')->select('kelurahan_lama')->get() as $kelurahan)
                                                    <option value="{{ $kelurahan->kelurahan_lama }}">{{ $kelurahan->kelurahan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 " >
                                        <div class="col-md-6">
                                            <label for="no_imb_pecahan" class="form-label">Kecamatans Baru:</label>
                                            <select name="kecamatan" id="kecamatan" class="form-select select2-kecamatan" required>
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan"
                                                class="form-select select2-kelurahan" required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div>

                                        {{-- <div class="col-md-6">
                                            <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan" class="form-select select2-kelurahan" required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div> --}}
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            @break

                            @case('pecahan')
                                <form id="mainForm" action="{{ route('SinkronisasiLokasiIMB.hubungkanStore') }}?type=pecahan" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="no_imb_induk" class="form-label">Kecamatan Lama:</label>
                                            <select name="kecamatan_lama" id="kecamatan_lama" class="form-control select2-kecamatan">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach (DB::table('imb_pecahan')->groupBy('kecamatan_lama')->where('kecamatan_lama', '!=', '')->select('kecamatan_lama')->get() as $kecamatan)
                                                    <option value="{{ $kecamatan->kecamatan_lama }}">{{ $kecamatan->kecamatan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_imb_induk" class="form-label">Kelurahan Lama:</label>
                                            <select name="kelurahan_lama" id="kelurahan_lama" class="form-control select2-kelurahan">
                                                <option value="">Pilih Kelurahan</option>
                                                @foreach (DB::table('imb_pecahan')->groupBy('kelurahan_lama')->where('kelurahan_lama', '!=', '')->select('kelurahan_lama')->get() as $kelurahan)
                                                    <option value="{{ $kelurahan->kelurahan_lama }}">{{ $kelurahan->kelurahan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="margin-top: 10px">
                                        <div class="col-md-6">
                                            <label for="no_imb_pecahan" class="form-label">Kecamatan Baru:</label>
                                            <select name="kecamatan" id="kecamatan" class="form-control select2-kecamatan" required>
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan"
                                                class="form-control  select2-kelurahan" required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3" style="margin-top: 10px">Submit</button>
                                </form>
                            @break

                            @case('perluasan')
                                <form id="mainForm" action="{{ route('SinkronisasiLokasiIMB.hubungkanStore') }}?type=perluasan" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="no_imb_induk" class="form-label">Kecamatan Lama:</label>
                                            <select name="kecamatan_lama" id="kecamatan_lama" class="form-control select2-kecamatan">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach (DB::table('imb_perluasan')->groupBy('kecamatan_lama')->where('kecamatan_lama', '!=', '')->select('kecamatan_lama')->get() as $kecamatan)
                                                    <option value="{{ $kecamatan->kecamatan_lama }}">{{ $kecamatan->kecamatan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_imb_induk" class="form-label">Kelurahan Lama:</label>
                                            <select name="kelurahan_lama" id="kelurahan_lama" class="form-control select2-kelurahan">
                                                <option value="">Pilih Kelurahan</option>
                                                @foreach (DB::table('imb_perluasan')->groupBy('kelurahan_lama')->where('kelurahan_lama', '!=', '')->select('kelurahan_lama')->get() as $kelurahan)
                                                    <option value="{{ $kelurahan->kelurahan_lama }}">{{ $kelurahan->kelurahan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="margin-top:10px !important;">
                                        <div class="col-md-6">
                                            <label for="no_imb_pecahan" class="form-label">Kecamatan Baru:</label>
                                            <select name="kecamatan" id="kecamatan" class="form-select select2-kecamatan" required>
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan"
                                                class="form-select select2 select2-kelurahan" style="width:100%;" required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-3" style="margin-top:10px !important;">Submit</button>
                                </form>
                            @break

                            @case('non_perum')
                                <form id="mainForm" action="{{ route('SinkronisasiLokasiIMB.hubungkanStore') }}?type=non_perum" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="no_imb_induk" class="form-label">Kecamatan Lama:</label>
                                            <select name="kecamatan_lama" id="kecamatan_lama" class="form-control">
                                                <option value="">Pilih Kecamatan</option>
                                                @foreach (DB::table('imb_induk_non_perum')->groupBy('kecamatan_lama')->where('kecamatan_lama', '!=', '')->select('kecamatan_lama')->get() as $kecamatan)
                                                    <option value="{{ $kecamatan->kecamatan_lama }}">{{ $kecamatan->kecamatan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_imb_induk" class="form-label">Kelurahan Lama:</label>
                                            <select name="kelurahan_lama" id="kelurahan_lama" class="form-control">
                                                <option value="">Pilih Kelurahan</option>
                                                @foreach (DB::table('imb_induk_non_perum')->groupBy('kelurahan_lama')->where('kelurahan_lama', '!=', '')->select('kelurahan_lama')->get() as $kelurahan)
                                                    <option value="{{ $kelurahan->kelurahan_lama }}">{{ $kelurahan->kelurahan_lama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3" style="margin-top: 10px;">
                                        <div class="col-md-6">
                                            <label for="no_imb_pecahan" class="form-label">Kecamatan Baru:</label>
                                            <select name="kecamatan" id="kecamatan" class="form-select select2-kecamatan" required>
                                                <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan"
                                                class="form-select select2 select2-kelurahan" style="width:100%;" required>
                                                <option value="">Pilih Desa/Kelurahan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                                </form>
                            @break

                            @default
                        @endswitch
                    @else

                        <form id="mainForm" action="{{ route('SinkronisasiLokasiIMB.hubungkanStore') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="no_imb_induk" class="form-label">Kecamatan Lama:</label>
                                    <select name="kecamatan_lama" id="kecamatan_lama" class="form-control">
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach (DB::table('imb_induk_perum')->groupBy('kecamatan_lama')->where('kecamatan_lama', '!=', '')->select('kecamatan_lama')->get() as $kecamatan)
                                            <option value="{{ $kecamatan->kecamatan_lama }}">{{ $kecamatan->kecamatan_lama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_imb_induk" class="form-label">Kelurahan Lama:</label>
                                    <select name="kelurahan_lama" id="kelurahan_lama" class="form-control">
                                        <option value="">Pilih Kelurahan</option>
                                        @foreach (DB::table('imb_induk_perum')->groupBy('kelurahan_lama')->where('kelurahan_lama', '!=', '')->select('kelurahan_lama')->get() as $kelurahan)
                                            <option value="{{ $kelurahan->kelurahan_lama }}">{{ $kelurahan->kelurahan_lama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" style="margin-top:10px;">
                                <div class="col-md-6">
                                    <label for="no_imb_pecahan" class="form-label">Kecamatan Baru:</label>
                                    <select name="kecamatan" id="kecamatan" class="form-select select2-kecamatan" required>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_pecahan" class="form-label">Kelurahan Baru:</label>
                                    <select name="desa_kelurahan" id="desa_kelurahan"
                                        class="form-select select2 select2-kelurahan" style="width: 100%" required>
                                        <option value="">Pilih Desa/Kelurahan</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success mt-3">Submit</button>
                        </form>
                    @endisset

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
            $('.select2-imb-induk').select2({
                width: '100%',
                placeholder: 'Pilih IMB Induk',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('master.imb-induk') }}", // URL to fetch kecamatan data
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

                $('#tgl_imb_induk').val(split[1]);


            });
        }

        // Initialize Select2 on page load for any pre-existing select elements
        $(document).ready(function() {
            initializeSelect2();
            initializeSelect2WithAjax();
        });
    </script>
@endsection
