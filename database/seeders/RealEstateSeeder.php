<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RealEstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ProjectType Seeder
        DB::table('project_types')->insert([
            ['name' => 'فيلا', 'slug' => Str::slug('فيلا'), 'status' => 1],
            ['name' => 'شقة', 'slug' => Str::slug('شقة'), 'status' => 1],
            ['name' => 'مكتب', 'slug' => Str::slug('مكتب'), 'status' => 1],
        ]);

        // AdditionalFeature Seeder
        DB::table('additional_features')->insert([
            ['title' => 'مسبح', 'description' => 'مسبح خارجي فاخر', 'icon' => 'pool-icon'],
            ['title' => 'حديقة', 'description' => 'حديقة كبيرة وجميلة', 'icon' => 'garden-icon'],
            ['title' => 'موقف سيارات', 'description' => 'موقف سيارات خاص', 'icon' => 'parking-icon'],
        ]);

        // AfterSalesService Seeder
        DB::table('after_sales_services')->insert([
            ['title' => 'صيانة', 'description' => 'خدمة صيانة شاملة بعد الشراء', 'icon' => 'maintenance-icon'],
            ['title' => 'خدمة العملاء', 'description' => 'دعم مستمر بعد البيع', 'icon' => 'customer-service-icon'],
            ['title' => 'ضمان', 'description' => 'ضمان على العقار لمدة سنة', 'icon' => 'warranty-icon'],
        ]);
    }
}
