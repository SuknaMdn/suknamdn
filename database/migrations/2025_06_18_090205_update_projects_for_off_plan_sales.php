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
            // المفتاح الرئيسي لتفعيل الميزة: زر التفعيل في لوحة التحكم
            $table->boolean('enables_payment_plan')->default(false)->after('purpose');

            // حقل نسبة الإنجاز: يغذي بطاقة "%48 نسبة تقدم بناء المشروع"
            $table->unsignedInteger('completion_percentage')->default(0)->after('longitude');
            
            // الحقول التالية تغذي شاشة "مراجعة الطلب" التي تحتوي على معلومات التصميم والتنفيذ
            $table->string('architect_office_name')->nullable()->after('completion_percentage'); // مكتب التصميم المعماري
            $table->string('construction_supervisor_office')->nullable()->after('architect_office_name'); // الاستشاري المشرف على البناء
            $table->string('main_contractor')->nullable()->after('construction_supervisor_office'); // المقاول الرئيسي
            $table->text('istisna_contract_details')->nullable()->after('main_contractor'); // تفاصيل عقد الاستصناع
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'enables_payment_plan',
                'completion_percentage',
                'architect_office_name',
                'construction_supervisor_office',
                'main_contractor',
                'istisna_contract_details',
            ]);
        });
    }
};
