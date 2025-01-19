@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Form Tambah Buku</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Judul Buku*</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Tahun*</label>
                <input type="number" name="year" id="year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Sampul Buku*</label>
                <input type="file" name="cover" id="cover" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategori Buku*</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="induk perum">Induk Perum</option>
                    <option value="pecahan">Pecahan</option>
                    <option value="perluasan">Perluasan</option>
                    <option value="non perum">Non Perum</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Buku (Opsional)</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <h3>Tambahkan Halaman</h3>
            <div class="mb-3">
                <label for="upload_option" class="form-label">Pilih Metode Tambah Halaman*</label>
                <select id="upload_option" class="form-control" required>
                    <option value="manual">Tambah Halaman Manual</option>
                    <option value="bulk">Unggah Gambar Bulk</option>
                </select>
            </div>

            <!-- Manual Upload -->
            <div id="manual_upload" class="upload-method">
                <h4>Tambah Halaman Manual</h4>
                <div id="pagesContainer">
                    <div class="page-group mb-3">
                        <h5>Halaman 1</h5>
                        <div class="mb-3">
                            <label for="page_number_1" class="form-label">Halaman Buku*</label>
                            <input type="text" name="pages[0][page_number]" id="page_number_1" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="page_image_1" class="form-label">Gambar Halaman*</label>
                            <input type="file" name="pages[0][image]" id="page_image_1" class="form-control" accept="image/*" >
                        </div>
                        <div class="mb-3">
                            <label for="page_description_1" class="form-label">Deskripsi Halaman (Opsional)</label>
                            <textarea name="pages[0][description]" id="page_description_1" class="form-control"></textarea>
                        </div>
                        <hr>
                    </div>
                </div>
                <button type="button" id="addPageButton" class="btn btn-primary mb-3">Tambah Halaman</button>
            </div>

            <!-- Bulk Upload -->
            <div id="bulk_upload" class="upload-method" style="display: none;">
                <h4>Unggah Gambar Bulk</h4>
                <div class="mb-3">
                    <label for="page_images" class="form-label">Unggah Gambar Halaman*</label>
                    <input type="file" name="page_images[]" id="page_images" class="form-control" accept="image/*" multiple >
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilihan Tindakan*</label>
                    <div class="form-check">
                        <input type="radio" name="bulk_action" id="replace" value="replace" class="form-check-input" >
                        <label for="replace" class="form-check-label">Ganti Semua Data Sebelumnya</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="bulk_action" id="append" value="append" class="form-check-input" >
                        <label for="append" class="form-check-label">Tambahkan ke Data Sebelumnya</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan Buku</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('upload_option').addEventListener('change', function () {
            const manual = document.getElementById('manual_upload');
            const bulk = document.getElementById('bulk_upload');
            if (this.value === 'manual') {
                manual.style.display = 'block';
                bulk.style.display = 'none';
            } else {
                manual.style.display = 'none';
                bulk.style.display = 'block';
            }
        });

        let pageIndex = 1;
        document.getElementById('addPageButton').addEventListener('click', () => {
            pageIndex++;
            const container = document.getElementById('pagesContainer');
            const pageGroup = document.createElement('div');
            pageGroup.classList.add('page-group', 'mb-3');
            pageGroup.innerHTML = `
                <h5>Halaman ${pageIndex}</h5>
                <div class="mb-3">
                    <label for="page_number_${pageIndex}" class="form-label">Halaman Buku*</label>
                    <input type="text" name="pages[${pageIndex - 1}][page_number]" id="page_number_${pageIndex}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="page_image_${pageIndex}" class="form-label">Gambar Halaman*</label>
                    <input type="file" name="pages[${pageIndex - 1}][image]" id="page_image_${pageIndex}" class="form-control" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="page_description_${pageIndex}" class="form-label">Deskripsi Halaman (Opsional)</label>
                    <textarea name="pages[${pageIndex - 1}][description]" id="page_description_${pageIndex}" class="form-control"></textarea>
                </div>
                <hr>
            `;
            container.appendChild(pageGroup);
        });
    </script>
@endsection
