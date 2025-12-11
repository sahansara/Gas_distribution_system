<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'customer_type',
        'credit_limit',
        'outstanding_balance',
        'full_cylinders_issued',
        'empty_cylinders_returned',
    ];

    // Relationship back to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
