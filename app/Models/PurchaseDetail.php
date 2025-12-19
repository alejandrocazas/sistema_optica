<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $guarded = []; // Esto está bien, permite guardar todo sin definir uno por uno

    // Relación: Un detalle pertenece a un Producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // FALTABA ESTO: Relación inversa con la Compra (Padre)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
