<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // foreignId 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('customer_type')->nullable(); // dealer, commercial, individual
            $table->decimal('credit_limit', 14, 2)->default(0);
            $table->decimal('outstanding_balance', 14, 2)->default(0);

            // Cylinder tracking
            $table->integer('full_cylinders_issued')->default(0);
            $table->integer('empty_cylinders_returned')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
