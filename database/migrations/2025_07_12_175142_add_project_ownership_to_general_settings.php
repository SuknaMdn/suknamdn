<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the project_ownership setting already exists
        $existingSetting = DB::table('settings')
            ->where('group', 'general')
            ->where('name', 'project_ownership')
            ->first();

        if (!$existingSetting) {
            // Create new row for project_ownership setting
            DB::table('settings')->insert([
                'group' => 'general',
                'name' => 'project_ownership',
                'locked' => 0,
                'payload' => json_encode(''), // Empty string as default value
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the project_ownership setting
        DB::table('settings')
            ->where('group', 'general')
            ->where('name', 'project_ownership')
            ->delete();
    }
};