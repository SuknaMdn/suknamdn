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
        Schema::table('users', function (Blueprint $table) {
            $table->string('family_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('nationality')->nullable();
            $table->string('gender')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('social_status')->nullable();
            $table->string('national_address')->nullable();
            $table->string('iqama_number')->nullable();
            $table->string('birth_country')->nullable();

            $table->string('city')->nullable();
            $table->string('region_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('street_name')->nullable();

            // locationCoordinates 39.18938198 21.52049839
            $table->string('location_coordinates')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
