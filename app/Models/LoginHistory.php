<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = ['user_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
