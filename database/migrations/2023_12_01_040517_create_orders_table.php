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
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->text('note')->nullable();
            $table->boolean('collect_tax')->default(true);
            $table->{$this->jsonable()}('attributes')->nullable();
            $table->string('source')->nullable();
            $table->string('key')->nullable();
            $table->double('sub_total', 20, 2)->default(0.00);
            $table->double('tax_total', 20, 2)->default(0.00);
            $table->double('discount_total', 20, 2)->default(0.00);
            $table->double('grand_total', 20, 2)->default(0.00);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('shop_locations')->nullOnDelete();
            $table->foreign('billing_address_id')->references('id')->on('addresses')->nullOnDelete();
        });

        // set auto increment to 1000
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
