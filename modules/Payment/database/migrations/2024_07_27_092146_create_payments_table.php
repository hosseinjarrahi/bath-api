<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('paymentable');
            $table->unsignedBigInteger('amount'); // مقدار پرداخت
            $table->string('reference'); // شماره یکتا برای پرداخت
            $table->text('response')->nullable(); // کل دیتای پرداختی
            $table->enum('status', ['payed', 'notPayed'])->default('notPayed');
            $table->enum('gateway', ['internet', 'pos', 'cash', 'bank'])->default('pos');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('payments');
    }
}
