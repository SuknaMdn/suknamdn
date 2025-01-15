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
        Schema::table('model_has_roles', function (Blueprint $table) {
            // حذف العمود القديم إذا كان يحتوي على بيانات متوافقة
            $table->dropColumn('model_id');
            // إضافة العمود الجديد كـ UUID
            $table->uuid('model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // العودة إلى النوع القديم
            $table->dropColumn('model_id');
            $table->unsignedBigInteger('model_id');
        });
    }
};
