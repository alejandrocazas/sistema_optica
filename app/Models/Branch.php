<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address', 'phone'];

    // Relación: Una sucursal tiene muchos productos (con su stock)
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('stock')
                    ->withTimestamps();
    }
    
    // Relación: Una sucursal tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}