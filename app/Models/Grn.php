<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $fillable = [
        'purchase_order_id', 'supplier_id', 'grn_number', 
        'received_date', 'status', 'remarks', 
        'created_by', 'approved_by', 'approved_at'
    ];
    // n : m Relationship with GrnItem
    public function items() {
        return $this->hasMany(GrnItem::class);
    }
   // n : 1 Relationship with PurchaseOrder and Supplier
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }
    // n : 1 Relationship with Supplier
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    // n : 1 Relationship with User 
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // auto genarate number for GRN
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($grn) {
            $year = date('Y');
            $lastGrn = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $number = $lastGrn ? intval(substr($lastGrn->grn_number, -4)) + 1 : 1;
            $grn->grn_number = 'GRN-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }
}