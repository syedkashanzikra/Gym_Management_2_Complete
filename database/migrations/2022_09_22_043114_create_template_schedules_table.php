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
        Schema::create('template_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day')->nullable();
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('instructor_id')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('class_id')->references('id')->on('class_lists')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_schedules');
    }
};
