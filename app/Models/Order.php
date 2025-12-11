<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'delivery_route_id', 
        'total_amount', 'status', 'is_urgent', 'created_by'
    ];
   
    // M:1 relationship with Customer
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    // M:1 relationship with DeliveryRoute
    public function route() { 
        return $this->belongsTo(DeliveryRoute::class, 'delivery_route_id');
    }
    // 1:M relationship with OrderItem
    public function items() {
        return $this->hasMany(OrderItem::class);
    }
    // M:1 relationship with User 
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // auto generate unique number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $year = date('Y');
            $lastOrder = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $number = $lastOrder ? intval(substr($lastOrder->order_number, -4)) + 1 : 1;
            $order->order_number = 'ORD-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }
}