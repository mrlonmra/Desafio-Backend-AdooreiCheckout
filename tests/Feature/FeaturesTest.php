<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Sale;
use App\Models\Product;

class FeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se a aplicação retorna uma resposta de sucesso ao acessar a rota principal.
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    /**
     * Testa a criação bem-sucedida de uma venda.
     */
    public function testStoreSaleSuccessfully()
    {
        // Crie alguns produtos no banco de dados
        $products = Product::factory()->count(3)->create();

        // Dados simulados para criar uma venda
        $requestData = [
            'products' => [
                ['product_id' => $products[0]->id, 'amount' => 2],
                ['product_id' => $products[1]->id, 'amount' => 3],
                ['product_id' => $products[2]->id, 'amount' => 1],
            ],
        ];

        // Simule a presença do token no cabeçalho
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        // Realize a solicitação POST com o token no cabeçalho
        $response = $this->post("/api/sales", $requestData, ['X-TOKEN-ADOOREI-API' => $token]);

        // Verifique se a resposta está correta
        $response->assertStatus(201)
            ->assertJson([
                'sales_id' => $response['sales_id'],
                'amount' => $response['amount'],
                'products' => [
                    [
                        'product_id' => $products[0]->id,
                        'nome' => $products[0]->name,
                        'price' => number_format($products[0]->price, 2),
                        'amount' => $requestData['products'][0]['amount'],
                    ],
                    [
                        'product_id' => $products[1]->id,
                        'nome' => $products[1]->name,
                        'price' => number_format($products[1]->price, 2),
                        'amount' => $requestData['products'][1]['amount'],
                    ],
                ],
            ]);

        // Verifique se a venda foi corretamente criada no banco de dados
        $this->assertDatabaseHas('sales', [
            'id' => $response['sales_id'],
            'amount' => $response['amount'],
        ]);

        // Verifique se os produtos foram corretamente adicionados à venda no banco de dados
        foreach ($requestData['products'] as $item) {
            $this->assertDatabaseHas('product_sale', [
                'sale_id' => $response['sales_id'],
                'product_id' => $item['product_id'],
                'amount' => $item['amount'],
            ]);
        }
    }
    /**
     * Testa o cancelamento bem-sucedido de uma venda.
     */
    public function testCancelSaleSuccessfully()
    {
        // Crie uma venda no banco de dados
        $sale = Sale::factory()->create();

        // Simule a presença do token no cabeçalho
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        // Realize a solicitação DELETE com o token no cabeçalho
        $response = $this->delete("/api/sales/{$sale->id}", [], ['X-TOKEN-ADOOREI-API' => $token]);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Venda cancelada com sucesso',
                'status' => 'success',
            ]);

        // Verifique se a venda foi marcada como excluída do banco de dados
        $this->assertSoftDeleted('sales', ['id' => $sale->id]);
    }
    /**
     * Testa a exibição bem-sucedida dos detalhes de uma venda.
     */
    public function testShowSaleSuccessfully()
    {
        // Crie uma venda no banco de dados com produtos relacionados
        $sale = Sale::factory()->hasProducts(4)->create();

        // Simule a presença do token no cabeçalho
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        // Realize a solicitação GET com o token no cabeçalho
        $response = $this->get("/api/sales/{$sale->id}", ['X-TOKEN-ADOOREI-API' => $token]);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'sales_id' => $sale->id,
                'amount' => number_format($sale->amount, 2, '.', ''),
                'products' => $this->formatProducts($sale->products)
            ]);
    }
    /**
     * Testa a resposta correta ao tentar exibir uma venda que não existe.
     */
    public function testShowSaleNotFound()
    {
        // Simule a presença do token no cabeçalho
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        // Realize a solicitação GET para uma venda inexistente
        $response = $this->get('/api/sales/999', ['X-TOKEN-ADOOREI-API' => $token]);

        // Verifique se a resposta está correta
        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Venda não encontrada',
            ]);
    }
    /**
     * Testa a resposta correta ao tentar exibir uma venda sem autorização.
     */
    public function testShowSaleUnauthorized()
    {
        // Realize a solicitação GET sem um token válido
        $response = $this->get('/api/sales/1');

        // Verifique se a resposta está correta
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'ACESSO NÃO AUTORIZADO',
            ]);
    }
    /**
     * Testa a adição bem-sucedida de produtos a uma venda existente.
     */
    public function testAddProductsToSaleSuccessfully()
    {
        // Crie uma venda no banco de dados
        $sale = Sale::factory()->create();

        // Crie alguns produtos no banco de dados
        $products = Product::factory()->count(8)->create();

        // Dados simulados para adicionar produtos à venda
        $requestData = [
            'products' => [
                ['product_id' => $products[2]->id, 'amount' => 5],
                ['product_id' => $products[3]->id, 'amount' => 1],
                ['product_id' => $products[4]->id, 'amount' => 2],
                ['product_id' => $products[5]->id, 'amount' => 3],
                ['product_id' => $products[6]->id, 'amount' => 5],
                ['product_id' => $products[7]->id, 'amount' => 4],
            ],
        ];

        // Simule a presença do token no cabeçalho
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        // Realize a solicitação POST com o token no cabeçalho
        $response = $this->post("/api/sales/{$sale->id}/add-products", $requestData, ['X-TOKEN-ADOOREI-API' => $token]);

        // Verifique se a resposta está correta
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'amount',
                'created_at',
                'updated_at',
                'deleted_at',
                'products' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'description',
                        'created_at',
                        'updated_at',
                        'pivot' => [
                            'sale_id',
                            'product_id',
                            'amount',
                        ],
                    ],
                ],
            ]);

        // Verifique se os produtos foram corretamente adicionados à venda no banco de dados
        foreach ($requestData['products'] as $item) {
            $this->assertDatabaseHas('product_sale', [
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'amount' => $item['amount'],
            ]);
        }
    }

    /**
     * Função auxiliar para formatar produtos.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $products
     * @return array
     */
    private function formatProducts($products)
    {
        return $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'nome' => $product->name,
                'price' => number_format($product->price, 2, '.', ''),
                'amount' => $product->pivot->amount
            ];
        })->toArray();
    }

}