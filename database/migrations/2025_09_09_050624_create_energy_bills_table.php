<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('energy_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_information_id');
            $table->integer('meter_no');
            $table->integer('type')->comment('1 = Generator, 2 = Solar');
            $table->decimal('customer_unit', 10, 2);
            $table->decimal('unit_rate', 10, 2);
            $table->decimal('bill_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_bills');
    }
};
