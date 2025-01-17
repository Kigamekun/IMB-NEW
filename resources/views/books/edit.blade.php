@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Form Edit Buku</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data" id="bookForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Judul Buku*</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $book->title }}" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Tahun*</label>
                <input type="number" name="year" id="year" class="form-control" value="{{ $book->year }}" required>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Sampul Buku</label>
                <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah sampul</small>
                @if($book->cover)
                    <div class="mt-2">
                        <img src="{{ Storage::url($book->cover) }}" alt="Current Cover" style="max-width: 200px;">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategori Buku*</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['induk perum', 'pecahan', 'perluasan', 'non perum'] as $category)
                        <option value="{{ $category }}" {{ $book->category == $category ? 'selected' : '' }}>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Buku (Opsional)</label>
                <textarea name="description" id="description" class="form-control">{{ $book->description }}</textarea>
            </div>

            <h3>Edit Halaman</h3>
            <div class="mb-3">
                <label for="upload_option" class="form-label">Pilih Metode Edit Halaman*</label>
                <select id="upload_option" class="form-control" required>
                    <option value="manual">Edit Halaman Manual</option>
                    <option value="bulk">Unggah Gambar Bulk</option>
                </select>
            </div>

            <!-- Manual Upload -->
            <div id="manual_upload" class="upload-method">
                <h4>Edit Halaman Manual</h4>
                <div id="pagesContainer">
                    @foreach($book->pages as $index => $page)
                        <div class="page-group mb-3">
                            <h5>Halaman {{ $index + 1 }}</h5>
                            <div class="mb-3">
                                <label for="page_number_{{ $index + 1 }}" class="form-label">Halaman Buku*</label>
                                <input type="text" name="existing_pages[{{ $page->id }}][page_number]" 
                                       id="page_number_{{ $index + 1 }}" class="form-control" 
                                       value="{{ $page->page_number }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="page_image_{{ $index + 1 }}" class="form-label">Gambar Halaman</label>
                                <input type="file" name="existing_pages[{{ $page->id }}][image]" 
                                       id="page_image_{{ $index + 1 }}" class="form-control" accept="image/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                @if($page->image)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($page->image) }}" alt="Page {{ $page->page_number }}" 
                                             style="max-width: 200px;">
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="page_description_{{ $index + 1 }}" class="form-label">Deskripsi Halaman (Opsional)</label>
                                <textarea name="existing_pages[{{ $page->id }}][description]" 
                                          id="page_description_{{ $index + 1 }}" 
                                          class="form-control">{{ $page->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="existing_pages[{{ $page->id }}][delete]" 
                                           id="delete_page_{{ $page->id }}" class="form-check-input">
                                    <label for="delete_page_{{ $page->id }}" class="form-check-label">Hapus Halaman Ini</label>
                                </div>
                            </div>
                            <hr>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="addPageButton" class="btn btn-primary mb-3">Tambah Halaman Baru</button>
            </div>

            <!-- Bulk Upload -->
            <div id="bulk_upload" class="upload-method" style="display: none;">
                <h4>Unggah Gambar Bulk</h4>
                <div class="mb-3">
                    <label for="page_images" class="form-label">Unggah Gambar Halaman</label>
                    <input type="file" name="page_images[]" id="page_images" class="form-control" accept="image/*" multiple>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilihan Tindakan*</label>
                    <div class="form-check">
                        <input type="radio" name="bulk_action" id="replace" value="replace" class="form-check-input">
                        <label for="replace" class="form-check-label">Ganti Semua Data Sebelumnya</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="bulk_action" id="append" value="append" class="form-check-input">
                        <label for="append" class="form-check-label">Tambahkan ke Data Sebelumnya</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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

        let pageIndex = {{ count($book->pages) }};
        document.getElementById('addPageButton').addEventListener('click', () => {
            pageIndex++;
            const container = document.getElementById('pagesContainer');
            const pageGroup = document.createElement('div');
            pageGroup.classList.add('page-group', 'mb-3');
            pageGroup.innerHTML = `
                <h5>Halaman Baru ${pageIndex}</h5>
                <div class="mb-3">
                    <label for="page_number_new_${pageIndex}" class="form-label">Halaman Buku*</label>
                    <input type="text" name="new_pages[${pageIndex}][page_number]" id="page_number_new_${pageIndex}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="page_image_new_${pageIndex}" class="form-label">Gambar Halaman*</label>
                    <input type="file" name="new_pages[${pageIndex}][image]" id="page_image_new_${pageIndex}" class="form-control" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="page_description_new_${pageIndex}" class="form-label">Deskripsi Halaman (Opsional)</label>
                    <textarea name="new_pages[${pageIndex}][description]" id="page_description_new_${pageIndex}" class="form-control"></textarea>
                </div>
                <hr>
            `;
            container.appendChild(pageGroup);
        });
    </script>
@endsection