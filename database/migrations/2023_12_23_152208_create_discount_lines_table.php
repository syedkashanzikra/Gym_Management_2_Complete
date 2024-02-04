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
        Schema::create('discount_lines', function (Blueprint $table) {
            $table->id();

            $table->string('discountable_type')->nullable();
            $table->unsignedBigInteger('discountable_id')->nullable();

            $table->enum('type', ['percentage', 'fixed_amount'])->nullable()->default('fixed_amount');
            $table->decimal('value', 20, 2)->default(0.00);
            $table->string('description')->nullable();
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE discount_lines AUTO_INCREMENT = 10000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_lines');
    }
};
