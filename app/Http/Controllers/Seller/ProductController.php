<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all|available|out
        $q = trim((string)$request->query('q', ''));

        $query = Product::query()->with('category');

        if ($filter === 'available') $query->where('is_available', true)->where('stock','>',0);
        if ($filter === 'out') $query->where('stock','<=',0);

        if ($q !== '') $query->where('name','like',"%{$q}%");

        $products = $query->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('seller.products.index', compact('products','filter','q'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $options = Option::query()->where('is_active', true)->orderBy('name')->get();
        return view('seller.products.create', compact('categories','options'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string','max:500'],
            'price' => ['required','integer','min:0'],
            'stock' => ['required','integer','min:0'],
            'is_available' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
            'image' => ['nullable','image','max:2048'],
            'option_ids' => ['nullable','array'],
            'option_ids.*' => ['integer','exists:options,id'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => (int)$data['price'],
            'stock' => (int)$data['stock'],
            'is_available' => (bool)($data['is_available'] ?? true),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'image_path' => $imagePath,
        ]);

        $product->options()->sync($data['option_ids'] ?? []);

        return redirect()->route('seller.products.index')->with('success', 'Produk dibuat.');
    }

    public function edit(Product $product)
    {
        $product->load('options');
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $options = Option::query()->where('is_active', true)->orderBy('name')->get();
        $selected = $product->options->pluck('id')->all();
        return view('seller.products.edit', compact('product','categories','options','selected'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string','max:500'],
            'price' => ['required','integer','min:0'],
            'stock' => ['required','integer','min:0'],
            'is_available' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
            'image' => ['nullable','image','max:2048'],
            'option_ids' => ['nullable','array'],
            'option_ids.*' => ['integer','exists:options,id'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) Storage::disk('public')->delete($product->image_path);
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => (int)$data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => (int)$data['price'],
            'stock' => (int)$data['stock'],
            'is_available' => (bool)($data['is_available'] ?? false),
            'sort_order' => (int)($data['sort_order'] ?? 0),
            'image_path' => $product->image_path,
        ]);

        $product->options()->sync($data['option_ids'] ?? []);

        return redirect()->route('seller.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) Storage::disk('public')->delete($product->image_path);
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}
