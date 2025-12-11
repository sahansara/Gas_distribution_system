<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('category_prices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gas_type_id')->constrained()->onDelete('cascade');
        
        $table->string('customer_type'); 
        $table->decimal('price', 10, 2); // can add desimal numebr as price gas 
        $table->timestamps();
        
        // cannot have duplicate prices for the same type of gas
        $table->unique(['gas_type_id', 'customer_type']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_prices');
    }
};
