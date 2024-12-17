<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'receipt_number',
        'borrow_date',
        'due_date',
        'return_date',
        'fine_amount',
        'status'
    ];

    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
        'fine_amount' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($receipt) {
            $receipt->receipt_number = 'RCP-' . strtoupper(uniqid());
        });
    }
} 