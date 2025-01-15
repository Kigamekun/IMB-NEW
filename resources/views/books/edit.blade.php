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
        <br>
        <div class="mb-3">
            <label for="year" class="form-label">Tahun*</label>
            <input type="number" name="year" id="year" class="form-control" value="{{ $book->year }}" required>
        </div>
        <br>
        <div class="mb-3">
            <label for="cover" class="form-label">Sampul Buku (Opsional)</label>
            <input type="file" name="cover" id="cover" class="form-control" accept="image/*">
            <small>Cover saat ini: <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" height="100"></small>
        </div>
        <br>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori Buku*</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="induk perum" {{ $book->category == 'induk perum' ? 'selected' : '' }}>Induk Perum</option>
                <option value="pecahan" {{ $book->category == 'pecahan' ? 'selected' : '' }}>Pecahan</option>
                <option value="perluasan" {{ $book->category == 'perluasan' ? 'selected' : '' }}>Perluasan</option>
                <option value="non perum" {{ $book->category == 'non perum' ? 'selected' : '' }}>Non Perum</option>
            </select>
        </div>
        <br>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Buku (Opsional)</label>
            <textarea name="description" id="description" class="form-control">{{ $book->description }}</textarea>
        </div>
        <br>
        <h3>Halaman Buku</h3>
        <div id="pagesContainer">
            @foreach ($book->pages as $index => $page)
                <div class="page-group mb-3">
                    <h5>Halaman {{ $index + 1 }}</h5>
                    <input type="hidden" name="pages[{{ $index }}][id]" value="{{ $page->id }}">
                    <div class="mb-3">
                        <label for="page_number_{{ $index }}" class="form-label">Halaman Buku*</label>
                        <input type="text" name="pages[{{ $index }}][page_number]" id="page_number_{{ $index }}" class="form-control" value="{{ $page->page_number }}" required>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="page_image_{{ $index }}" class="form-label">Gambar Halaman (Opsional)</label>
                        <input type="file" name="pages[{{ $index }}][image]" id="page_image_{{ $index }}" class="form-control" accept="image/*">
                        <small>Gambar saat ini: <img src="{{ asset('storage/' . $page->image) }}" alt="Page Image" height="100"></small>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="page_description_{{ $index }}" class="form-label">Deskripsi Halaman (Opsional)</label>
                        <textarea name="pages[{{ $index }}][description]" id="page_description_{{ $index }}" class="form-control">{{ $page->description }}</textarea>
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
        <button type="button" id="addPageButton" class="btn btn-primary mb-3">Tambah Halaman</button>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>


@php
phpinfo();
@endphp

@php
$c = count($book->pages);
@endphp

@endsection

@section('scripts')
    <script>
        let pageIndex = @json($c);
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
            <br>
            <div class="mb-3">
                <label for="page_image_${pageIndex}" class="form-label">Gambar Halaman*</label>
                <input type="file" name="pages[${pageIndex - 1}][image]" id="page_image_${pageIndex}" class="form-control" accept="image/*">
            </div>
            <br>
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
