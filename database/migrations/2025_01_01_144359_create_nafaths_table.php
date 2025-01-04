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
        Schema::create('nafaths', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();

            $table->string('transaction_id')->unique();
            $table->string('national_id');
            $table->string('id_type')->default('NATIONAL_ID');
            $table->string('full_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
            $table->string('nationality')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'REJECTED', 'EXPIRED'])->default('PENDING');
            $table->json('response_data')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['national_id', 'status']);
            $table->index('transaction_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nafaths');
    }
};
