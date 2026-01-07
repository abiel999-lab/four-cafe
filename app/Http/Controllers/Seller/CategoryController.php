<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
        ]);

        Category::create([
            'name' => $data['name'],
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategori dibuat.');
    }

    public function edit(Category $category)
    {
        return view('seller.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
        ]);

        $category->update([
            'name' => $data['name'],
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
