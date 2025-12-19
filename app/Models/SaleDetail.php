<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $guarded = [];

    // Relación con el producto (Para saber el nombre y precio)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}