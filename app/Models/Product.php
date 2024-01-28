<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'description'];

    // Relacionamento para vendas (um produto pode estar em várias vendas)
    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('amount');
    }
}