<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryRoute;

class DeliveryRouteSeeder extends Seeder
{
    public function run()
    {
        DeliveryRoute::create(['name' => 'Route A - Colombo North', 'driver_name' => 'Kamal Perera', 'vehicle_number' => 'WP-LG-4500']);
        DeliveryRoute::create(['name' => 'Route B - Gampaha City', 'driver_name' => 'Saman Kumara', 'vehicle_number' => 'WP-CA-1234']);
        DeliveryRoute::create(['name' => 'Route C - Coastal Line', 'driver_name' => 'Nimal Silva', 'vehicle_number' => 'WP-DX-9876']);
        DeliveryRoute::create(['name' => 'Route D - Matara City', 'driver_name' => 'Sunil Jayasuriya', 'vehicle_number' => 'WP-KY-5678']);
    }
}