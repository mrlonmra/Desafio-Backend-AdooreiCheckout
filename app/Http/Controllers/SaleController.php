<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Lista todas as vendas com os produtos relacionados.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sales = Sale::with('products')->get(); // Carrega os produtos relacionados a cada venda
        $formattedSales = [];

        foreach ($sales as $sale) {
            $formattedSale = [
                'sales_id' => $sale->id,
                'amount' => $sale->amount,
                'products' => $this->formatProducts($sale->products),
            ];

            $formattedSales[] = $formattedSale;
        }

        return response()->json($formattedSales);
    }

    /**
     * Formata os produtos para inclusão no retorno JSON.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $products
     * @return array
     */
    private function formatProducts($products)
    {
        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProduct = [
                'product_id' => $product->id,
                'nome' => $product->name,
                'price' => $product->price,
                'amount' => $product->pivot->amount, // A quantidade de produtos vendidos
            ];

            $formattedProducts[] = $formattedProduct;
        }

        return $formattedProducts;
    }

    /**
     * Formatar os detalhes dos produtos para a resposta.
     *
     * @param \Illuminate\Database\Eloquent\Collection $productsDetails
     * @param array $selectedProducts
     * @return array
     */
    private function formatProductsResponse($productsDetails, $selectedProducts)
    {
        return collect($selectedProducts)->map(function ($item) use ($productsDetails) {
            $product = $productsDetails->where('id', $item['product_id'])->first();
            return [
                'product_id' => $product->id,
                'nome' => $product->name,
                'price' => $product->price,
                'amount' => $item['amount'],
            ];
        })->toArray();
    }

    /**
     * Cria uma nova venda com os produtos fornecidos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.amount' => 'required|numeric',
        ]);

        // Obter detalhes dos produtos
        $productsDetails = Product::whereIn('id', collect($validatedData['products'])->pluck('product_id'))->get();

        // Calcular o amount total somando os preços multiplicados pelas quantidades
        $totalAmount = collect($validatedData['products'])->sum(function ($item) use ($productsDetails) {
            $product = $productsDetails->where('id', $item['product_id'])->first();
            return $product->price * $item['amount'];
        });

        // Criar a venda
        $sale = Sale::create(['amount' => $totalAmount]);

        // Anexar os produtos à venda
        $this->attachProductsToSale($sale, $validatedData['products']);

        // Montar a resposta no formato desejado
        $response = [
            'sales_id' => $sale->getKey(), // Obter automaticamente o ID da venda
            'amount' => $totalAmount,
            'products' => $this->formatProductsResponse($productsDetails, $validatedData['products']),
        ];

        return response()->json($response, 201);
    }


    // Método para associar produtos a uma venda
    private function attachProductsToSale(Sale $sale, array $products)
    {
        foreach ($products as $productData) {
            $product = Product::find($productData['product_id']);

            // Adiciona o produto à venda com a quantidade especificada
            $sale->products()->attach($product, ['amount' => $productData['amount']]);
        }
    }

    /**
     * Adiciona produtos a uma venda existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProductsToSale(Request $request, $id)
    {
        $validatedData = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.amount' => 'required|numeric',
        ]);

        $sale = Sale::findOrFail($id);

        // Obter detalhes dos produtos
        $productsDetails = Product::whereIn('id', collect($validatedData['products'])->pluck('product_id'))->get();

        foreach ($validatedData['products'] as $item) {
            $existingProduct = $sale->products()->where('product_id', $item['product_id'])->first();

            if ($existingProduct) {
                // Se o produto já existe na venda, atualiza os valores
                $existingProduct->pivot->update([
                    'amount' => $existingProduct->pivot->amount + $item['amount'],
                ]);
            } else {
                // Se o produto não existe na venda, adiciona-o
                $product = $productsDetails->where('id', $item['product_id'])->first();
                $sale->products()->attach($product->id, [
                    'amount' => $item['amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Recalcular o amount total da venda
        $sale->load('products');
        $totalAmount = $sale->products->sum(function ($product) {
            return $product->price * $product->pivot->amount;
        });
        $sale->update(['amount' => $totalAmount]);

        return response()->json($sale, 200);
    }

    /**
     * Exibe os detalhes de uma venda específica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $sale = Sale::with('products')->findOrFail($id); // Carrega os produtos relacionados à venda

        $formattedSale = [
            'sales_id' => $sale->id,
            'amount' => $sale->amount,
            'products' => $this->formatProducts($sale->products),
        ];

        return response()->json($formattedSale);
    }

    /**
     * Cancela uma venda.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json(['message' => 'Venda cancelada com sucesso', 'status' => 'success'], 200);
    }
}