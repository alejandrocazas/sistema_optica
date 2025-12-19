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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained(); // Relación con categoría
        
        $table->string('code')->unique(); // Código de barras o interno
        $table->string('name'); // Descripción / Nombre
        $table->string('batch')->nullable(); // Lote (Importante para líquidos)
        
        $table->integer('stock')->default(0);
        $table->decimal('price_buy', 10, 2); // Precio Compra
        $table->decimal('price_sell', 10, 2); // Precio Venta
        
        $table->string('image_path')->nullable(); // Ruta de la foto
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
