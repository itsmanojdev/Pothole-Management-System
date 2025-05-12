<?php

namespace Database\Seeders;

use App\Models\Pothole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PotholeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pothole::factory(5)->create();
        Pothole::factory(10)->assigned()->create();
    }
}
