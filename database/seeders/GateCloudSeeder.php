<?php

namespace Database\Seeders;

use App\Models\GateCloud;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GateCloudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GateCloud::factory()->count(3)->create()->each(function ($gateCloud) {
            $token = $gateCloud->createToken('default');
            $gateCloud->token = $token->plainTextToken;
            $gateCloud->save();
        });
    }
}
