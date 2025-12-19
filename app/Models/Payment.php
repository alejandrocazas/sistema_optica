<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Multitenantable;

class Payment extends Model
{
    use Multitenantable;
    protected $guarded = [];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
