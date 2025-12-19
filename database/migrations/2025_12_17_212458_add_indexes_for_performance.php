<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Índices para PACIENTES (Búsquedas rápidas)
        Schema::table('patients', function (Blueprint $table) {
            // Verificamos para no duplicar si corres esto dos veces
            if (!collect(DB::select("SHOW INDEXES FROM patients"))->pluck('Key_name')->contains('patients_name_index')) {
                $table->index('name'); // Búsqueda por nombre
                $table->index('ci');   // Búsqueda por carnet
                $table->index('phone'); // Búsqueda por teléfono
            }
        });

        // 2. Índices para PRODUCTOS
        Schema::table('products', function (Blueprint $table) {
            if (!collect(DB::select("SHOW INDEXES FROM products"))->pluck('Key_name')->contains('products_code_index')) {
                $table->index('code'); // Escáner de código de barras
                $table->index('name'); // Buscador manual
            }
        });

        // 3. Índices para VENTAS (Reportes por fecha)
        Schema::table('sales', function (Blueprint $table) {
            $table->index('created_at'); // Para reportes "Ventas de Hoy/Mes"
            $table->index('status');     // Para filtrar "Pendientes/Entregados"
        });
    }

    public function down(): void
    {
        // Borrar índices si revertimos
        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['name', 'ci', 'phone']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['code', 'name']);
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['created_at', 'status']);
        });
    }
};