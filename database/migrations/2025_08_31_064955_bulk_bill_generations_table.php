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
        Schema::create('bulk_bill_generations', function (Blueprint $table) {
            $table->id();
            $table->string('billing_month');
            $table->decimal('unit_rate',10,2);
            $table->integer('user_id');
            $table->integer('status')->default(1)->comment('1=Active 2=InActive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_bill_generations');
    }
};
