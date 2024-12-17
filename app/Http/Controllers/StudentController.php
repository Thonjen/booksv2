<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StudentController extends Controller
{
    public function index()
    {
        $books = Book::all()->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'image_url' => $book->image_path ? Storage::url($book->image_path) : null,
                'author' => $book->author,
                'availability' => $book->availability
            ];
        });

        return Inertia::render('Student/HomePage', [
            'books' => $books
        ]);
    }

    public function reserveBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'reservation_date' => 'required|date',
            'return_date' => 'required|date|after:reservation_date',
        ]);

        // Check if book is available
        $book = Book::find($request->book_id);
        if ($book->availability !== 'Available') {
            return response()->json([
                'message' => 'Book is not available for reservation'
            ], 400);
        }

        // Create reservation
        $reservation = Reservation::create([
            'user_id' => Auth::user()->id,
            'book_id' => $request->book_id,
            'reservation_date' => $request->reservation_date,
            'return_date' => $request->return_date,
            'status' => 'pending'
        ]);

        // Update book availability
        $book->update(['availability' => 'Reserved']);

        return response()->json([
            'message' => 'Book reserved successfully',
            'reservation' => $reservation
        ]);
    }
}