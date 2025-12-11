<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    
    public function purchaseOrders()
    {   // One supplier can have many purchase orders ( 1:M )
        return $this->hasMany(PurchaseOrder::class);
    }
}