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
        // 1. Crear tabla de Sucursales (Solo si no existe)
        if (!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('name'); 
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->timestamps();
            });
        }

        // 2. Agregar la relación a las tablas existentes
        // He quitado 'cash_movements' y agregado 'payments' que SÍ vi en tu código anterior.
        // Puedes agregar más tablas a esta lista si lo necesitas.
        $tables = ['users', 'patients', 'sales', 'prescriptions', 'payments', 'purchases'];

        foreach ($tables as $tableName) {
            // Verificamos si la tabla existe antes de intentar modificarla
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Verificamos si la columna YA existe para no dar error al re-ejecutar
                    if (!Schema::hasColumn($tableName, 'branch_id')) {
                        $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['users', 'patients', 'sales', 'prescriptions', 'payments', 'purchases'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'branch_id')) {
                        // Nota: A veces borrar foráneas requiere saber el nombre exacto, 
                        // aquí intentamos una eliminación genérica segura.
                        $table->dropForeign([$tableName . '_branch_id_foreign']); // Convención de Laravel
                        $table->dropColumn('branch_id');
                    }
                });
            }
        }
        Schema::dropIfExists('branches');
    }
};
