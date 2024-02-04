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
        Schema::create('option_values', function (Blueprint $table) {
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('value_id');

            $table->foreign('option_id')->references('id')->on('options')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('value_id')->references('id')->on('attribute_values')->cascadeOnUpdate()->cascadeOnDelete();

            $table->primary(['option_id', 'value_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_values');
    }
};
