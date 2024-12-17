<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ReceiptController extends Controller
{
    public function index()
    {
        return Inertia::render('Receipt/Receipt', [
            'books' => Book::available()->get(),
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        // Check if book is available
        $book = Book::findOrFail($validated['book_id']);
        if ($book->availability !== 'Available') {
            return back()->withErrors(['message' => 'Book is not available']);
        }

        // Create receipt
        $receipt = Receipt::create($validated);
        
        // Update book availability
        $book->update(['availability' => 'Borrowed']);

        return back()->with('message', 'Receipt created successfully');
    }

    public function history()
    {
        return Inertia::render('Receipt/Receipthistory', [
            'receipts' => Receipt::with(['user', 'book'])
                ->latest()
                ->get()
                ->map(function ($receipt) {
                    return [
                        'id' => $receipt->id,
                        'receipt_number' => $receipt->receipt_number,
                        'borrowerName' => $receipt->user->fullname,
                        'bookTitle' => $receipt->book->title,
                        'ISBN' => $receipt->book->bookId,
                        'borrowDate' => $receipt->borrow_date->format('Y-m-d'),
                        'dueDate' => $receipt->due_date->format('Y-m-d'),
                        'returnDate' => $receipt->return_date ? $receipt->return_date->format('Y-m-d') : null,
                        'status' => $receipt->status,
                        'fine_amount' => $receipt->fine_amount
                    ];
                })
        ]);
    }

    public function return(Receipt $receipt)
    {
        $now = Carbon::now();
        $dueDate = Carbon::parse($receipt->due_date);
        
        if ($now->gt($dueDate)) {
            $daysLate = $now->diffInDays($dueDate);
            $fineAmount = $daysLate * 50; // ₱50 per day
            $receipt->fine_amount = $fineAmount;
        }

        $receipt->return_date = $now;
        $receipt->status = 'returned';
        $receipt->save();

        // Update book availability
        $receipt->book->update(['availability' => 'Available']);

        return back()->with('message', 'Book returned successfully');
    }

    public function destroy(Receipt $receipt)
    {
        if ($receipt->status === 'active') {
            return back()->withErrors(['message' => 'Cannot delete active receipt']);
        }

        $receipt->delete();
        return back()->with('message', 'Receipt deleted successfully');
    }
}