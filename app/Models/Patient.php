<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Multitenantable;

class Patient extends Model
{
    use Multitenantable;
    use HasFactory;

    protected $fillable = ['name', 'ci', 'phone', 'email', 'address', 'birth_date', 'age', 'occupation','branch_id'];

    // --- AGREGA ESTO ---
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
