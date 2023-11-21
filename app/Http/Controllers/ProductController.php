<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfOldSlug;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\FavoriteProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(
        protected Product $product,
        protected FavoriteProduct $favorites,
        protected CartItem $cart
    ) {
        $this->middleware(RedirectIfOldSlug::class)->only('show');
    }

    public function index()
    {
        $currentSort = $this->product->currentSorting();

        return view('products.index', [
            'products' => $this->product->sorting($this->product),
            'categories' => Category::all(),
            'currentSort' => $currentSort,
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems()
        ]);
    }

    public function show(Request $request)
    {
        $slug = $request->route('slug');

        $product = Product::query()->where('slug', $slug)->first();

        if ($product->in_stock_quantity == 0) {
            $quantityLabel = 'In Stock: Not available';
        } else {
            $quantityLabel = 'In Stock: '.$product->in_stock_quantity;
        }

        return view('products.show', [
            'product' => $product,
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems(),
            'quantityLabel' => $quantityLabel
        ]);
    }


}
