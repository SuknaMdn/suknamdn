<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectType;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProjectType::create(['name' => 'شقة', 'slug' => 'shqa', 'status' => 1]);
        ProjectType::create(['name' => 'فلل', 'slug' => 'fll', 'status' => 1]);
        ProjectType::create(['name' => 'ادوار', 'slug' => 'adwar', 'status' => 1]);
    }
}
