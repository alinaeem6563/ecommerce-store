<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EcommerceProductCategory extends Controller
{
    public function index()
    {
        $collections=Collection::all();
        $categories = Category::paginate(10);
        return view('content.apps.app-ecommerce-category-list', compact('categories', 'collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryTitle' => 'required|string|max:255',
            'slug' => 'nullable|string',
            'categoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoryDescription' => 'nullable|string',
            'categoryStatus' => 'required|in:Publish,Inactive',
        ]);

        $imagePath = $request->hasFile('categoryImage')
            ? $request->file('categoryImage')->store('categories', 'public')
            : null;

        Category::create([
            'name' => $validated['categoryTitle'],
            'slug' => $validated['slug'] ?: Str::slug($validated['categoryTitle']),
            'image_path' => $imagePath,
            'description' => $validated['categoryDescription'],
            'status' => $validated['categoryStatus'],
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        return view('content.apps.edit-category', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'categoryTitle' => 'required|string|max:255',
            'slug' => 'string|nullable',
            'categoryImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'categoryDescription' => 'string|nullable',
            'categoryStatus' => 'required|in:Publish,Inactive',
        ]);

        if ($request->hasFile('categoryImage')) {
            if ($category->image_path) {
                Storage::delete('public/' . $category->image_path);
            }
            $validated['image_path'] = $request->file('categoryImage')->store('categories', 'public');
        } else {
            $validated['image_path'] = $category->image_path;
        }

        $category->update([
            'name' => $validated['categoryTitle'],
            'slug' => $validated['slug'] ?: Str::slug($validated['categoryTitle']),
            'image_path' => $validated['image_path'],
            'description' => $validated['categoryDescription'],
            'status' => $validated['categoryStatus'],
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image_path) {
            Storage::delete('public/' . $category->image_path);
        }
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
    }
}
