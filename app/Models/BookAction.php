<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookAction extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'type',
        'action_date',
        'notes'
    ];

    protected $casts = [
        'action_date' => 'datetime'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}