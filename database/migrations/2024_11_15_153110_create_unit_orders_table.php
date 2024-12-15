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
        Schema::create('unit_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->uuid('user_id')->nullable();
            $table->string('payment_plan'); // cash, bank_transfer
            $table->string('payment_method'); // cash, installments
            $table->string('payment_status'); // pending, paid, canceled
            $table->boolean('tax_exemption_status');
            $table->enum('status', ['pending', 'processing','confirmed' ,'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_orders');
    }
};
