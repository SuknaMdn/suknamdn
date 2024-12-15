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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();


            // Amount and currency
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');

            // Payment status and method
            $table->enum('status', ['initiated', 'paid', 'failed', 'pending', 'canceled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();

            // Polymorphic relationship
            $table->nullableMorphs('payable');

            // Payment context
            $table->string('payment_type')->nullable(); // e.g., subscription, property_deposit
            $table->string('invoice_number')->unique()->nullable();
            $table->text('description')->nullable();

            // User relationship
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('redirect_url')->nullable();

            $table->boolean('refunded')->default(false);


            // Metadata
            $table->json('metadata')->nullable();

            // Dates
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
