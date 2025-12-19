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
        Schema::table('sales', function (Blueprint $table) {
        $table->dateTime('delivery_date')->nullable()->after('payment_status');
        $table->text('observations')->nullable()->after('delivery_date');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn(['delivery_date', 'observations']);
    });
    }
};
