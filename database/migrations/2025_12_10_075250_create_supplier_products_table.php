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
    Schema::create('supplier_products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        $table->foreignId('gas_type_id')->constrained()->onDelete('cascade');
        $table->decimal('contract_price', 10, 2); // supplier contract price its depend on the supplier
        $table->timestamps();

        // stop duplicate entries for same supplier and gas type
        $table->unique(['supplier_id', 'gas_type_id']); 
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
