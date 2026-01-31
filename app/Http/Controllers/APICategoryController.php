<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class APICategoryController extends BaseController
{
    public function show()
    {
        $categories = Category::where('status', 1)->get();
        return $this->sendResponse($categories);
    }
}
