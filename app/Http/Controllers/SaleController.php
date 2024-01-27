<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
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

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'amount' => 'required|numeric',
    //     ]);

    //     $sale = Sale::create($validatedData);

    //     $this->attachProductsToSale($sale, $request);

    //     return response()->json($sale, 201);
    // }

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

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json(null, 204);
    }

    public function addProducts(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $validatedData = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'amounts' => 'required|array',
            'amounts.*' => 'required|numeric',
        ]);

        $this->attachProductsToSale($sale, $request);

        return response()->json($sale, 200);
    }

    private function attachProductsToSale(Sale $sale, Request $request)
    {
        if ($request->has('product_ids') && $request->has('amounts')) {
            $validatedProductData = $request->validate([
                'product_ids' => 'array',
                'product_ids.*' => 'exists:products,id',
                'amounts' => 'array',
                'amounts.*' => 'numeric',
            ]);

            // Adicionar produtos à venda com quantidades
            $sale->products()->attach(array_combine($validatedProductData['product_ids'], $validatedProductData['amounts']));
        }
    }
}