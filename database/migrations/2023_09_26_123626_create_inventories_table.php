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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->integer('available')->default(0);
            $table->integer('incoming')->default(0);
            $table->boolean('active')->default(true);
            $table->boolean('tracking')->default(true);

            $table->unsignedBigInteger('variant_id');
            $table->unsignedBigInteger('location_id');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('shop_locations')->cascadeOnDelete()->cascadeOnUpdate();
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE inventories AUTO_INCREMENT = 10000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
