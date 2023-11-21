<?php

namespace App\Http\Controllers;

use App\Events\CartUpdated;
use App\Jobs\SendEmail;
use App\Mail\BuyProducts;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\FavoriteProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartItemController extends Controller
{
    public function __construct(
        protected FavoriteProduct $favorites,
        protected CartItem $cart
    ) {
    }

    public function index()
    {
        [$subtotal, $discount, $total] = $this->definePricing();

        return view('cart.index', [
            'cartItems' => $this->cart->latest()->where('user_id', '=', auth()->id())->paginate(8),
            'favorites' => $this->favorites->numberOfFavorites(),
            'numberOfCartItems' => $this->cart->numberOfCartItems(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total
        ]);
    }

    public function store(Product $product)
    {
        try {
            $cartItem = new CartItem();

            $cartItem->user_id = auth()->id();
            $cartItem->product_id = $product->id;

            $cartItem->product_quantity = request('quantity');

            if (request('quantity') === null) {
                $cartItem->product_quantity = 1;
            }

            if (request('quantity') > $product->in_stock_quantity) {
                return back()->with('error', 'There are not that many products in stock');
            }

            if (request('quantity') > 5) {
                return back()->with('error', 'You cannot buy more than 5 products');
            }

            $cartItem->save();

            return back()->with('success', 'The item was added to the cart');
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('error', 'The item has already been added to the cart');
        }

    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    public function destroyAll()
    {
        $cartItems = CartItem::all();

        $priceInfo = $this->definePricing();

        $details = [
            'email' => Auth::user()->email,
            'cartItems' => $cartItems,
            'priceInfo' => $priceInfo
        ];

        $this->dispatch(new SendEmail($details));

        session()->forget('coupon');

        foreach ($cartItems as $cartItem) {
            $cartItem->product->decrement('in_stock_quantity', min($cartItem->product_quantity, $cartItem->product->in_stock_quantity));

        }

        CartItem::query()->delete();

        return back()->with('success', 'Congratulations, you bought the products!');
    }

    public function increaseQuantity(CartItem $cartItem)
    {
        if ($cartItem->product_quantity === 5) {
            return back()->with('error', 'You cannot buy more than 5 products');
        }

        if ($cartItem->product->in_stock_quantity === $cartItem->product_quantity) {
            return back()->with('error', 'Not enough products in stock');
        }

        $cartItem->increment('product_quantity', 1);

        return back();
    }

    public function decreaseQuantity(CartItem $cartItem)
    {
        if ($cartItem->product_quantity == 1) {
            $cartItem->delete();

            return redirect(route('cart'))->with('success', 'Product removed from cart!');
        }

        $cartItem->decrement('product_quantity', 1);

        return back();
    }

    /**
     * @param  mixed  $discount
     * @param  float  $subtotal
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function removeCouponIfPriceTooLow(mixed $discount, float $subtotal): void
    {
        if (session()->get('coupon')['type'] === 'fixed' && $subtotal < ($discount * 2)) {
            session()->forget('coupon');
            session()->now(
                'error',
                'The price of the chosen items is too low for the coupon to be applied!'
            );
        }
    }

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function definePricing(): array
    {
        $subtotal = $this->cart->subtotalPrice();
        $coupon = session()->get('coupon');
        $discount = 0;

        if ($coupon) {
            event(new CartUpdated($this->cart));

            $discount = (int)session()->get('coupon')['discount'];

            $this->removeCouponIfPriceTooLow($discount, $subtotal);
        }

        $total = number_format($this->cart->totalPrice($subtotal, $discount), 2);
        return array($subtotal, $discount, $total);
    }

}
