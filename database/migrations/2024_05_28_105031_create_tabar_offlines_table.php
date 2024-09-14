<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabarOfflinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabar_offlines', function (Blueprint $table) {
            $table->id();
            $table->string('Invoice_number')->nullable();
            $table->string('ReceiptNumber')->nullable();
            $table->string('AbandonmentDate')->nullable();
            $table->string('InvoiceDate')->nullable();
            $table->string('PaymentDate')->nullable();
            $table->BigInteger('ParkingCost')->nullable();
            $table->string('PayRequestTraceNo')->nullable();
            $table->string('SystemTraceNumber')->nullable();
            $table->string('GoodsOwnerNationalID')->nullable();
            $table->string('GoodsOwnerName')->nullable();
            $table->string('GoodsOwnerPostalCode')->nullable();
            $table->string('GoodsOwnerEconommicCode')->nullable();
            $table->integer('20f')->nullable();
            $table->integer('40f')->nullable();
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
        Schema::dropIfExists('tabar_offlines');
    }
}
