<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
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

    public function show(Product $id)
    {
        return $id->load('category');
    }

    public function update(Request $request, Product $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:50|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        $product = Product::update($validated);
        return response()->json([
            'status' => 'succes',
            'message' => 'product berhasil dibuat',
            'product' => $product
        ], 201);
    }

    public function destroy(Product $id)
    {
        $id->delete();
        return response()->json([
            'status' => 'Succes',
            'message' => 'Product telah dihapus oleh kamu'
        ]);
    }
}
