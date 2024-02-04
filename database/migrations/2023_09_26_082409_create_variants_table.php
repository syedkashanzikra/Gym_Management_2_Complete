<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();

            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('compare_at_price', 10, 2)->default(0.00);
            $table->decimal('cost_per_item', 10, 2)->default(0.00);
            $table->boolean('taxable')->default(true);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('out_of_stock_track_inventory')->default(false);
            $table->string('sku')->nullable()->unique();
            $table->decimal('weight', 10, 3)->default(0.00);
            $table->string('weight_unit')->nullable()->default('kg');
            $table->string('origin')->nullable();
            $table->string('harmonized_system_code')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_default')->nullable()->default(false);
            $table->unsignedBigInteger('media_id')->nullable();

            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('media_id')->references('id')->on('files')->nullOnDelete();;
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE variants AUTO_INCREMENT = 1000000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants');
    }
};
