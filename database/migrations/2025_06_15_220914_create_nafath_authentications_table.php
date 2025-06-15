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
        Schema::create('nafath_authentications', function (Blueprint $table) {
            $table->id();
            $table->string('nafath_request_id');
            $table->string('nafath_trans_id');
            $table->string('user_type'); // national_id, iqama, visa
            $table->string('authentication_status');
            $table->timestamp('authenticated_at')->nullable();
            
            // Common fields
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('grandfather_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('english_first_name')->nullable();
            $table->string('english_second_name')->nullable();
            $table->string('english_third_name')->nullable();
            $table->string('english_last_name')->nullable();
            $table->string('gender', 1)->nullable(); // M/F
            $table->string('nationality')->nullable();
            $table->string('nationality_code')->nullable();
            $table->string('nationality_desc')->nullable();
            $table->date('date_of_birth_g')->nullable(); // Gregorian
            $table->string('date_of_birth_h')->nullable(); // Hijri
            
            // National ID specific fields
            $table->string('national_id')->nullable();
            $table->string('id_version_number')->nullable();
            $table->string('id_issue_place')->nullable();
            $table->date('id_issue_date_g')->nullable();
            $table->string('id_issue_date_h')->nullable();
            $table->date('id_expiry_date_g')->nullable();
            $table->string('id_expiry_date_h')->nullable();
            
            // Iqama specific fields
            $table->string('iqama_number')->nullable();
            $table->string('iqama_version_number')->nullable();
            $table->date('iqama_expiry_date_g')->nullable();
            $table->string('iqama_expiry_date_h')->nullable();
            $table->date('iqama_issue_date_g')->nullable();
            $table->string('iqama_issue_date_h')->nullable();
            $table->string('iqama_issue_place_code')->nullable();
            $table->string('iqama_issue_place_desc')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('legal_status')->nullable();
            
            // Additional fields
            $table->string('social_status_code')->nullable();
            $table->string('social_status_desc')->nullable();
            $table->string('occupation_code')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('passport_number')->nullable();
            $table->boolean('is_minor')->default(false);
            $table->integer('total_dependents')->nullable();
            
            // Store raw JWT data
            $table->json('raw_data')->nullable();
            
            $table->timestamps();
            
            $table->index(['nafath_request_id']);
            $table->index(['national_id', 'iqama_number']);
            $table->index(['authentication_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nafath_authentications');
    }
};
