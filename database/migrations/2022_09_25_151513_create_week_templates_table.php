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
        Schema::create('week_templates', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_of_week')->nullable()->unique();
            $table->unsignedBigInteger('template_id')->nullable();

            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_templates');
    }
};
