<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('count')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
        Schema::create('passenger_form_service_option', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('passenger_form_id')->default(0);
            $table->unsignedBigInteger('service_option_id')->default(0);
            $table->unsignedBigInteger('count')->default(0);
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
        Schema::dropIfExists('service_options');
        Schema::dropIfExists('service_option_passenger');
    }
}
