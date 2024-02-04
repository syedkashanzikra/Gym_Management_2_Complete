<?php

use Coderstm\Traits\Helpers;
use Illuminate\Support\Facades\DB;
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
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();

            $table->string('itemable_type')->nullable();
            $table->unsignedBigInteger('itemable_id')->nullable();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('title')->nullable();
            $table->string('variant_title')->nullable();
            $table->string('sku')->nullable();
            $table->boolean('taxable')->default(true);
            $table->boolean('is_custom')->nullable()->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('accepted')->nullable();
            $table->unsignedBigInteger('rejected')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->{$this->jsonable()}('attributes')->nullable();
            $table->boolean('is_product_deleted')->nullable();
            $table->boolean('is_variant_deleted')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('variant_id')->references('id')->on('variants')->nullOnDelete();
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE line_items AUTO_INCREMENT = 10000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_items');
    }
};
