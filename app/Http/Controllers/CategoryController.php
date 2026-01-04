<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        
        return view('category.list');
    }

    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->category_name = $request->input('category_name');
        $category->save();

        return response()->json(['success' => 'Category created successfully'], 201);
    }
    public function edit($id)
    {
        $category = Category::find($id);
        return response()->json($category);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::find($id);
        $category->category_name = $request->category_name;
        $category->save();

        return response()->json(['success' => 'Category updated successfully'], 200);
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json(['success' => 'Category deleted successfully'], 200);
    }
}
