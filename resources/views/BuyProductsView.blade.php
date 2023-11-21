@component('mail::message')

<p style="text-align: center">Hello, {{ $cartItems->first()->user->name }}.</p>
<h4 style="text-align: center">Thank you for buying products from Aesthetics™</h4>
<p style="text-align: center">Details for the order can be seen below:</p> 

<ul style="list-style-type: none">
    @foreach($cartItems as $cartItem)
        <li>
            <div style="display: flex; padding-top: 10px">
                <div style="flex-basis: 20%">
                    <img src="{{ asset('storage/' . $cartItem->product->image) }}" width="80" height="120" alt="{{ $cartItem->product->title }}">
                </div>
                <div>
                    <p>Title: {{ $cartItem->product->title }}</p>
                    <p>Quantity: {{ $cartItem->product_quantity}}</p>
                    <p>Price: {{ $cartItem->product->price}}</p>
                </div>
            </div>
        </li>
    @endforeach
</ul>

<h4 style="text-align: right">Subtotal: {{ $priceInfo[0] }}</h4>
<h4 style="text-align: right">Discount: -{{ $priceInfo[1] }}</h4>
<h4 style="text-align: right">Total: {{ $priceInfo[2] }}</h4>


@endcomponent
