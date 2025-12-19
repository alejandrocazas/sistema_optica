<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Relación: Un gasto pertenece a una Caja
    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}
