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


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required|string|in:induk perum,pecahan,perluasan,non perum',
            'description' => 'nullable|string',
            'pages.*.page_number' => 'required|string|max:10',
            'pages.*.image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pages.*.description' => 'nullable|string',
        ]);

        // Save the book
        $coverPath = $request->file('cover')->store('books/covers', 'public');
        $book = Book::create([
            'title' => $request->title,
            'year' => $request->year,
            'cover' => $coverPath,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        // Save the pages
        foreach ($request->pages as $page) {
            $pageImagePath = $page['image']->store('books/pages', 'public');
            Page::create([
                'book_id' => $book->id,
                'page_number' => $page['page_number'],
                'image' => $pageImagePath,
                'description' => $page['description'],
            ]);
        }

        return redirect()->route('books.create')->with('success', 'Buku berhasil ditambahkan!');
    }
}
