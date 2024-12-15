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
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('case')->default(0);

            // Building and Unit Information
            $table->string('building_number')->nullable(); // رقم المبنى
            $table->string('unit_number')->nullable(); // رقم الوحدة
            $table->string('unit_type')->nullable(); // نوع الوحدة
            $table->string('floor')->nullable(); // الدور

            // Areas
            $table->decimal('total_area', 10, 2)->nullable(); // المساحة الإجمالية
            $table->decimal('internal_area', 10, 2)->nullable(); // المساحة الداخلية
            $table->decimal('external_area', 10, 2)->nullable(); // المساحة الخارجية

            // Rooms
            $table->integer('total_rooms')->nullable(); // عدد الغرف
            $table->integer('bedrooms')->nullable(); // غرف النوم
            $table->integer('living_rooms')->nullable(); // المعيشة
            $table->integer('bathrooms')->nullable(); // دورات المياه
            $table->integer('kitchens')->nullable(); // المطبخ

            // Sales Information
            $table->string('sale_type')->default('direct'); // نوع البيع مباشر
            $table->decimal('unit_price', 12, 2)->nullable(); // سعر الوحدة
            $table->decimal('property_tax', 12, 2)->nullable(); // الضريبة العقارية
            $table->decimal('total_amount', 12, 2)->nullable(); // المبلغ الإجمالي
            $table->string('user_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
