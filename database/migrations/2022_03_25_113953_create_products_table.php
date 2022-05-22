<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->unsignedBigInteger('family_id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->double('price')->default(0);
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();

            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('family_id')->references('id')->on('families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::disableForeignKeyConstraints('products_family_id_foreign');
        Schema::dropIfExists('products');
    }
};
