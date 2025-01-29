@extends('layouts.base')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Galeri Buku</h1>
        <h2>Jumlah Buku yang Masuk :  {{ $books->count() }}</h2>

        <!-- Filter Form -->
        <form action="{{ route('books.index') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <!-- Filter Judul -->
                <div class="col-md-4">
                    <label for="title" class="form-label">Judul Buku</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ request('title') }}"
                        placeholder="Cari judul buku...">
                </div>

                <!-- Filter Tahun -->
                <div class="col-md-4">
                    <label for="year" class="form-label">Tahun</label>
                    <input type="number" name="year" id="year" class="form-control" value="{{ request('year') }}"
                        placeholder="Masukkan tahun">
                </div>

                <!-- Filter Kategori -->
                <div class="col-md-4">
                    <label for="category" class="form-label">Kategori</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Cari -->
                <div class="col-md-12 g-3" style="display: flex;justify-content:end;margin-top:15px;gap:10px;">
                    <a class="btn btn-info w-100" href="{{ route('books.create') }}">Create</a>
                    <button type="submit" class="btn btn-primary w-100 ">Filter</button>
                </div>
            </div>
        </form>

        <br>
        <!-- Daftar Buku -->
        <div class="row">
            @forelse ($books as $book)
                <div class="col-md-3 mb-4" style="margin-bottom: 10px;">
                    <div class="card ">
                        <img src="{{ asset('storage/' . $book->cover) }}" class="card-img-top"
                            style="border-radius: 10px;width:100%;height:300px; object-fit: cover;" alt="{{ $book->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($book->title, 25) }}</h5>
                            <p class="card-text">
                                Tahun : {{ $book->year }}<br>
                                <small class="text-muted">{{ ucfirst($book->category) }}</small>
                            </p>
                            <div style="display: flex;gap:5px;justify-content:end;">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary btn-sm w-100">Detail</a>
                                {{-- edit dan hapus  --}}
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm w-100">Edit</a>
                                   
                                   @if(!is_null(auth()->user()))
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Hapus</button>
                                    </form> 
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Tidak ada buku yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    </div>
    <br>
    <br>
@endsection
