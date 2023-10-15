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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'ifood',
                'waze',
                'olx',
                'google_street_view',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->after('youtube', function (Blueprint $table) {
                $table->string('ifood')->nullable();
                $table->string('waze')->nullable();
                $table->string('olx')->nullable();
                $table->string('google_street_view')->nullable();
            });
        });
    }
};
