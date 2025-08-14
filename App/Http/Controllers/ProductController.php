<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        return response()->json($this->productRepo->all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = $this->productRepo->create($validated);

        return response()->json([
            'status' => 'succes',
            'message' => 'product berhasil dibuat',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:50|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        $product = $this->productRepo->update($id, $validated);

        return response()->json([
            'status' => 'succes',
            'message' => 'product berhasil diubah',
            'product' => $product
        ], 200);
    }

    public function destroy(Product $product)
    {
        $this->productRepo->delete($product->id);
        return response()->json([
            'status' => 'Succes',
            'message' => 'Product telah dihapus oleh kamu'
        ]);
    }
}
