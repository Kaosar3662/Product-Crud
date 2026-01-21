<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'thumbnail', 'price', 'category_id', 'status', 'description'];

    protected $hidden = [
        'id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
