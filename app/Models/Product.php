<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'thumbnail', 'category_id', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
