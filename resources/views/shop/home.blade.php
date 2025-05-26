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
                        <div class="ProductImage">
                            <img src="{{ asset('storage/' . $productImage['path']) }}" alt="{{$product['name']}}" class="w-full h-full cover">
                        </div>
                        <div class="productName text-xl font-bold p-4">{{$product['name']}}</div>
                    </a>
                    <div class="productBottom p-4 mt-auto flex items-center justify-between">
                        <span class="addToCart border-2 border-gray-500 rounded p-2 flex justify-center items-center">
                            {{-- <span><i class="fa-solid fa-cart-plus"></i></span> --}}
                            <span>Add to Cart</span>
                        </span>
                        <span class="addToFavorites">
                            <span class="border-2 border-gray-500 rounded-full h-8 w-8 flex justify-center items-center"><i class="fa-regular fa-heart"></i></span>
                        </span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection
