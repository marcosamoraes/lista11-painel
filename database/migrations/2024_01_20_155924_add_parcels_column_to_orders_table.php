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
        Schema::table('orders', function (Blueprint $table) {
            $table->after('value', function ($table) {
                $table->integer('parcels')->default(1);
                $table->json('parcels_data')->nullable();
            });

            $table->dropColumn('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['parcels', 'parcels_data']);
            $table->string('payment_method')->nullable();
        });
    }
};
