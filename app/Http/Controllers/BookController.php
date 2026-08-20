<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;

class BookController extends Controller
{
    /**
     * 書籍一覧を表示
     */
public function index(): View
{
    $books = Book::with('genres')->paginate(10);
    return view('books.index', compact('books'));
}



    /**
     * 書籍詳細を表示
     */
    public function show(Book $book)
{
    $book->load(['genres', 'reviews.user', 'reviews.likedByUsers']);
    return view('books.show', compact('book'));
}


    /**
     * 書籍登録フォームを表示
     */
    public function create(): View
{
    $book = new Book(); // 空のモデル
    $genres = Genre::all(); // ジャンル一覧

    return view('books.create', compact('book', 'genres'));
}


    /**
     * 書籍登録処理
     */
    public function store(BookStoreRequest $request)
    {
        $validated = $request->validated();
        $genreIds = $validated['genres'];
        unset($validated['genres']);

        $book = Book::create([
            ...$validated,
            'image_url' => $validated['image_url'] ?? 'https://placehold.co/600x400',
            'user_id' => User::first()->id,
        ]);

        $book->genres()->sync($genreIds);

        return redirect()->route('books.index');
    }
}
