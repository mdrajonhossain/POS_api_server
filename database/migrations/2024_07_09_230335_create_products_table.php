<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->string('name')->unique();
            $table->string('product_code')->unique();
            $table->unsignedInteger('brand_id');
            $table->decimal('purchase_price', 8, 2);
            $table->decimal('selling_price', 8, 2);
            $table->tinyInteger('discount')->default(0);            
            $table->boolean('stock')->default(true);
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
        Schema::dropIfExists('products');
    }
}
