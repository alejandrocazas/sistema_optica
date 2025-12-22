<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Traits\Multitenantable;

class Sale extends Model
{
    use Multitenantable;
    use SoftDeletes;
    protected $guarded = [];
    protected $casts = [
    'delivery_date' => 'datetime',
];

    // --- ESTA ES LA FUNCIÓN QUE TE FALTA ---
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
    // ---------------------------------------

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function branch()
    {
     return $this->belongsTo(Branch::class);
    }
}
