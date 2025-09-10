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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_no');
            $table->integer('receipt_no');
            $table->date('bill_month');
            $table->decimal('bill_amount');
            $table->decimal('payment_amount');
            $table->date('payment_date');
            $table->string('payment_method')->comment('cash,bkash,nagad,rocket,bank');
            $table->string('payment_status')->comment('unpaid','partial','paid');
            $table->integer('status')->default(1)->comment('active=1,panding=0');
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
        Schema::dropIfExists('bill_payments');
    }
};
