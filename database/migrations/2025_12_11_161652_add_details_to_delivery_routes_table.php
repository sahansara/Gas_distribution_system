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
    Schema::table('delivery_routes', function (Blueprint $table) {
       
       
        //upgrade driver and assistant to foreign keys 
        $table->foreignId('driver_id')->nullable()->constrained('users'); // drive and assistant is staff member
        $table->foreignId('assistant_id')->nullable()->constrained('users'); 
        
        // Planning & Timings for delivery routes
        $table->time('planned_start_time')->nullable(); 
        $table->time('planned_end_time')->nullable();
        
        $table->dateTime('actual_start_time')->nullable();
        $table->dateTime('actual_end_time')->nullable();
        
        $table->enum('status', ['Scheduled', 'Active', 'Completed'])->default('Scheduled');
    });
}

public function down()
{    
    Schema::table('delivery_routes', function (Blueprint $table) {
        $table->dropColumn(['driver_id', 'assistant_id', 'planned_start_time', 'planned_end_time', 'actual_start_time', 'actual_end_time', 'status']);
    });
}

   
};
