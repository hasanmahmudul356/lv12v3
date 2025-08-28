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
        Schema::create('notication_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('frequency');
            $table->string('reminder_days')->comment('Before Due');
            $table->time('time');
            $table->string('sender_email');
            $table->string('sender_phone');
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
        Schema::dropIfExists('notication_settings');
    }
};
