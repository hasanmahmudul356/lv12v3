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
            $table->string('meter_no');
            $table->string('billing_month', 7)->comment('Format: YYYY-MM');
            $table->integer('start_reading')->comment('(KWh)');
            $table->integer('end_reading')->comment('(KWh)');
            $table->integer('units_consumed')->comment('(KWh)');
            $table->decimal('bill_amount',10,2);
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
