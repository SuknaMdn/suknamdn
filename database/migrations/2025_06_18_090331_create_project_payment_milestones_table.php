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
        Schema::create('project_payment_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name'); // مثال: "الدفعة الأولى"
            $table->unsignedInteger('percentage'); // النسبة المئوية للمبلغ
            // شرط الاستحقاق: يغذي جملة "الدفعة القادمة: عند نسبة تقدم 20%"
            $table->text('completion_milestone');
            $table->unsignedInteger('order')->default(0); // للترتيب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_payment_milestones');
    }
};
