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
        Schema::create('initial_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_order_id')->constrained()->onDelete('cascade'); // يرتبط بالحجز
            $table->decimal('amount', 10, 2);  // مبلغ الدفع الأولي
            $table->string('payment_method');  // طريقة الدفع
            $table->string('status')->default('pending');  // حالة الدفع: pending, completed, failed
            $table->string('transaction_id')->nullable();  // معرف المعاملة من بوابة الدفع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_payments');
    }
};
