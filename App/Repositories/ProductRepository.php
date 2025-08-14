<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data)
    {
        return Product::create($data);
    }
    public function all()
    {
        return Product::with('category')->get();
    }

    public function find($id)
    {
        return Product::with('category')->findOrFail($id);
    }


    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product->load('category');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
