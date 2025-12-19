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
        $table->softDeletes(); // Crea la columna deleted_at
        $table->text('deletion_reason')->nullable()->after('updated_at'); // La justificación
        $table->unsignedBigInteger('deleted_by')->nullable()->after('deletion_reason'); // Quién lo borró
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
        $table->dropSoftDeletes();
        $table->dropColumn(['deletion_reason', 'deleted_by']);
    });
    }
};
