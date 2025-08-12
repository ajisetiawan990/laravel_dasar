<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        return Category::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $id)
    {
        return $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $id)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $id->update($validated);

        return $id;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $id)
    {
        $id->delete();

        return response()->noContent();
    }
}
