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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id(); // Este será tu número de receta
        $table->foreignId('patient_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained(); // El optometrista que atendió
        
        // Datos Clínicos
        $table->text('anamnesis')->nullable(); // Motivo de consulta
        $table->text('antecedentes')->nullable();
        
        // VISION LEJOS (OD = Ojo Derecho, OI = Ojo Izquierdo)
        $table->decimal('od_esfera', 5, 2)->nullable();
        $table->decimal('od_cilindro', 5, 2)->nullable();
        $table->integer('od_eje')->nullable();
        
        $table->decimal('oi_esfera', 5, 2)->nullable();
        $table->decimal('oi_cilindro', 5, 2)->nullable();
        $table->integer('oi_eje')->nullable();
        
        // ADICIÓN (Para cerca)
        $table->decimal('add_od', 5, 2)->nullable();
        $table->decimal('add_oi', 5, 2)->nullable();
        
        // VISION CERCA (Calculada o medida)
        $table->decimal('cerca_od_esfera', 5, 2)->nullable();
        $table->decimal('cerca_od_cilindro', 5, 2)->nullable();
        $table->integer('cerca_od_eje')->nullable();
        
        $table->decimal('cerca_oi_esfera', 5, 2)->nullable();
        $table->decimal('cerca_oi_cilindro', 5, 2)->nullable();
        $table->integer('cerca_oi_eje')->nullable();
        
        // Otros
        $table->string('dip')->nullable(); // Distancia Interpupilar
        $table->string('diagnostico')->nullable(); // Miopía, Astigmatismo, etc.
        $table->text('observaciones')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
