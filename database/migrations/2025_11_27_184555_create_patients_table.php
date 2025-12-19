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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            // Datos Personales
        $table->string('name'); // Nombre completo
        $table->string('ci')->nullable(); // Carnet de Identidad (Importante para facturación)
        $table->string('phone')->nullable(); // Para avisarles por WhatsApp
        $table->string('email')->nullable();
        $table->text('address')->nullable();
        
        // Datos Demográficos (Útiles para estadística médica)
        $table->date('birth_date')->nullable();
        $table->integer('age')->nullable(); // Opcional, se puede calcular con la fecha
        $table->string('occupation')->nullable(); // Ocupación (Importante para saber uso de la vista)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
