<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassengerFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passenger_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passenger_id');
            $table->dateTime('checkout_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedBigInteger('damage')->default(0);
            $table->unsignedBigInteger('delay_price')->default(0);
            $table->unsignedBigInteger('options_price')->default(0);
            $table->unsignedBigInteger('service_price')->default(0);
            $table->timestamps();
        });

        Schema::create('passenger_form_service_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passenger_form_id');
            $table->unsignedBigInteger('service_item_id');
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('passenger_forms');
    }
}
