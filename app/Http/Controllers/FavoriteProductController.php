<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\FavoriteProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteProductController extends Controller
{
    public function __construct(
        protected FavoriteProduct $favorites,
        protected CartItem $cart
    ) {
    }

    public function index()
    {

        return view('favorites.index', [
            'favoriteProducts' => $this->favorites->latest()->where('user_id', '=', auth()->id())->paginate(8),
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems()
        ]);
    }

    public function store(Product $product)
    {
        try {
            //add a product to favorites of the logged in person
            $favorite = new FavoriteProduct();

            $favorite->product()->associate($product);
            $favorite->user()->associate(auth()->user());

            $favorite->save();

            return back()->with('success', 'The item was added to favorites');
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('error', 'The item was already added to favorites');
        }

    }

    public function destroy(FavoriteProduct $favorite)
    {
        $favorite->delete();

        return back()->with('success', 'Product removed from favorites!');
    }
}
