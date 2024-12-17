<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Book extends Model
{
    // app/Models/Book.php
    protected $fillable = [
        'title',
        'author',
        'bookId',
        'publicationDate',
        'description',
        'image_path',
        'availability'
    ];
    
    protected $casts = [
        'publicationDate' => 'date',
    ];

    // Relationship with users who borrow books
    public function borrowers()
    {
        return $this->belongsToMany(User::class, 'book_user', 'book_id', 'user_id')
            ->withTimestamps()
            ->withPivot('borrow_date', 'return_date', 'status');
    }

    // Relationship with admin who added the book (if needed)
    public function addedBy()
    {
        return $this->belongsTo(Admin::class, 'added_by');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'Available');
    }

    public function currentBorrower()
    {
        return $this->belongsTo(User::class, 'current_borrower_id');
    }

    public function currentReserver()
    {
        return $this->belongsTo(User::class, 'current_reserver_id');
    }

    public function currentBorrowing()
    {
        return $this->hasOne(Borrowing::class)->where('returned_at', null);
    }

    public function currentReservation()
    {
        return $this->hasOne(BookRequest::class)
            ->where('request_type', 'reserve')
            ->where('status', 'approved')
            ->whereNull('completed_at');
    }
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
