<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Demonstra a lista de todos produtos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Captura todos os produtos do banco de dados, ocultando as colunas Created e Updated.
        $products = Product::select('id', 'name', 'price', 'description')->get();

        // Traz uma resposta JSON com todos os produtos
        return response()->json($products);
    }

    /**
     * Faz o cadastro de um novo produto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Faz a validação dos dados da solicitação
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        // Adiciona um produto usando os dados validados anteriormente
        $product = Product::create($validatedData);

        // Traz uma resposta JSON com o produto adicionado e o status HTTP 201 (Criado)
        return response()->json($product, 201);
    }
}
