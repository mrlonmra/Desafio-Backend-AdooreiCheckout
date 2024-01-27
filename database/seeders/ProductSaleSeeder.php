<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;

class ProductSaleSeeder extends Seeder
{
    /**
     * Seeder para adicionar ao banco 10 vendas simuladas com produtos associados.
     *
     * @return void
     */
    public function run()
    {
        // Obtém todos os IDs de produtos
        $productIds = Product::pluck('id');

        // Adiciona ao banco 10 vendas simuladas
        for ($i = 1; $i <= 10; $i++) {
            // Obtem alguns produtos aleatórios
            $products = $productIds->random(rand(1, count($productIds)));

            // Inicializa o valor total da venda
            $totalAmount = 0;

            // Cria um array para armazenar detalhes dos produtos
            $productDetails = [];

            // Calcula o valor total da venda e cria detalhes dos produtos
            foreach ($products as $productId) {
                $product = Product::find($productId);
                $quantity = rand(1, 5);

                // Calcula o valor do produto considerando a quantidade
                $productAmount = $product->price * $quantity;

                // Atualiza o valor total da venda
                $totalAmount += $productAmount;

                // Adiciona detalhes do produto ao array
                $productDetails[$productId] = [
                    'amount' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Realiza uma venda com o valor total
            $sale = Sale::create([
                'amount' => $totalAmount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Cadastra produtos à venda com quantidades calculadas
            $sale->products()->attach($productDetails);
        }
    }
}
