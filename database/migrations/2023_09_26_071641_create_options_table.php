<?php

use Coderstm\Traits\Helpers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use Helpers;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_variant')->default(false);
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->{$this->jsonable()}('custom_values')->nullable();
            $table->string('values')->nullable();

            $table->unsignedBigInteger('product_id');
            $table->unique(['product_id', 'slug']);

            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
};
