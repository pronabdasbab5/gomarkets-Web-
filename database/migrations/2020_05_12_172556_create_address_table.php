<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->string('address', 191)->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('sub_district_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('pin_code')->nullable();
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
        Schema::dropIfExists('address');
    }
}
