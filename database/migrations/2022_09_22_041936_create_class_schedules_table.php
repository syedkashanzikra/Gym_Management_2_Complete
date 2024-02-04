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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day')->nullable();
            $table->dateTime('start_of_week')->nullable();
            $table->dateTime('date_at')->nullable();
            $table->string('start_at')->nullable();
            $table->string('end_at')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->integer('capacity')->unsigned()->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->mediumText('note')->nullable();
            $table->dateTime('sign_off_at')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('remote_link')->nullable();
            $table->string('remote_code')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('instructor_id')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('class_id')->references('id')->on('class_lists')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
};
