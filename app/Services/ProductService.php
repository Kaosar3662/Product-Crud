<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function create(array $data): Product
    {
        $thumbnail = null;

        if (!empty($data['thumbnail'])) {
            $thumbnail = $data['thumbnail']->store('products', 'public');
        }

        return Product::create([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
            'category_id' => $data['category_id'],
            'status' => $data['status'],
            'description' => $data['description'] ?? null,
            'thumbnail' => $thumbnail,
        ]);
    }

    public function update(Product $product, array $data): Product
    {
        if (!empty($data['thumbnail'])) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $product->thumbnail = $data['thumbnail']->store('products', 'public');
        }

        $product->update([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
            'category_id' => $data['category_id'],
            'status' => $data['status'],
            'description' => $data['description'] ?? $product->description,
        ]);

        return $product;
    }

    public function delete(Product $product): bool
    {
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        return (bool) $product->delete();
    }

    public function apiData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category_id' => $product->category_id,
            'category' => $product->category,
            'status' => $product->status,
            'description' => $product->description,
            'thumbnail' => $product->thumbnail
                ? url('storage/' . $product->thumbnail)
                : null,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }
}