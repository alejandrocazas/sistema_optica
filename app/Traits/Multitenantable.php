<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        // 1. AL LEER DATOS: Filtrar automáticamente por la sucursal del usuario
        if (auth()->check()) {
            static::addGlobalScope('branch_id', function (Builder $builder) {
                // Si es admin, quizás quieras que vea todo. Si no, quita este if del admin.
                if (auth()->user()->role !== 'admin') { 
                    $builder->where('branch_id', auth()->user()->branch_id);
                }
            });
        }

        // 2. AL CREAR DATOS: Asignar automáticamente la sucursal del usuario
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->branch_id = auth()->user()->branch_id;
            }
        });
    }
}