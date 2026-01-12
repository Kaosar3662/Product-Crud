<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('products.create', compact('categories'));
    }

    // Add
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        try {
            $data = [
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'category_id' => $request->category_id,
                'status'      => $request->has('status') ? 1 : 0,
            ];

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    // Edit
    public function edit(Product $product)
    {
        $categories = Category::where('status', 1)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        try {
            $data = [
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'category_id' => $request->category_id,
                'status'      => $request->has('status') ? 1 : 0,
            ];

            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }

                $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    // Delete
    public function destroy(Product $product)
    {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();
        return redirect()->route('products.index');
    }
}


