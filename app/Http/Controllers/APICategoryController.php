<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class APICategoryController extends Controller
{
    public function show()
    {
        $categories = Category::where('status', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
