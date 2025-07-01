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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_ownership')->nullable()->after('qr_code')->comment('حقوق ملكية المشروع');
            $table->string('ad_license_qr')->nullable()->after('project_ownership')->comment('QR للمعلومات عن ترخيص الإعلان');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['project_ownership', 'ad_license_qr']);
        });
    }
};
