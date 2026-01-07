<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $options = Option::query()->orderBy('name')->paginate(20);
        return view('seller.options.index', compact('options'));
    }

    public function create()
    {
        return view('seller.options.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'price' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        Option::create([
            'name' => $data['name'],
            'price' => (int)$data['price'],
            'is_active' => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()->route('seller.options.index')->with('success', 'Option dibuat.');
    }

    public function edit(Option $option)
    {
        return view('seller.options.edit', compact('option'));
    }

    public function update(Request $request, Option $option)
    {
        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'price' => ['required','integer','min:0'],
            'is_active' => ['nullable','boolean'],
        ]);

        $option->update([
            'name' => $data['name'],
            'price' => (int)$data['price'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return redirect()->route('seller.options.index')->with('success', 'Option diperbarui.');
    }

    public function destroy(Option $option)
    {
        $option->delete();
        return back()->with('success', 'Option dihapus.');
    }
}
