<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'account_id',
        'type',         // deposit, withdrawal, transfer
        'amount',
        'spread_amount',
        'from_account_number',
        'to_account_number',
        'description',
        'transaction_date',
    ];

    /**
     * Casts
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship: A Transaction belongs to an Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
