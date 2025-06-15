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
        Schema::create('nafath_national_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('nafath_request_id');
            $table->string('street_name')->nullable();
            $table->string('city')->nullable();
            $table->string('additional_number')->nullable();
            $table->string('district')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('building_number')->nullable();
            $table->string('post_code')->nullable();
            $table->string('location_coordinates')->nullable();
            $table->boolean('is_primary_address')->default(false);
            $table->string('city_id')->nullable();
            $table->string('region_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('region_name_l2')->nullable();
            $table->string('city_l2')->nullable();
            $table->string('street_l2')->nullable();
            $table->string('district_l2')->nullable();
            $table->string('region_name')->nullable();
            $table->timestamps();
            
            $table->index(['nafath_request_id']);
            $table->index(['city', 'district']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nafath_national_addresses');
    }
};
