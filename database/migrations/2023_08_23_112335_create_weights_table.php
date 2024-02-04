<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('weights', function (Blueprint $table) {
            $table->id();

            $table->string('weightable_type')->nullable();
            $table->unsignedBigInteger('weightable_id')->nullable();

            $table->string('unit')->default('kg');
            $table->decimal('value', 10, 3)->default(0.00);
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE weights AUTO_INCREMENT = 10000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weights');
    }
};
