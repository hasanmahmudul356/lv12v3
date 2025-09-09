<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_id');
            $table->string('billing_month', 7)->comment('Format: YYYY-MM');
            $table->decimal('start_reading',10,2)->comment('(KWh)');
            $table->decimal('end_reading',10,2)->comment('(KWh)');
            $table->decimal('units_consumed',10,2)->comment('(KWh)');
            $table->decimal('bill_amount',10,2);
            $table->decimal('nesco_unit',10,2)->nullable();
            $table->decimal('unit_rate',10,2)->nullable();
            $table->decimal('nesco_bill',10,2)->nullable();
            $table->integer('bill_status')->default(0)->comment('0 = unpaid, 1 = paid, 2 = pending');
            $table->integer('status')->default(1)->comment('1=Active, 0=InActive');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_informations');
    }
};
