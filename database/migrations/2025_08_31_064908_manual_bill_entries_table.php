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
        Schema::create('manual_bill_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_id');
            $table->string('billing_month');
            $table->decimal('start_reading', 10, 2);
            $table->decimal('end_reading', 10, 2);
            $table->decimal('units_consumed', 10, 2);
            $table->decimal('bill_amount', 10, 2);
            $table->integer('bill_status')->default(0)->comment('0 = unpaid, 1 = paid, 2 = pending');
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
        Schema::dropIfExists('manual_bill_entries');
    }
};
