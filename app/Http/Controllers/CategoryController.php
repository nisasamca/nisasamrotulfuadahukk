<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all(); 
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required', 
            'division' => 'required',
            'pj' => 'required'
        ]);
        Category::create($request->all());
        return back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'nama' => 'required', 
            'division' => 'required',
            'pj' => 'required'
        ]);
        $category->update($request->all());
        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}
