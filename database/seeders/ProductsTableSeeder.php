<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Seeder para popular a tabela Products com dados simulados.
     *
     * @return void
     */
    public function run()
    {
        // Cadastra no banco de dadostrês produtos iniciais.
        $this->criarProduto('Celular 1', 1800, 'Lorem ipsum 1');
        $this->criarProduto('Celular 2', 1400, 'Lorem ipsum 2');
        $this->criarProduto('Celular 3', 2200, 'Lorem ipsum 3');

        // For para criar mais 7 produtos com valores aleatorios.
        for ($i = 4; $i <= 10; $i++) {
            $this->criarProdutoAleatorio($i);
        }
    }

    /**
     * Função para criar um novo produto com valor especificado.
     *
     * @param string $nome
     * @param float $preco
     * @param string $descricao
     * @return void
     */
    private function criarProduto($nome, $preco, $descricao)
    {
        Product::create([
            'name' => $nome,
            'price' => $preco,
            'description' => $descricao,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Função para cria um novo produto com valores aleatórios.
     *
     * @param int $indice
     * @return void
     */
    private function criarProdutoAleatorio($indice)
    {
        $nome = "Celular $indice";
        $preco = rand(800, 3500);
        $descricao = "Lorem ipsum $indice";

        $this->criarProduto($nome, $preco, $descricao);
    }
}
