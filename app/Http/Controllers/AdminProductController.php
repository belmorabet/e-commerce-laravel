<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\FavoriteProduct;
use App\Models\OldSlug;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    public function __construct(
        protected Product $product,
        protected FavoriteProduct $favorites,
        protected CartItem $cart
    ) {
    }

    public function index()
    {
        return view('admin.products.index', [
            'products' => $this->product->latest()->paginate(16),
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems()
        ]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems()
        ]);
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = str($validated['title'])->slug();
        if (array_key_exists('image', $validated)) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect(route('admin.products'))->with('success', 'New product has been added.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product,
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems()
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->product->createOldSlug($product);

        $validated = $request->validated();
        if (array_key_exists('image', $validated)) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);

        return redirect(route('admin.products'))->with('success', 'Product Updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product Deleted!');
    }

}
