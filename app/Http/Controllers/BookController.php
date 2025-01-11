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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
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
