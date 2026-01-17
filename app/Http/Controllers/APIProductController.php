<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class APIProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Get all products
    public function index()
    {
        return response()->json(
            Product::latest()->get()
        );
    }

    /*  
    
    public function index(Request $request) 
    {
        // Get query params
        $search = $request->query('search'); // ?search=shirt
        $limit  = (int) $request->query('limit', 10); // ?limit=10
        $offset = (int) $request->query('offset', 0); // ?offset=0

        // Start query
        $query = Product::with('category')->latest();

        // Apply search
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        // Get total count for pagination info
        $total = $query->count();

        // Apply limit + offset
        $products = $query->skip($offset)->take($limit)->get();

        // Return JSON
        return response()->json([
            'total' => $total,
            'count' => $products->count(),
            'products' => $products,
        ]);
    }
*/




    // Get single product
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json(
            $this->productService->apiData($product)
        );
    }

    // Store new product
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = $request->boolean('status');

        $product = $this->productService->create([
            ...$validated,
            'thumbnail' => $request->file('thumbnail'),
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $this->productService->apiData($product),
        ], 201);
    }

    // Update product
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $validated['status'] = $request->boolean('status');

        $product = $this->productService->update($product, [
            ...$validated,
            'thumbnail' => $request->file('thumbnail'),
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $this->productService->apiData($product),
        ]);
    }

    // Delete product
    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
