<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('role')->nullable()->after('password'); 
            // customer_type is relevant only for users with role = 'customer'
            $table->string('customer_type')->nullable()->after('role'); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'role']);
        });
    }
};
