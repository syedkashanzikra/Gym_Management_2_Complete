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
        Schema::create('tax_lines', function (Blueprint $table) {
            $table->id();

            $table->string('taxable_type')->nullable();
            $table->unsignedBigInteger('taxable_id')->nullable();

            $table->string('label');
            $table->enum('type', ['normal', 'harmonized', 'compounded'])->nullable()->default('normal');
            $table->integer('rate')->nullable()->default(0);
            $table->decimal('amount', 10, 2)->nullable()->default(0);
        });

        // set auto increment to 10000
        DB::statement('ALTER TABLE tax_lines AUTO_INCREMENT = 10000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_lines');
    }
};
