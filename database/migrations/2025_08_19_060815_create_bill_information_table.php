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
        Schema::create('bill_information', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_no');
            $table->date('billing_month');
            $table->integer('start_reading')->comment('(KWh)');
            $table->integer('end_reading')->comment('(KWh)');
            $table->integer('units_consumed')->comment('(KWh)');
            $table->integer('bill_amount');
            $table->integer('status')->comment('active=1,panding=0');
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
        Schema::dropIfExists('bill_information');
    }
};
