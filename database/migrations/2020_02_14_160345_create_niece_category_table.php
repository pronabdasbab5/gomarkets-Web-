<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNieceCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('niece_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sub_category_id')->nullable();
            $table->string('niece_cate_name')->nullable();
            $table->string('banner')->nullable();
            $table->integer('status')->default(1)->comment('1 = Enable, 2 = Disabled');
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
        Schema::dropIfExists('niece_category');
    }
}
