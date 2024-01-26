<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['amount'];

    // Relacionamento para produtos (uma venda pode ter vários produtos)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('amount');
    }
}