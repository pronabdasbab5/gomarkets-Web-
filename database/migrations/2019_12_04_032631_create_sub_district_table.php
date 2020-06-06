<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_district', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('district_id');
            $table->string('sub_district_name');
            $table->integer('status')->default(1)->comment('1 = Enabled, 2 = Disabled');
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
        Schema::dropIfExists('sub_district');
    }
}
