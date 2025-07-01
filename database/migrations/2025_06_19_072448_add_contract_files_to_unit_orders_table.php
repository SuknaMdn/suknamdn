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
        Schema::table('unit_orders', function (Blueprint $table) {
            $table->string('istisna_contract_url')->nullable()->after('note');
            $table->string('price_quote_url')->nullable()->after('istisna_contract_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_orders', function (Blueprint $table) {
            //
        });
    }
};
