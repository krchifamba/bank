<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'number',
        'balance',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: Convenience accessor
    public function getNameAttribute()
    {
        return "{$this->user->first_name} {$this->user->last_name}";
    }
}
