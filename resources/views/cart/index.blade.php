<x-layout>
    <x-header favorites="{{ $favorites }}" numberOfCartItems="{{ $numberOfCartItems }}"/>
    <main>
        @if($numberOfCartItems == 0)
            <p>No products in the cart</p>
        @else
            <div class="mt-14">
                @foreach($cartItems as $cartItem)
                    <div class="flex grid grid-cols-6 border-2 m-2 h-40">
                        @if($cartItem->product->image == null)
                            <a href="{{ route('product.show', ['slug' => $cartItem->product->slug]) }}"><img src="{{ asset('images/imageNotAvailable.png') }}" alt="{{ $cartItem->product->title }}"
                                 class="shrink-0 w-24 h-32 mt-4 ml-10"></a>
                        @else
                            <a href="{{ route('product.show', ['slug' => $cartItem->product->slug]) }}"><img src="{{ asset('storage/' . $cartItem->product->image) }}" alt="{{ $cartItem->product->title }}"
                                 class="shrink-0 w-24 h-32 mt-4 ml-10"></a>
                        @endif
                        <a href="{{ route('product.show', ['slug' => $cartItem->product->slug]) }}" class="text-center place-self-center hover:text-blue-600 focus:text-blue-900"> {{ $cartItem->product->title }}</a>

                        <p class="text-center place-self-center text-red-500">${{ $cartItem->product->price }}</p>

                        <p class="text-center place-self-center"> {{ $cartItem->product->pages }} pages</p>

                        <div class="grid grid-cols-3 place-self-center">
                            <form method="POST" action="{{ route('cart.decrease', ['cartItem' => $cartItem->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-amber-400 w-7 rounded-full text-xl">-</button>
                            </form>
                            <p class="text-center"> {{ $cartItem->product_quantity }}</p>
                            <form method="POST" action="{{ route('cart.increase',['cartItem' => $cartItem->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-amber-400 w-7 rounded-full text-xl">+</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('cart.delete', ['cartItem' => $cartItem->id]) }}" class="place-self-center">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-white w-36 h-9 bg-red-500 hover:bg-red-700 focus:ring-4  font-medium rounded-3xl text-sm">
                                Remove from cart
                            </button>
                        </form>
                    </div>
                @endforeach

                <div class="mt-2">
                    {{ $cartItems->links() }}
                </div>
            </div>

{{--        Coupon Section--}}
            <div class="grid grid-cols-2">
                <div>
                    @if(! session()->get('coupon'))
                        <form method="POST" action="{{ route('coupon.store') }}" class="grid grid-cols-2 ml-2">
                            @csrf
                            <x-form.input name="coupon_code" type="text" label="Coupon code"/>
                            <x-primary-button class="w-20 h-10 ml-3 mt-9 rounded-l">Apply</x-primary-button>
                        </form>
                    @endif
                </div>
                <div class="grid grid-cols-2 text-right mr-4">
                    <p class="text-3xl font-semibold pt-10">Subtotal:</p>
                    <p class="text-3xl font-semibold pt-10">${{ $subtotal }}</p>
                    <div>
                        <p class="text-xl font-semibold -mb-2 text-green-500">Discount ({{ isset(session()->get('coupon')['name']) ? session()->get('coupon')['name'] : '' }}):</p>
                        @if(session()->get('coupon'))
                            <form method="POST" action="{{ route('coupon.delete') }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-500">Remove Coupon</button>
                            </form>
                        @endif
                    </div>
                    <p class="text-xl font-semibold text-right text-green-500"> -{{ isset(session()->get('coupon')['discount']) ? '$' . $discount : '0' }}</p>
                    <p class="text-3xl font-semibold mt-3 ">Total:</p>
                    <p class="text-3xl font-semibold mt-3 border-t-2 border-black dark:border-white">${{ $total }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('cart.delete.all') }}" class="grid justify-items-center mt-2">
                @csrf
                @method('DELETE')
                <x-primary-button class="w-36 bg-green-500 hover:bg-green-700 focus:ring-1 focus:ring-green-300 mt-1">Buy</x-primary-button>
            </form>
        @endif


    </main>
    <x-footer/>
</x-layout>
