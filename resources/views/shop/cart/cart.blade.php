@extends('shop.layouts.app')

@section('content')
<div class="CartDetailsPage flex flex-col w-full p-4 md:p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Your Shopping Cart</h1>

    @if ($cart && $cart->items->count() > 0)
        <div class="cart-items-container bg-white rounded-lg shadow-md p-4 md:p-6 mb-8">
            <div class="hidden md:grid grid-cols-5 gap-4 py-3 px-2 border-b border-gray-200 font-semibold text-gray-600">
                <div class="col-span-2 ml-8">Product</div>
                <div>Price</div>
                <div>Quantity</div>
                <div class="text-right">Subtotal</div>
            </div>

            @foreach ($cart->items as $item)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center py-4 px-2 border-b border-gray-100 last:border-b-0">
                    {{-- Product Name (Mobile & Desktop) --}}
                    <div class="col-span-2 flex items-center">
                        <button class="removeFromCart p-4 mr-8 h-8 w-8 flex justify-center items-center" data-item-id="{{ $item->id }}" data-cart-id="{{ $item->cart_id }}">
                            <i class="fa-solid fa-trash text-2xl text-red-500"></i>
                        </button>
                        {{-- Placeholder for product image --}}
                        <img src="{{ asset('storage/' . getProductMainImage($item->product->id)['path']) }}" alt="{{ $item->product->name ?? 'Product Image' }}" class="w-20 h-20 object-cover rounded-lg mr-4">
                        <span class="font-medium text-gray-900">{{ $item->product->name ?? 'Unknown Product' }}</span>
                    </div>

                    {{-- Price (Mobile & Desktop) --}}
                    <div class="flex justify-between md:block">
                        <span class="md:hidden font-semibold text-gray-600">Price:</span>
                        <span class="text-gray-700">{!! dollarSign() !!}{{ number_format($item->price_at_add, 2) }}</span>
                    </div>

                    {{-- Quantity (Mobile & Desktop) --}}
                    <div class="flex justify-between md:block">
                        <span class="md:hidden font-semibold text-gray-600">Quantity:</span>
                        <span class="text-gray-700">{{ $item->quantity }}</span>
                        {{-- You would add update quantity buttons/input here --}}
                    </div>

                    {{-- Subtotal (Mobile & Desktop) --}}
                    <div class="flex justify-between md:block text-right">
                        <span class="md:hidden font-semibold text-gray-600">Subtotal:</span>
                        <span class="font-bold text-gray-900">{!! dollarSign() !!}{{ number_format($item->price_at_add * $item->quantity, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-summary bg-white rounded-lg shadow-md p-4 md:p-6 self-end w-full md:w-1/3 lg:w-1/4">
            <div class="flex justify-between items-center text-xl font-bold text-gray-800 mb-4 border-b pb-3">
                <span>Total:</span>
                <span>{!! dollarSign() !!}{{ number_format($cart->items->sum(fn($item) => $item->price_at_add * $item->quantity), 2) }}</span>
            </div>
            <a href="{{ Route('shop.checkout.index') }}" class="w-full block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out">
                Proceed to Checkout
            </a>
        </div>

    @else
        <div class="empty-cart-message bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-xl text-gray-700 mb-4">Your shopping cart is empty.</p>
            <a href="{{ url('/') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                Continue Shopping
            </a>
        </div>
    @endif
</div>


{{-- Add this script section, or include it in your app.js --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const removeFromCartButtons = document.querySelectorAll('.removeFromCart');

    removeFromCartButtons.forEach(button => {
        button.addEventListener('click', async function () {
            try {
                // Get item ID and cart ID from data attributes
                const itemId = this.dataset.itemId;
                const cartId = this.dataset.cartId;

                // Send POST request to remove product from cart
                const response = await fetch("{{ route('shop.cart.remove') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        cart_id: cartId
                    })
                });

                if (!response.ok) {
                    // Try to parse error as JSON
                    return response.json().then(err => { throw err; });
                }

                const data = await response.json();
                showPopup(data.message || 'Product removed from cart!',true);

            } catch (error) {
                console.error('Error:', error);
                let errorMessage = 'Could not remove product from cart.';
                if (error && error.message) {
                    errorMessage = error.message;
                } else if (typeof error === 'string') {
                    errorMessage = error;
                }
                showPopup(errorMessage); // Show error message
                // Re-enable button
                button.disabled = false;
            }
        });
    });
});
</script>
@endpush

@endsection