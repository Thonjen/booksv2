<?php

namespace App\Services;

use App\Models\Book;

class BookAvailabilityService
{
    public function canDelete(Book $book): bool
    {
        return !$book->receipts()->where('status', 'borrowed')->exists() 
            && !$book->reservations()->where('status', 'pending')->exists();
    }

    public function updateAvailability(Book $book)
    {
        $hasActiveReceipt = $book->receipts()
            ->where('status', 'borrowed')
            ->exists();

        $hasActiveReservation = $book->reservations()
            ->where('status', 'pending')
            ->exists();

        if ($hasActiveReceipt) {
            $book->availability = 'Borrowed';
        } elseif ($hasActiveReservation) {
            $book->availability = 'Reserved';
        } else {
            $book->availability = 'Available';
        }

        $book->save();
    }
} 