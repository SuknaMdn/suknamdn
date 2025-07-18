<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nafaths', function (Blueprint $table) {
            $table->string('request_id')->unique();
            $table->string('random_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nafaths', function (Blueprint $table) {
            $table->dropColumn([
                'request_id',
                'random_number',
            ]);
        });
    }
};
