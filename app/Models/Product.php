<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'description'];

    // Relacionamento para vendas (um produto pode estar em várias vendas)
    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('amount');
    }
}