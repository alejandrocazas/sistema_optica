<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class Purchase extends Model
{
    use HasFactory, Multitenantable;

    // Está perfecto usar guarded = [] para permitir guardar branch_id, total, etc. sin restricciones.
    protected $guarded = []; 

    // AGREGAR ESTO: Para que Laravel entienda que 'purchase_date' es una fecha
    protected $casts = [
        'purchase_date' => 'date',
    ];

    // Relación: Una compra es realizada por un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // AGREGAR ESTO: Relación con la Sucursal (Donde ingresó la mercadería)
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relación: Una compra tiene muchos detalles
    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}