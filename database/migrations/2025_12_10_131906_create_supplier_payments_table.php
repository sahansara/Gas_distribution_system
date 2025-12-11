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
    Schema::create('supplier_payments', function (Blueprint $table) {
        $table->id();
        
        // foreign keys on supplier table and purchase orders table
        $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
        
        // Payment Details
        $table->decimal('amount', 12, 2); 
        $table->date('payment_date');
        
        //  Cheque Details 
        $table->string('payment_mode')->default('Cheque'); 
        $table->string('cheque_number')->nullable();
        $table->string('bank_name')->nullable();
        $table->date('cheque_date')->nullable();
        
        // additional notes some one just put in the table
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
