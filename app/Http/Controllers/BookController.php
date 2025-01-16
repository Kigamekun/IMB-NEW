<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function create()
    {
        return view('books.create');
    }

    public function index(Request $request)
    {
        $query = Book::query();

        // Filter berdasarkan judul
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter berdasarkan tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Dapatkan daftar buku dengan pagination
        $books = $query->paginate(12);

        // Ambil daftar kategori unik
        $categories = Book::select('category')->distinct()->pluck('category');

        return view('books.index', compact('books', 'categories'));
    }


public function show($id)
{
    $book = Book::with('pages')->findOrFail($id);
    return view('books.show', compact('book'));
}

public function destroy($id)
{
    $book = Book::findOrFail($id);

    // Hapus file gambar terkait
    Storage::disk('public')->delete($book->cover);
    foreach ($book->pages as $page) {
        Storage::disk('public')->delete($page->image);
    }

    // Hapus buku
    $book->delete();

    return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
}


    // public function store(Request $request)
    // {


    //     $coverPath = $request->file('cover')->store('books/covers', 'public');
    // $book = Book::create([
    //     'title' => $request->title,
    //     'year' => $request->year,
    //     'cover' => $coverPath,
    //     'category' => $request->category,
    //     'description' => $request->description,
    // ]);

    // // Opsi pertama: Menambahkan halaman secara manual
    // if ($request->has('pages')) {
    //     foreach ($request->pages as $page) {
    //         $pageImagePath = $page['image']->store('books/pages', 'public');
    //         Page::create([
    //             'book_id' => $book->id,
    //             'page_number' => $page['page_number'],
    //             'image' => $pageImagePath,
    //             'description' => $page['description'] ?? '-',
    //         ]);
    //     }
    // }

    // // Opsi kedua: Mengunggah multiple file upload
    // if ($request->has('page_images')) {
    //     $pageNumber = 1; // Halaman dimulai dari 1
    //     foreach ($request->file('page_images') as $file) {
    //         $pageImagePath = $file->store('books/pages', 'public');
    //         Page::create([
    //             'book_id' => $book->id,
    //             'page_number' => $pageNumber++,
    //             'image' => $pageImagePath,
    //             'description' => '-', // Default deskripsi
    //         ]);
    //     }
    // }

  
    //     return redirect()->route('books.create')->with('success', 'Buku berhasil ditambahkan!');
    // }


    public function store(Request $request)
{
    // Validasi form
    $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'required|integer',
        'cover' => 'required|image',
        'pages.*.image' => 'nullable|image',
        'pages.*.page_number' => 'nullable|integer|min:1',
        'pages.*.description' => 'nullable|string|max:500',
        'page_images.*' => 'nullable|image',
    ]);

    // Simpan cover
    $coverPath = $request->file('cover')->store('books/covers', 'public');

    // Simpan buku
    $book = Book::create([
        'title' => $request->title,
        'year' => $request->year,
        'cover' => $coverPath,
        'category' => $request->category,
        'description' => $request->description,
    ]);

    // Prioritas logika untuk page_images jika pages tidak valid
    if ($request->has('pages') && $this->isValidPages($request->pages)) {
        // Jika ada pages dengan data valid, simpan satu per satu
        $pagesData = [];
        foreach ($request->pages as $page) {
            if (isset($page['image'])) {
                $pageImagePath = $page['image']->store('books/pages', 'public');
                $pagesData[] = [
                    'book_id' => $book->id,
                    'page_number' => $page['page_number'] ?? 0,
                    'image' => $pageImagePath,
                    'description' => $page['description'] ?? '-',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($pagesData)) {
            Page::insert($pagesData);
        }
    } elseif ($request->has('page_images')) {
        // Jika tidak ada pages valid, proses file di page_images
        $pagesData = [];
        $pageNumber = 1;
        foreach ($request->file('page_images') as $file) {
            $pageImagePath = $file->store('books/pages', 'public');
            $pagesData[] = [
                'book_id' => $book->id,
                'page_number' => $pageNumber++,
                'image' => $pageImagePath,
                'description' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($pagesData)) {
            Page::insert($pagesData);
        }
    }

    return redirect()->route('books.create')->with('success', 'Buku berhasil ditambahkan!');
}

/**
 * Helper function untuk memeriksa validitas pages
 */
private function isValidPages($pages)
{
    foreach ($pages as $page) {
        if (isset($page['image'])) {
            return true;
        }
    }
    return false;
}



    public function edit($id)
{
    $book = Book::with('pages')->findOrFail($id);
    return view('books.edit', compact('book'));
}

public function update(Request $request, $id)
{

    $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'required',
        'category' => 'required|string|in:induk perum,pecahan,perluasan,non perum',
        'description' => 'nullable|string',
        'pages.*.id' => 'nullable|exists:pages,id',
        'pages.*.page_number' => 'required|string|max:10',
        'pages.*.description' => 'nullable|string',
    ]);

    // Update the book
    $book = Book::findOrFail($id);
    $book->title = $request->title;
    $book->year = $request->year;
    $book->category = $request->category;
    $book->description = $request->description;

    if ($request->hasFile('cover')) {
        $coverPath = $request->file('cover')->store('books/covers', 'public');
        $book->cover = $coverPath;
    }

    $book->save();

    // Update or create pages
    foreach ($request->pages as $key => $page) {
        if (isset($page['id'])) {
            // Update existing page
            $pageModel = Page::findOrFail($page['id']);
            $pageModel->page_number = $page['page_number'];
            $pageModel->description = $page['description'];
            if (isset($page['image'])) {
                $pageImagePath = $page['image']->store('books/pages', 'public');
                $pageModel->image = $pageImagePath;
            }
            $pageModel->save();
        } else {
            // Create new page
            $pageImagePath = $page['image']->store('books/pages', 'public');
            Page::create([
                'book_id' => $book->id,
                'page_number' => $page['page_number'],
                'image' => $pageImagePath,
                'description' => $page['description'],
            ]);
        }
    }

    return redirect()->route('books.edit', $id)->with('success', 'Buku berhasil diperbarui!');
}

}
