<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'amount',
        'payment_date',
        'payment_mode',
        'cheque_number',
        'bank_name',
        'cheque_date',
        'notes'
    ];

    // Relationships(1 to Many) supplier table and supplier payment table
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    // Relationships(1 to Many) pusrchase order table and supplier payment table
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}