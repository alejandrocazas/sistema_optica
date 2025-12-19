<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'code', 'name', 'batch', 
        // 'stock', <--- BORRADO (Ya no guardamos el stock aquí)
        'price_buy', 'price_sell', 'image_path'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // --- NUEVO: Relación con Sucursales (Inventario) ---
    public function branches()
    {
        return $this->belongsToMany(Branch::class)
                    ->withPivot('stock') // Importante para leer el stock
                    ->withTimestamps();
    }

    // --- HELPER MÁGICO ---
    // Esta función te dará el stock exacto de la sucursal donde está el usuario logueado.
    // Úsala en tus vistas así: {{ $product->stock_actual }}
    // Accesor para obtener el stock de la sucursal del usuario logueado
    public function getStockActualAttribute()
    {
        // 1. Si no hay usuario logueado, retornamos 0 (por seguridad)
        if (!auth()->check()) return 0;

        // 2. Obtenemos la sucursal del usuario
        $branchId = auth()->user()->branch_id;

        // 3. Buscamos la relación en la tabla pivote para esa sucursal
        $branchProduct = $this->branches()->where('branch_id', $branchId)->first();

        // 4. Si existe registro retornamos el stock, si no, 0
        return $branchProduct ? $branchProduct->pivot->stock : 0;
    }
}