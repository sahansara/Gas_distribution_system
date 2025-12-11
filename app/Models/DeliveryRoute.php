<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    protected $fillable = ['name', 'driver_name', 'vehicle_number', 'is_active'];
}