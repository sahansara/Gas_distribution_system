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
    Schema::create('grn_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grn_id')->constrained()->onDelete('cascade');
        $table->foreignId('gas_type_id')->constrained();
        
        $table->integer('ordered_qty');   // ordered cylinders in PO
        $table->integer('received_qty');  // good cylinders received coming from supplier
        $table->integer('damaged_qty')->default(0); // damage cylinders no stored table
        $table->integer('missing_qty')->default(0); // Shortage (Ordered - Received - Damaged)
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_items');
    }
};
