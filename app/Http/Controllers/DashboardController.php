<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookAction;
use App\Models\LoginHistory;
use App\Models\BookRequest;
use App\Models\Borrowing;
use App\Models\Reservation;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'totalBorrowings' => Borrowing::whereNull('returned_at')->count(),
            'totalReturns' => BookAction::where('type', 'return')->count(),
            'totalLoginsToday' => LoginHistory::whereDate('created_at', today())->count(),
            'activeBorrowings' => Borrowing::whereNull('returned_at')->count(),
            'activeReservations' => Reservation::where('status', 'approved')->count(),
            'pendingRequests' => BookRequest::where('status', 'pending')->count(),
        ];

        $loginsData = LoginHistory::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $topBooks = Book::withCount(['borrowings'])
            ->orderByDesc('borrowings_count')
            ->limit(5)
            ->get()
            ->map(fn($book) => [
                'title' => $book->title,
                'count' => $book->borrowings_count
            ]);

            $recentActions = BookAction::with(['book', 'user'])
            ->latest('action_date')
            ->limit(10)
            ->get()
            ->map(fn($action) => [
                'id' => $action->id,
                'book' => [
                    'title' => $action->book->title
                ],
                'user' => [
                    'fullname' => $action->user->fullname
                ],
                'type' => $action->type,
                'date' => $action->action_date
            ]);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'loginsData' => $loginsData,
            'topBooks' => $topBooks,
            'bookActions' => $recentActions,
        ]);
    }


    public function studentList()
    {
        $students = User::all();
        return Inertia::render('Student/StudentList', [
            'students' => $students
        ]);
    }

    public function storeStudent(Request $request)
{
    $validated = $request->validate([
        'fullname' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'studentid' => 'required|string|unique:users',
        'courseSection' => 'required|string',
        'gender' => 'required|in:Male,Female,Other',
        'password' => ['required', 'confirmed'],
        'phone_number' => 'nullable|string|max:20',
    ]);

    $user = User::create([
        'fullname' => $validated['fullname'],
        'email' => $validated['email'],
        'studentid' => $validated['studentid'],
        'courseSection' => $validated['courseSection'],
        'gender' => $validated['gender'],
        'password' => Hash::make($validated['password']),
        'phone_number' => $validated['phone_number'],
        'role' => 'user',
    ]);

    return redirect()->back()->with('message', 'Student created successfully');
}

    public function updateStudent(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'studentid' => 'required|string|unique:users,studentid,'.$user->id,
            'courseSection' => 'required|string',
            'gender' => 'required|in:Male,Female,Other',
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $updateData = [
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'studentid' => $validated['studentid'],
            'courseSection' => $validated['courseSection'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
        ];

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        return redirect()->back()->with('message', 'Student updated successfully');
    }

    public function deleteStudent(User $user)
    {
        $user->delete();
        return redirect()->back()->with('message', 'Student deleted successfully');
    }
}