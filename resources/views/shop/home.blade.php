@extends('shop.layouts.app')

@section('content')
@php
$arrProducts = $products->toArray();

@endphp
<div class="HomePage flex flex-col w-full">
    <div class="homepageSlideShow mx-24">
        <div class="slideshow h-96 w-full bg-red-500"></div>
    </div>
    <div class="p-2"><hr></div>
    {{-- List All Products --}}
    <div class="products grid grid-cols-12 gap-8 max-w-[1400px] mx-auto">
        @if(!empty($arrProducts))
            @foreach ($arrProducts as $product)
                @php
                     $productImage = getProductMainImage($product['id']);
                     $category = getProductCategory($product['category_id']);
                @endphp
                <div class="Product border-4 shadow-lg border-white m-2 col-span-3 relative flex flex-col h-full m-2 bg-primary">
                    <div class="categoryName text-sm border-2 bg-gray-200 absolute top-0 right-0 z-50">{{$category['name']}}</div>
                    <a class="productTop" href="{{ url('shop/'.$product['slug']) }}">
                        <div class="ProductImage overflow-hidden">
                            <img src="{{ asset('storage/' . $productImage['path']) }}" alt="{{$product['name']}}" class="w-full h-full hover:scale-110 ease-in-out duration-100 cover">
                        </div>
                        <div class="topContent p-4">
                            <div class="productName text-xl font-bold">{{$product['name']}}</div>
                            {{-- Price Details --}}
                            <div class="productPrice text-lg py-2">
                                @if ($product['compare_at_price'])
                                    {!! dollarSign() !!}<span class="productComparePrice text-sm line-through">{{$product['compare_at_price']}}</span>
                                @endif
                                {!! dollarSign() !!}{{$product['price']}}
                            </div>
                        </div>
                    </a>
                    <div class="productBottom p-4 mt-auto flex items-center justify-between">
                        <button type="button"
                                class="addToCartBtn border-2 border-gray-500 rounded p-2 flex justify-center items-center hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400"
                                data-product-id="{{ $product['id'] }}">
                            {{-- <span><i class="fa-solid fa-cart-plus"></i></span> --}}
                            <span>Add to Cart</span>
                        </button>
                        <span class="addToFavorites">
                            <span class="border-2 border-gray-500 rounded-full h-8 w-8 flex justify-center items-center"><i class="fa-regular fa-heart"></i></span>
                        </span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToCartButtons = document.querySelectorAll('.addToCartBtn');

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;
                
                button.disabled = true; button.textContent = 'Adding...';

                fetch("{{ route('shop.cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1 // We are adding one item at a time.
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        // Try to parse error an JSON if server sends it
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    // Handle success (e.g., show a success message, update cart icon/count)
                    alert(data.message || 'Product added to cart!');

                    // Update the cart count display
                    if (data.cartItemCount !== undefined && document.getElementById('cart-count')) {
                        document.getElementById('cart-count').textContent = data.cartItemCount;
                    }

                    // Re-enable button
                    button.disabled = false; button.textContent = 'Add to Cart';
                })
                .catch((error) => {
                    console.error('Error:', error);
                    let errorMessage = 'Could not add product to cart.';
                    if (error && error.message) {
                        errorMessage = error.message;
                    } else if (typeof error === 'string') {
                        errorMessage = error;
                    }
                    alert(errorMessage); // Show error message
                    // Re-enable button
                    button.disabled = false; button.textContent = 'Add to Cart';
                });
            });
        });
    });
</script>
@endpush
@endsection
