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
        Schema::create('cash_registers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Quién abrió la caja
        $table->decimal('opening_amount', 10, 2); // Monto inicial (cambio)
        $table->decimal('closing_amount', 10, 2)->nullable(); // Monto al cerrar
        $table->decimal('calculated_amount', 10, 2)->nullable(); // Lo que el sistema dice que debería haber
        $table->timestamp('closed_at')->nullable(); // Fecha cierre
        $table->enum('status', ['abierta', 'cerrada'])->default('abierta');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
