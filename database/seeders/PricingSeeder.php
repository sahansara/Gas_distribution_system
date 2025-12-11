<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GasType;
use App\Models\CategoryPrice;

class PricingSeeder extends Seeder
{

    public function run()
    {
        //  Gas Types
        $gas12 = GasType::create(['name' => '12.5kg Cylinder', 'weight' => 12.5, 'current_stock' => 100]);
        $gas5  = GasType::create(['name' => '5kg Cylinder', 'weight' => 5.0, 'current_stock' => 100]);
        $gas2_8 = GasType::create(['name' => '2.8kg Cylinder', 'weight' => 2.8, 'current_stock' => 100]);

        //  Prices for 2.8kg
        CategoryPrice::create(['gas_type_id' => $gas2_8->id, 'customer_type' => 'dealer', 'price' => 1000]);
        CategoryPrice::create(['gas_type_id' => $gas2_8->id, 'customer_type' => 'commercial', 'price' => 1200]);
        CategoryPrice::create(['gas_type_id' => $gas2_8->id, 'customer_type' => 'individual', 'price' => 1500]);
        //  for 5kg
        CategoryPrice::create(['gas_type_id' => $gas5->id, 'customer_type' => 'dealer', 'price' => 1500]);
        CategoryPrice::create(['gas_type_id' => $gas5->id, 'customer_type' => 'commercial', 'price' => 1700]);
        CategoryPrice::create(['gas_type_id' => $gas5->id, 'customer_type' => 'individual', 'price' => 2000]);

        //  for 12.5kg
        CategoryPrice::create(['gas_type_id' => $gas12->id, 'customer_type' => 'dealer', 'price' => 4000]);
        CategoryPrice::create(['gas_type_id' => $gas12->id, 'customer_type' => 'commercial', 'price' => 4500]);
        CategoryPrice::create(['gas_type_id' => $gas12->id, 'customer_type' => 'individual', 'price' => 5000]);

        
    }
}