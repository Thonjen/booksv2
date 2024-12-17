<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookRequestController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->all()); // Logs all incoming request data
    
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'request_type' => 'required|in:borrow,reserve',
            'request_date' => 'required|date',
            'return_date' => 'required|date|after:request_date',
        ]);
    
        $bookRequest = BookRequest::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'request_type' => $validated['request_type'],
            'request_date' => $validated['request_date'],
            'return_date' => $validated['return_date'],
            'status' => 'pending',
        ]);
    
        return redirect()->back()->with('success', 'Request submitted successfully');
    }
    

    public function approve($id)
    {
        $request = BookRequest::findOrFail($id);
        
        DB::transaction(function () use ($request) {
            $request->update(['status' => 'approved']);
            
            if ($request->request_type === 'borrow') {
                // Create borrowing record
                Borrowing::create([
                    'book_id' => $request->book_id,
                    'user_id' => $request->user_id,
                    'borrowed_date' => now(),
                    'return_date' => $request->return_date
                ]);
                $request->book->update(['availability' => 'Borrowed']);
            } else {
                // Create reservation record
                Reservation::create([
                    'book_id' => $request->book_id,
                    'user_id' => $request->user_id,
                    'reservation_date' => now(),
                    'return_date' => $request->return_date,
                    'status' => 'approved'
                ]);
                $request->book->update(['availability' => 'Reserved']);
            }
        });
    }

    public function reject($id)
    {
        $request = BookRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);
        
        return redirect()->back();
    }

    public function index()
    {
        $books = Book::with(['bookRequests.user'])->get()->map(function ($book) {
            $currentBorrowRequest = $book->bookRequests
                ->where('status', 'approved')
                ->where('request_type', 'borrow')
                ->where('return_date', '>', now())
                ->first();
    
            $currentReserveRequest = $book->bookRequests
                ->where('status', 'approved')
                ->where('request_type', 'reserve')
                ->where('return_date', '>', now())
                ->first();
    
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'bookId' => $book->bookId,
                'availability' => $book->availability,
                'image_url' => $book->image_path ? Storage::url($book->image_path) : null,
                'current_borrower' => $currentBorrowRequest ? [
                    'name' => $currentBorrowRequest->user->fullname,
                    'due_date' => $currentBorrowRequest->return_date->format('Y-m-d'),
                    'phone_number' => $currentBorrowRequest->user->phone_number,
                ] : null,
                'current_reserver' => $currentReserveRequest ? [
                    'name' => $currentReserveRequest->user->fullname,
                    'until_date' => $currentReserveRequest->return_date->format('Y-m-d'),
                    'phone_number' => $currentReserveRequest->user->phone_number,
                ] : null,
            ];
        });
    
        $bookRequests = BookRequest::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'book' => [
                        'title' => $request->book->title
                    ],
                    'user' => [
                        'name' => $request->user->fullname
                    ],
                    'request_type' => $request->request_type,
                    'request_date' => $request->request_date->format('Y-m-d'),
                    'return_date' => $request->return_date ? $request->return_date->format('Y-m-d') : null,
                    'status' => $request->status
                ];
            });
    
        return Inertia::render('Books/AvailableBooks', [
            'books' => $books,
            'bookRequests' => $bookRequests
        ]);
    }
    
    
}