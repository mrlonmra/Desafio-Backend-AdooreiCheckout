<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::all();
        return response()->json($sales);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
        ]);

        $sale = Sale::create($validatedData);

        $this->attachProductsToSale($sale, $request);

        return response()->json($sale, 201);
    }

    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        return response()->json($sale);
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