<?php

// database/seeders/ProductsTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Celular 1',
            'price' => 1800,
            'description' => 'Lorem ipsum 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'name' => 'Celular 2',
            'price' => 3200,
            'description' => 'Lorem ipsum 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'name' => 'Celular 3',
            'price' => 9800,
            'description' => 'Lorem ipsum 3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crie mais 7 aparelhos com valores aleatorios
        for ($i = 4; $i <= 10; $i++) {
            Product::create([
                'name' => "Celular $i",
                'price' => rand(1000, 5000),
                'description' => "Lorem ipsum $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
