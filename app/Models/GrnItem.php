<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    protected $fillable = [
        'grn_id', 'gas_type_id', 'ordered_qty', 
        'received_qty', 'damaged_qty', 'missing_qty'
    ];

    

    // Relationships (1 to Many)
    public function grn() {
        return $this->belongsTo(Grn::class);
    }

    public function gasType() {
        return $this->belongsTo(GasType::class);
    }
}