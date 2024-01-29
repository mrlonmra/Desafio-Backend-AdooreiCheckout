<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    protected $fillable = ['amount'];

    // Relacionamento para produtos (uma venda pode ter vários produtos)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount');
    }
}
