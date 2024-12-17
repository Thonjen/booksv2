<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\BookAvailabilityService;
use Illuminate\Support\Facades\Storage;
use App\Models\BookAction;
use Illuminate\Support\Facades\Auth;


class BookController extends Controller
{
    protected $availabilityService;

    public function __construct(BookAvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    const STATUS_AVAILABLE = 'Available';
    const STATUS_BORROWED = 'Borrowed';
    const STATUS_RESERVED = 'Reserved';

    public function index()
    {
        // Fetch all books with necessary relationships
        $books = Book::with([
            'bookRequests.user',  // Eager load book requests with user details
            'borrowings.user',    // Eager load borrowings with user details
            'reservations.user',  // Eager load reservations with user details
            'currentBorrower',    // Eager load current borrower relationship
            'currentReserver'     // Eager load current reserver relationship
        ])->get()->map(function ($book) {
            $currentBorrowing = $book->borrowings
                ->where('returned_at', null)
                ->first();  // Get the current borrowing where the book hasn't been returned
            $currentReserver = $book->reservations
                ->where('returned_date', null)
                ->first();  // Get the current borrowing where the book hasn't been returned
    
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'bookId' => $book->bookId,
                'publicationDate' => $book->publicationDate,
                'description' => $book->description,
                'availability' => $book->availability,
                'image_url' => $book->image_path ? Storage::url($book->image_path) : null,
                'current_borrower' => $currentBorrowing ? [
                    'fullname' => $currentBorrowing->user?->fullname,  // Use null-safe operator
                    'borrowed_date' => $currentBorrowing->borrowed_date,
                    'due_date' => $currentBorrowing->return_date,
                    'phone_number' => $currentBorrowing->user?->phone_number,
                ] : null,
                'current_reserver' => $currentReserver ? [
                    'fullname' => $currentReserver->user?->fullname,
                    'until_date' => $currentReserver?->return_date,
                    'phone_number' => $currentReserver->user?->phone_number,
                ] : null,
            ];
        });
    
        // Fetch book requests with necessary relationships
        $bookRequests = BookRequest::with(['user', 'book'])
            ->latest('created_at')  // Use 'latest' for sorting
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'book' => [
                        'title' => $request->book?->title  // Use null-safe operator
                    ],
                    'user' => [
                        'fullname' => $request->user?->fullname  // Updated to safely fetch user field
                    ],
                    'request_type' => $request->request_type,
                    'request_date' => $request->request_date,
                    'return_date' => $request->return_date,
                    'status' => $request->status
                ];
            });
    
        // Return data to the Inertia view
        return Inertia::render('Books/AvailableBooks', [
            'books' => $books,
            'bookRequests' => $bookRequests
        ]);
    }
    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'bookId' => 'required|string|unique:books',
            'publicationDate' => 'required|date',
            'description' => 'nullable|string',
            'availability' => 'required|in:Available,Borrowed,Reserved',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $path;
        }

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('message', 'Book added successfully');
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'availability' => 'required|in:Available,Borrowed,Reserved',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            
            $path = $request->file('image')->store('books', 'public');
            $validated['image_path'] = $path;
        }

        $book->update($validated);

        return back()->with('message', 'Book updated successfully');
    }

    public function destroy(Book $book)
    {
        if ($this->availabilityService->canDelete($book)) {
            // Delete the image if exists
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }
            
            $book->delete();
            return back()->with('message', 'Book deleted successfully');
        }

        return back()->withErrors(['message' => 'Cannot delete book that is currently borrowed or reserved']);
    }
    public function create()
    {
        return Inertia::render('Books/AddBooks');
    }

    public function return(Book $book)
    {
        $user = Auth::user();
        
        // Create book action record
        BookAction::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'type' => 'return',
            'action_date' => now(),
        ]);

        // Update book status
        $book->update(['availability' => 'Available']);
        
        return redirect()->back()->with('success', 'Book returned successfully');
    }

    // Add this method to handle reservation cancellations
    public function cancelReservation(Book $book)
    {
        $user = Auth::user();
        
        // Create book action record
        BookAction::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'type' => 'cancellation',
            'action_date' => now(),
        ]);

        // Update book status
        $book->update(['availability' => 'Available']);
        
        return redirect()->back()->with('success', 'Reservation cancelled successfully');
    }

}