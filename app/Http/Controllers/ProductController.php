<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productIndex()
    {
        $categories = Category::all();
        return view('product.list', compact('categories'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:category,id',
            'name_product' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:product,product_code',
            'brand' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        Product::create($validated);

        return response()->json(['message' => 'Product created successfully.']);
    }
}
