@extends('layouts.base')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{url('assets-adminlte/dist/css/select2.css')}}">
@endsection

@section('content')
    <div class="py-12">
        <div style="width: 90%;margin:auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-3xl font-bold">Tambah Data IMB Induk Perum</h3>
                    <br>
                    <form id="mainForm" action="{{ route('IMBIndukPerum.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="imb_induk" class="form-label">IMB Induk</label>
                                <input type="text" class="form-control" id="imb_induk" name="imb_induk" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_imb_induk" class="form-label">Tgl IMB Induk</label>
                                <input type="date" class="form-control" id="tgl_imb_induk" name="tgl_imb_induk" required>
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 10px">
                            <div class="col-md-6">
                                <label for="no_register" class="form-label">No Register</label>
                                <input type="text" class="form-control" id="no_register" name="no_register" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_register" class="form-label">Tgl Register</label>
                                <input type="date" class="form-control" id="tgl_register" name="tgl_register" required>
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 10px">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label for="atas_nama" class="form-label">Atas Nama</label>
                                <input type="text" class="form-control" id="atas_nama" name="atas_nama" required>
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 10px">
                            <div class="col-md-6">
                                <label for="lokasi_perumahan" class="form-label">Lokasi Perumahan</label>
                                <input type="text" class="form-control" id="lokasi_perumahan" name="lokasi_perumahan"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="kabupaten" class="form-label">Kabupaten</label>
                                <select class="form-control form-select select2-kabupaten" id="kabupaten" name="kabupaten" required>
                                    <option></option> <!-- Placeholder option -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" style="margin-top: 10px">
                            <div class="col-md-6">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <select class="form-control form-select select2-kecamatan" id="kecamatan" name="kecamatan" required>
                                    <option></option> <!-- Placeholder option -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kelurahan" class="form-label">Desa/Kelurahan</label>
                                <select class="form-control select2-kelurahan" id="kelurahan" name="kelurahan" required>
                                    <option></option> <!-- Placeholder option -->
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end" style="margin-top: 10px">
                            <div class="mb-3">
                                <label for="entryCount" class="form-label">Jumlah Entry Item:</label>
                                <input type="number" class="form-control" id="entryCount" min="1" name="entryCount"
                                    placeholder="Masukkan jumlah entry">
                                <button type="button" class="btn btn-primary mt-2 w-100" style="margin-top: 10px" onclick="generateForms()">Generate
                                    Item
                                    Forms</button>
                            </div>
                        </div>
                        <div id="formContainer"></div>
                        <button type="submit" class="btn btn-success mt-3" style="margin-top: 10px">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>


    <script>
        function initializeSelect2WithAjax() {
            $('.select2-kabupaten').select2({
                width: '100%',
                placeholder: 'Pilih Kabupaten',
                // minimumInputLength: 2,
                ajax: {
                    url: "{{ route('master.kabupaten') }}", // URL to fetch kabupaten data
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
                console.log("Selected Kabupaten:", e.params.data);
                loadKecamatan(e.params.data.id);
                $(".select2-kecamatan").val('').trigger('change')
                $(".select2-kelurahan").val('').trigger('change')
            });

            function loadKecamatan(kabupatenId) {
                 // Kecamatan Select2 with AJAX
                $('.select2-kecamatan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kecamatan',
                    // minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('master.kecamatan') }}", // URL to fetch kecamatan data
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                kabupaten_id: kabupatenId,
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
                    loadKelurahan(e.params.data.id);
                    $(".select2-kelurahan").val('').trigger('change')
                });
             }

            // Kelurahan Select2 with AJAX
            function loadKelurahan(kecamatanId) {
                $('.select2-kelurahan').select2({
                    width: '100%',
                    placeholder: 'Pilih Kelurahan',
                    // minimumInputLength: 2,
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





        // Fetch data from the database and convert it to a JavaScript object
        var jenisKegiatan = @json(DB::table('app_md_jeniskeg')->get());
        var fungsiBangunan = @json(DB::table('app_md_fungsibang')->get());

        function initializeSelect2() {
            $('.data-master').select2({
                width: '100%', // Adjust the width as needed
                placeholder: 'Pilih opsi',
            });
        }

        function generateForms() {
            const entryCount = document.getElementById('entryCount').value;
            const formContainer = document.getElementById('formContainer');
            formContainer.innerHTML = ''; // Reset form container

            for (let i = 0; i < entryCount; i++) {
                const section = document.createElement('div');
                section.className = 'mb-4 border rounded p-4';

                // Create options for fungsi_bangunan dynamically
                const fungsiBangunanOptions = fungsiBangunan.map(item =>
                    `<option value="${item.id_fungsibang}">${item.name_fungsibang}</option>`
                ).join('');

                // Create options for jenis_kegiatan dynamically
                const jenisKegiatanOptions = jenisKegiatan.map(item =>
                    `<option value="${item.name_jeniskeg}">${item.name_jeniskeg}</option>`
                ).join('');

                section.innerHTML = `
                    <h4>Item ${i + 1}</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type_${i}" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type_${i}" name="type_${i}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="luas_bangunan_${i}" class="form-label">Luas Bangunan</label>
                            <input type="text" class="form-control" id="luas_bangunan_${i}" name="luas_bangunan_${i}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_unit_${i}" class="form-label">Jumlah Unit</label>
                            <input type="number" class="form-control" id="jumlah_unit_${i}" name="jumlah_unit_${i}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fungsi_bangunan_${i}" class="form-label">Fungsi Bangunan</label>
                            <select class="form-select data-master" id="fungsi_bangunan_${i}" name="fungsi_bangunan_${i}" required>
                                ${fungsiBangunanOptions}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kegiatan_${i}" class="form-label">Jenis Kegiatan</label>
                            <select class="form-select data-master" id="jenis_kegiatan_${i}" name="jenis_kegiatan_${i}" required>
                                ${jenisKegiatanOptions}
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="keterangan_${i}" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan_${i}" name="keterangan_${i}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="scan_imb_${i}" class="form-label">Scan IMB</label>
                            <input type="file" class="form-control" id="scan_imb_${i}" name="scan_imb_${i}" accept=".pdf, .jpg, .jpeg, .png">
                        </div>
                    </div>
                `;

                formContainer.appendChild(section);
            }

            // Re-initialize Select2 for newly added elements
            initializeSelect2();
        }

        // Initialize Select2 on page load for any pre-existing select elements
        $(document).ready(function() {
            initializeSelect2();
            initializeSelect2WithAjax();
        });
    </script>
@endsection
