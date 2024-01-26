<?php

// database/seeders/ProductSaleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;

class ProductSaleSeeder extends Seeder
{
    public function run()
    {
        // Obter IDs de todos os produtos
        $productIds = Product::pluck('id');

        // Criar 10 vendas simuladas
        for ($i = 1; $i <= 10; $i++) {
            // Criar uma venda com um valor aleatório entre 1000 e 10000
            $sale = Sale::create([
                'amount' => rand(1000, 10000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Adicionar produtos à venda com quantidades aleatórias
            $products = $productIds->random(rand(1, count($productIds)));

            $sale->products()->attach(
                $products->mapWithKeys(function ($productId) {
                    return [
                        $productId => [
                            'amount' => rand(1, 5),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ];
                })
            );
        }
    }
}

