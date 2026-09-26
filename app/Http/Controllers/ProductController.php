<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Product::class);

        return view('admin.products.index', [
            'products' => Product::query()->with('category')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Product::class);

        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $attributes['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($attributes);

        return to_route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        return view('admin.products.show', [
            'product' => $product->load('category'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($product->image);
            $attributes['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($attributes);

        return to_route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        Storage::disk('public')->delete($product->image);
        $product->delete();

        return to_route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
