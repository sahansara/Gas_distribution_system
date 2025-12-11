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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // genarate unique order numbers 
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->foreignId('delivery_route_id')->constrained()->onDelete('cascade'); // Link to Delivery Routes
        
        // Financials
        $table->decimal('total_amount', 12, 2)->default(0);
        
        // statuses
        $table->enum('status', ['Pending', 'Loaded', 'Delivered', 'Completed', 'Cancelled'])->default('Pending');
        
        // urgent flag
        $table->boolean('is_urgent')->default(false);
        
        $table->foreignId('created_by')->constrained('users'); // Staff ID
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
