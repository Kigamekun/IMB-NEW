@extends('layouts.base')

@section('content')
    <!-- Lightbox2 CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery/css/lightgallery-bundle.min.css" />



    <style>
        .img-thumbnail {
            cursor: pointer;
            /* Tunjukkan bahwa gambar dapat diklik */
        }
    </style>

    <div class="container">
        <h1>Detail Buku: {{ $book->title }}</h1>

        <br>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>Judul Buku</th>
                    <td>{{ $book->title }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ ucfirst($book->category) }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $book->description ?? 'Tidak ada deskripsi.' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Sampul Buku -->
        <div class="mb-3">
            <div id="lightgallery-cover">
                <a href="{{ asset('storage/' . $book->cover) }}"
                    data-sub-html="<h4>Sampul Buku</h4><p>{{ $book->title }}</p>">
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Sampul Buku" class="img-thumbnail"
                        style="max-width: 200px;">
                </a>
            </div>
        </div>

        <p><strong>Kategori:</strong> {{ ucfirst($book->category) }}</p>
        <p><strong>Deskripsi:</strong> {{ $book->description ?? 'Tidak ada deskripsi.' }}</p>

        <!-- Halaman Buku -->
        <h3>Halaman Buku</h3>
        {{-- <div id="lightgallery-pages">
        <div class="row">
            @foreach ($book->pages as $index => $page)
            <div class="col-md-4 mb-3">
                <a href="{{ asset('storage/' . $page->image) }}" data-sub-html="<h4>Halaman {{ $page->page_number }}</h4><p>{{ $page->description ?? 'Tidak ada deskripsi.' }}</p>">
                    <img src="{{ asset('storage/' . $page->image) }}" alt="Halaman {{ $page->page_number }}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                </a>
            </div>
            @endforeach
        </div>
    </div> --}}






        <div id="lightgallery-pages" style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach ($book->pages as $index => $page)
                <!-- Setiap item harus menjadi child langsung dari container lightgallery -->
                <a href="{{ asset('storage/' . $page->image) }}"
                    data-sub-html="<h4>Halaman {{ $page->page_number }}</h4><p>{{ $page->description ?? 'Tidak ada deskripsi.' }}</p>"
                    class="item">
                    <img src="{{ asset('storage/' . $page->image) }}" alt="Halaman {{ $page->page_number }}"
                        class="img-thumbnail" style="max-width: 150px;">
                    <div class="mt-2">
                        <p class="mb-1"><strong>Halaman:</strong> {{ $page->page_number }}</p>
                        <p class="text-muted">{{ $page->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                </a>
            @endforeach
        </div>

        <br>
        <br>
        <br>
        <a href="{{ route('books.index') }}" class="btn btn-primary">Kembali ke Daftar Buku</a>
        <br>
        <br>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/lightgallery/lightgallery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightgallery/plugins/zoom/lg-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightgallery/plugins/thumbnail/lg-thumbnail.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lightGallery(document.getElementById('lightgallery-cover'), {
                plugins: [lgZoom, lgThumbnail],
                licenseKey: 'your_license_key', // Untuk versi Pro, tambahkan lisensi (opsional)
                speed: 500
            });

            lightGallery(document.getElementById('lightgallery-pages'), {
                plugins: [lgZoom, lgThumbnail],
                licenseKey: 'your_license_key',
                speed: 500
            });
        });
    </script>
@endsection
