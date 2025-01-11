@extends('layouts.base')

@section('content')
<div class="container">
    <h1>Bulk Delete Data Tidak Sinkron</h1>

    {{-- Pilih Kategori --}}
    <form method="GET" action="{{ route('bulk-delete.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <select name="category" id="category" class="form-control">
                <option value="" selected>Pilih Kategori</option>
                    <option value="induk" {{ request('category') == 'induk' ? 'selected' : '' }}>IMB Induk</option>
                    <option value="pecahan" {{ request('category') == 'pecahan' ? 'selected' : '' }}>IMB Pecahan</option>
                    <option value="perluasan" {{ request('category') == 'perluasan' ? 'selected' : '' }}>IMB Perluasan</option>
                    <option value="non_perum" {{ request('category') == 'non_perum' ? 'selected' : '' }}>IMB Induk Non Perum</option>
                </select>
            </div>


            <div class="col-md-3">
                <select name="kabupaten" id="kabupaten" class="form-control">
                    <option value="">Pilih Kabupaten</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="kecamatan" id="kecamatan" class="form-control">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="desa" id="desa" class="form-control">
                    <option value="">Pilih Desa/Kelurahan</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-primary mt-2">Filter</button>
    </form>

    {{-- Tabel Data --}}
    <form method="POST" action="{{ route('bulk-delete.delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="category" value="{{ $category }}">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    @if($category == 'induk')
                        <th>IMB Induk</th>
                        <th>Tgl. IMB Induk</th>
                        <th>No. Register</th>
                        <th>Nama</th>
                        <th>Lokasi / Perumahan</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa / Kelurahan</th>
                    @elseif($category == 'pecahan')
                        <th>IMB Induk</th>
                        <th>Tgl. IMB Induk</th>
                        <th>IMB Pecahan</th>
                        <th>No. Register</th>
                        <th>Nama</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                    @elseif($category == 'perluasan')
                        <th>IMB Pecahan</th>
                        <th>No. IMB Perluasan</th>
                        <th>Tgl. Perluasan</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                    @elseif($category == 'non_perum')
                        <th>IMB Induk Non Perum</th>
                        <th>Tgl. IMB</th>
                        <th>No. Register</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    @if($category == 'induk')
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->no_imb_induk }}</td>
                            <td>{{ $item->tgl_imb_induk }}</td>
                            <td>{{ $item->no_register }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->lokasi_perumahan }}</td>
                            <td>{{ $item->kabupaten }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td>{{ $item->desa_kelurahan }}</td>
                        </tr>
                    @elseif($category == 'pecahan')
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->imb_induk_id }}</td>
                            <td>{{ $item->imb_pecahan }}</td>
                            <td>{{ $item->tgl_imb_pecahan }}</td>
                            <td>{{ $item->no_register }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kabupaten_lama }}</td>
                            <td>{{ $item->kecamatan_lama }}</td>
                            <td>{{ $item->kelurahan_lama }}</td>
                        </tr>
                    @elseif($category == 'perluasan')
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->no_imb_pecahan }}</td>
                            <td>{{ $item->no_imb_perluasan }}</td>
                            <td>{{ $item->tgl_imb_perluasan }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->kabupaten }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td>{{ $item->desa_kelurahan }}</td>
                        </tr>
                    @elseif($category == 'non_perum')
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $item->id }}"></td>
                            <td>{{ $item->no_imb_induk_non_perum }}</td>
                            <td>{{ $item->tgl_imb }}</td>
                            <td>{{ $item->no_register }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->kabupaten }}</td>
                            <td>{{ $item->kecamatan }}</td>
                            <td>{{ $item->desa_kelurahan }}</td>
                        </tr>
                @endif

                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($data->isNotEmpty())
            <button type="submit" class="btn btn-danger">Hapus Data Terpilih</button>
        @endif
    </form>
</div>

<script>
    // Select All Checkbox
    document.getElementById('select-all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('input[name="ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
    });
</script>



<script>
    // Update data dynamically based on category selection
    document.getElementById('category').addEventListener('change', function () {
        const category = this.value;

        // Clear dependent dropdowns
        document.getElementById('kabupaten').innerHTML = '<option value="">Pilih Kabupaten</option>';
        document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
        document.getElementById('desa').innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        if (category) {
            fetch(`/imb/get-locations?category=${category}`)
                .then(response => response.json())
                .then(data => {
                    if (data.kabupaten) {
                        const kabupatenDropdown = document.getElementById('kabupaten');
                        data.kabupaten.forEach(kabupaten => {
                            kabupatenDropdown.innerHTML += `<option value="${kabupaten}">${kabupaten}</option>`;
                        });
                    }
                });
        }
    });

    // Update Kecamatan dynamically based on Kabupaten selection
    document.getElementById('kabupaten').addEventListener('change', function () {
        const kabupaten = this.value;
        const category = document.getElementById('category').value;

        // Clear dependent dropdowns
        document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
        document.getElementById('desa').innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        if (kabupaten) {
            fetch(`/imb/get-kecamatan?category=${category}&kabupaten=${kabupaten}`)
                .then(response => response.json())
                .then(data => {
                    if (data.kecamatan) {
                        const kecamatanDropdown = document.getElementById('kecamatan');
                        data.kecamatan.forEach(kecamatan => {
                            kecamatanDropdown.innerHTML += `<option value="${kecamatan}">${kecamatan}</option>`;
                        });
                    }
                });
        }
    });

    // Update Desa dynamically based on Kecamatan selection
    document.getElementById('kecamatan').addEventListener('change', function () {
        const kecamatan = this.value;
        const category = document.getElementById('category').value;

        // Clear Desa dropdown
        document.getElementById('desa').innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';

        if (kecamatan) {
            fetch(`/imb/get-desa?category=${category}&kecamatan=${kecamatan}`)
                .then(response => response.json())
                .then(data => {
                    if (data.desa) {
                        const desaDropdown = document.getElementById('desa');
                        data.desa.forEach(desa => {
                            desaDropdown.innerHTML += `<option value="${desa}">${desa}</option>`;
                        });
                    }
                });
        }
    });
</script>
@endsection
