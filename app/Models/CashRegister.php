<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Multitenantable;

class CashRegister extends Model
{
    use Multitenantable;
    // Opción A: Desbloquear todo (Más fácil)
    protected $guarded = [];

    // Opción B: Especificar campos (Más estricto)
    // protected $fillable = ['user_id', 'opening_amount', 'closing_amount', 'opened_at', 'closed_at', 'status'];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
