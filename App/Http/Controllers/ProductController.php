<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'status' => 'succes',
            'message' => 'product berhasil dibuat',
            'product' => $product
        ], 201);
    }
}
