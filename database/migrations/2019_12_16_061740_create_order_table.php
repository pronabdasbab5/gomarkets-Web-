<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_request_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('delivery_charge')->nullable();
            $table->string('grand_total')->nullable();
            $table->integer('payment_status')->nullable()->comment('1 = Failed, 2 = Paid, 3 = Cash');
            $table->integer('order_status')->default(1)->comment('1 = New Order, 2 = Packed, 3 = Picked, 4 = Delivered, 5 = Canceled');
            $table->string('address', 191)->nullable();
            $table->string('area', 191)->nullable();
            $table->string('sub_district', 191)->nullable();
            $table->string('district', 191)->nullable();
            $table->string('state', 191)->nullable();
            $table->string('pin_code', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('mobile_no', 191)->nullable();
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
        Schema::dropIfExists('order');
    }
}
