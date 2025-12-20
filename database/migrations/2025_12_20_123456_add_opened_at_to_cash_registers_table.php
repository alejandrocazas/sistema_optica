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
        Schema::table('cash_registers', function (Blueprint $table) {
            // Agregamos la columna 'opened_at' que falta
            // La ponemos nullable por si acaso, y después de 'opening_amount' para mantener orden
            $table->timestamp('opened_at')->nullable()->after('opening_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_registers', function (Blueprint $table) {
            $table->dropColumn('opened_at');
        });
    }
};