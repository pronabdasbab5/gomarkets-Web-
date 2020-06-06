<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('district_id')->nullable();
            $table->string('product_name_english', 191)->nullable();
            $table->string('product_name_assamese', 191)->collation('utf8_unicode_ci')->nullable();
            $table->integer('niece_category_id')->nullable();
            $table->string('slug', 191)->nullable();
            $table->integer('brand_id')->nullable();
            $table->text('desc')->nullable();
            $table->string('banner', 191)->nullable();
            $table->integer('status')->default(1)->comment("1 = Enable, 2 = Disabled");
            $table->integer('make_discount_product')->default(2)->comment("1 = Yes, 2 = No");
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
        Schema::dropIfExists('product');
    }
}
