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
    <div class="products grid grid-cols-12">
        @if(!empty($arrProducts))
            @foreach ($arrProducts as $product)
                @php
                     $productImage = getProductMainImage($product['id']);
                     $category = getProductCategory($product['category_id']);
                @endphp
                <a class="Product border-2 p-4 m-2 col-span-2 relative" href="{{ url('shop/'.$product['slug']) }}">
                    <div class="ProductImage">
                        <img src="{{ asset('storage/' . $productImage['path']) }}" alt="{{$product['name']}}">
                    </div>
                    <div class="categoryName text-sm border-2 bg-gray-200 absolute top-0 right-0 z-50">{{$category['name']}}</div>
                    <div class="productName text-xl font-bold">{{$product['name']}}</div>
                </a>
            @endforeach
        @endif
    </div>
</div>

@endsection
