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
       Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->string('receipt_number')->unique(); // Nro Comprobante
        $table->foreignId('user_id')->constrained(); // Vendedor
        $table->foreignId('patient_id')->nullable()->constrained(); // Cliente
        
        $table->decimal('total', 10, 2);
        $table->decimal('paid_amount', 10, 2)->default(0); // Lo que pagó hasta ahora
        $table->decimal('balance', 10, 2)->default(0); // Saldo pendiente
        
        // Estados del Trabajo (Flujo de Óptica)
        $table->enum('status', ['pendiente', 'laboratorio', 'listo', 'entregado', 'cancelado'])->default('laboratorio');
        
        // Estado del Pago
        $table->enum('payment_status', ['pendiente', 'parcial', 'pagado'])->default('pendiente');
        
        $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
