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
    Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->string('po_number')->unique(); // Purchase Order Number auto generate
        $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        $table->decimal('total_amount', 12, 2)->default(0);
        $table->enum('status', ['Pending', 'Approved', 'Completed'])->default('Pending');
        $table->date('expected_date')->nullable();
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
