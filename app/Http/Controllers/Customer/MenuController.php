<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q',''));
        $category = $request->get('category','all');

        $categories = Category::orderBy('name')->get();

        $products = Product::query()
            ->with(['category','options'])
            ->when($category !== 'all', fn($qq) => $qq->where('category_id', $category))
            ->when($q !== '', fn($qq) => $qq->where('name','like',"%{$q}%"))
            ->orderBy('name')
            ->get();

        return view('customer.menu', [
            'categories' => $categories,
            'products' => $products,
            'q' => $q,
            'category' => $category,
        ]);
    }
}
