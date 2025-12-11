<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'gas_type_id', 'quantity', 'unit_price', 'subtotal'];
    // M:1 relationship with Order
    public function gasType() {
        return $this->belongsTo(GasType::class);
    }
}