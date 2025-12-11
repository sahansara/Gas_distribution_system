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
    Schema::table('purchase_orders', function (Blueprint $table) {
        // The actual bill number from the supplier
        $table->string('supplier_invoice_no')->nullable()->after('po_number');
        
        // The actual amount on the bill from the supplier
        $table->decimal('invoice_amount', 12, 2)->nullable()->after('total_amount');
    });
 }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            //
        });
    }
};
