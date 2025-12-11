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
    Schema::create('grns', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
        $table->foreignId('supplier_id')->constrained();
        $table->string('grn_number')->unique(); // Auto-generate number in Grn
        $table->date('received_date');
        $table->enum('status', ['Pending', 'Approved'])->default('Pending');
        $table->text('remarks')->nullable();
        
        // Audit Logs check and see logs
        $table->foreignId('created_by')->constrained('users'); // Staff
        $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin
        $table->timestamp('approved_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     * 
     */
    public function down(): void
    {
        Schema::dropIfExists('grns');
    }
};
