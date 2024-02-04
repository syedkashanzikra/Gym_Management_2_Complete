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
        Schema::create('variant_options', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('option_id');
            $table->integer('position')->default(0);
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('option_id')->references('id')->on('options')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variant_options');
    }
};
