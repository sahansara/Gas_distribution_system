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
    Schema::create('customer_prices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->foreignId('gas_type_id')->constrained()->onDelete('cascade');
        $table->decimal('price', 10, 2); // The special custom price for specific customer 
        $table->timestamps();

        $table->unique(['customer_id', 'gas_type_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_prices');
    }
};
