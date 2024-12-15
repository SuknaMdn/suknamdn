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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('developer_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_type_id')->constrained('project_types')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('purpose')->default("sale"); // sale, rent, invest
            $table->string('video')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('state_id');
            $table->string('area_range_from')->nullable(); // Minimum area
            $table->string('area_range_to')->nullable(); // Maximum area
            $table->string('building_style')->nullable(); // Style of the building
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->json('images')->nullable();
            $table->text('threedurl')->nullable(); // Link for the 3D view
            $table->text('mediaPDF')->nullable(); // Link for the media PDF
            $table->string('AdLicense')->nullable(); // Advertising license for the project
            $table->string('qr_code')->nullable(); // QR code for the project
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
