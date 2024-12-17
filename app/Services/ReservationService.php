<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Receipt;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReservationService
{
    public function createReservation(User $user, Book $book)
    {
        if (!$this->canReserve($user, $book)) {
            throw new \Exception('Book is not available for reservation');
        }

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
            'reservation_date' => Carbon::now(),
            'return_date' => Carbon::now()->addDays(7)
        ]);

        $book->update(['availability' => 'Reserved']);

        return $reservation;
    }

    public function approveReservation(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'approved'
        ]);

        // Create a receipt for the approved reservation
        Receipt::create([
            'user_id' => $reservation->user_id,
            'book_id' => $reservation->book_id,
            'borrow_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(7),
            'status' => 'borrowed'
        ]);
    }

    private function canReserve(User $user, Book $book): bool
    {
        return $book->availability === 'Available' 
            && $user->reservations()->active()->count() < 3;
    }
} 