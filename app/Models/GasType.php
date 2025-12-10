<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'weight', 'current_stock'];

   
    // M :N relationships this here
    public function categoryPrices()
    {
        return $this->hasMany(CategoryPrice::class);
    }
     // M :N relationships this here
    public function customerPrices()
    {
        return $this->hasMany(CustomerPrice::class);
    }

    
    
    public function getPriceForCustomer($customer)
    {
        //  check for customer specific price
        // Does this specific customer have a special price?
        $override = $this->customerPrices()
                         ->where('customer_id', $customer->id)
                         ->first();

        if ($override) {
            return $override->price;
        }

        //  check for category default price (Medium Priority as select category prices)
        // If no override, check the price for their category type
        $categoryPrice = $this->categoryPrices()
                              ->where('customer_type', $customer->customer_type)
                              ->first();

        if ($categoryPrice) {
            return $categoryPrice->price;
        }

        // no specific price found, return a default price or null
        return 0.00; 
    }
}