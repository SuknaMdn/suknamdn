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
        Schema::create('order_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_payment_milestone_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2); // المبلغ الفعلي للقسط
            // الحالة: تستخدم لتحديد المدفوع من غير المدفوع ولحساب التقدم
            $table->enum('status', ['pending', 'due', 'paid', 'overdue'])->default('pending');
            $table->string('receipt_url')->nullable(); // يغذي زر "عرض السند" في شاشة الدفعات
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_installments');
    }
};
