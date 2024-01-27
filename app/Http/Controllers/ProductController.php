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
}
