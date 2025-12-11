<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\GasType;
use App\Models\SupplierProduct;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        //  Ensure Gas Types Exist
        $gas12 = GasType::firstOrCreate(
            ['name' => '12.5kg Cylinder'],
            ['weight' => 12.5, 'current_stock' => 0]
        );
        $gas5 = GasType::firstOrCreate(
            ['name' => '5kg Cylinder'],
            ['weight' => 5.0, 'current_stock' => 0]
        );
        $gas2_8 = GasType::firstOrCreate(
            ['name' => '2.8kg Cylinder'],
            ['weight' => 2.8, 'current_stock' => 0]
        );

        // 2. Create Suppliers
        $litro = Supplier::create([
            'name' => 'Litro Gas',
            'contact_person' => 'Mr. Perera',
            'phone' => '0112123456',
            'email' => 'orders@litro.lk',
            'address' => 'Colombo 02',
        ]);

        $laugfs = Supplier::create([
            'name' => 'Laugfs Gas',
            'contact_person' => 'Mrs. Silva',
            'phone' => '0112987654',
            'email' => 'sales@laugfs.lk',
            'address' => 'Mabima, Sapugaskanda',
        ]);

        // setup Supplier Products for Litro and Laugfs

        // Litro Prices
        SupplierProduct::create([
            'supplier_id' => $litro->id,
            'gas_type_id' => $gas12->id,
            'contract_price' => 4000.00 
        ]);
        SupplierProduct::create([
            'supplier_id' => $litro->id,
            'gas_type_id' => $gas5->id,
            'contract_price' => 1500.00 
        ]);
        SupplierProduct::create([
            'supplier_id' => $litro->id,
            'gas_type_id' => $gas2_8->id,
            'contract_price' => 1200.00 
        ]);

        // Laugfs Prices 
        SupplierProduct::create([
            'supplier_id' => $laugfs->id,
            'gas_type_id' => $gas12->id,
            'contract_price' => 4100.00
        ]);
        SupplierProduct::create([
            'supplier_id' => $laugfs->id,
            'gas_type_id' => $gas5->id,
            'contract_price' => 1600.00
        ]);
        SupplierProduct::create([
            'supplier_id' => $laugfs->id,
            'gas_type_id' => $gas2_8->id,
            'contract_price' => 1300.00
        ]);

        
    }
}