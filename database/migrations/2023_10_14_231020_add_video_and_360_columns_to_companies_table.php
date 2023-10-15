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
            $table->after('google_my_business', function ($table) {
                $table->string('video_link')->nullable();
                $table->string('photo_360_link')->nullable();
                $table->text('photo_360_code')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('video_link');
            $table->dropColumn('photo_360_link');
            $table->dropColumn('photo_360_code');
        });
    }
};
