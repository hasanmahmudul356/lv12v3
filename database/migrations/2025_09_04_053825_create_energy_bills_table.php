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
            $table->integer('type')->comment('1=generator, 2=solar');
            $table->integer('user_id');
            $table->string('billing_month', 7)->comment('Format: YYYY-MM');
            $table->decimal('unit');
            $table->decimal('unit_rate')->comment('Per KWh');
            $table->decimal('customer_unit')->comment('Per KWh');
            $table->tinyInteger('status')->default(1)->comment('1=active,0=inactive');
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
