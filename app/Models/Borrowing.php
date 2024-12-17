<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_date',
        'return_date',
        'returned_at'
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'return_date' => 'datetime',
        'returned_at' => 'datetime'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}