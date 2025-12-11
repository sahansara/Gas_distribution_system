<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'total_amount', 'status', 'expected_date', 'remarks'
    ];

    // relationships(s)
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Auto-generate PO number on creating

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            $year = date('Y');
            // Find the last PO created this year
            $lastPo = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            
            if ($lastPo) {
                // Extract number and increment
                $lastNumber = intval(substr($lastPo->po_number, -4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $po->po_number = 'PO-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}