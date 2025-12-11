<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    protected $fillable = [
        'name', 
        'vehicle_number', 
        'driver_id', 
        'assistant_id', 
        'planned_start_time', 
        'planned_end_time', 
        'actual_start_time', 
        'actual_end_time', 
        'status', 
        'is_active'
    ];

    // Relationships to Staff ( 1 to Many)
    public function driver() {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Relationships to Staff ( 1 to Many)
    public function assistant() {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    // Relationship to Orders ( Many to 1)
    public function orders() {
        return $this->hasMany(Order::class);
    }
}