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
        Schema::table('users', function (Blueprint $table) {
            $table->string('member_id')->nullable()->after('password');
            $table->string('interest', 500)->nullable()->after('password');
            $table->integer('type')->unsigned()->nullable()->after('password');
            $table->string('best_time_contact')->nullable()->after('password');
            $table->mediumText('note')->nullable()->after('password');
            $table->string('source')->nullable()->after('password');
            $table->string('referal_code')->nullable()->after('password');
            $table->unsignedBigInteger('collect_id')->nullable()->after('password');
            $table->unsignedBigInteger('admin_id')->nullable()->after('password');
            $table->integer('assign')->unsigned()->nullable()->after('password');
            $table->dateTime('enq_date')->nullable()->after('password');
            $table->dateTime('status_change_at')->nullable()->after('password');
            $table->boolean('checked')->nullable()->default(false)->after('password');
            $table->boolean('request_parq')->nullable()->default(false)->after('password');
            $table->boolean('request_avatar')->nullable()->default(false);
            $table->dateTime('deactivates_at')->nullable()->after('email_verified_at');
            $table->dateTime('release_at')->nullable()->after('deactivates_at');
            $table->boolean('email_marketing')->nullable()->default(false)->after('release_at');
            $table->boolean('collect_tax')->nullable()->default(true)->after('email_marketing');

            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();
        });
    }
};
