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
        Schema::create('gate_clouds', function (Blueprint $table) {
            $table->id();
            $table->string('door_name')->nullable();
            $table->unsignedBigInteger('door_number')->default(1);
            $table->string('controller')->nullable();
            $table->string('controller_socket')->nullable();
            $table->string('arguments')->nullable();
            $table->string('reader_type')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('gate_clouds');
    }
};
