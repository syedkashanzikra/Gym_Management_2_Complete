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
        if (Schema::hasColumn('bookings', 'attendence')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->renameColumn('attendence', 'attendance');
            });
        }
    }
};
