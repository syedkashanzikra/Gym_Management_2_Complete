<?php

use Coderstm\Models\AppSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        AppSetting::create('banners', [
            [
                'tag_line' => 'The Best Fitness Studio.',
                'title_line_1' => 'Best Top-Notch Gym',
                'title_line_2' => 'Health Fitness Services',
                'button' => 'OUR OFFERS',
                'link' => 'javascript:void(0);',
                'options' => [],
                'thumbnail' => null,
                'is_active' => true,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
