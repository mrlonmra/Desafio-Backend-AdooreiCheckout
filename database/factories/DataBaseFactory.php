<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

class DatabaseFactory extends BaseFactory
{
    /**
     * Define your model's default state.
     *
     * @return array
     */
    protected $model = Sale::class;

    public function definition()
    {
        \App\Models\Product::factory();

        Sale::factory();

        return [
            
            // Adicione outras Factorys, se necessário
        ];
    }
}
