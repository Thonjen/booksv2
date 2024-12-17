<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReserveController extends Controller
{
    public function index()
    {
        return Inertia::render('Reserve/Reserve', [
            'books' => Book::select('id', 'title', 'author', 'availability')
                ->where('availability', 'Available')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $book = Book::findOrFail($validated['book_id']);
        
        if ($book->availability !== 'Available') {
            return back()->withErrors(['message' => 'Book is no longer available']);
        }

        $reservation = Reservation::create([
            'book_id' => $validated['book_id'],
            'user_id' => Auth::id(),
            'status' => 'pending',
            'reservation_date' => now()
        ]);

        $book->update(['availability' => 'Reserved']);

        return back()->with('message', 'Book reserved successfully');
    }
    public function destroy(Book $book)
    {
        if ($book->availability === 'Reserved') {
            $book->update(['availability' => 'Available']);
            $book->currentReservation->update(['status' => 'cancelled']);
            return back()->with('message', 'Reservation cancelled successfully');
        }
        return back()->withErrors(['message' => 'Book is not currently reserved']);
    }
}