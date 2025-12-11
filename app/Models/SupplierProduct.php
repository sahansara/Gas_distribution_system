<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    protected $fillable = ['supplier_id', 'gas_type_id', 'contract_price'];
     // M :N relationships this here
    public function gasType() {
        return $this->belongsTo(GasType::class);
    }
}