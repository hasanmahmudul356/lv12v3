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
        Schema::create('overdue_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('bill_id');
            $table->integer('penalty_due');
            $table->integer('due_amount');
            $table->integer('total_due');
            $table->date('due_date');
            $table->integer('status')->default(1)->comment('active=1,pending=0');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overdue_bills');
    }
};
