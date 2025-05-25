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
                <a class="Product border-2 p-4 m-2 col-span-4" href="{{ url('shop/'.$product['slug']) }}">
                    <div class="productName text-xl font-bold">{{$product['name']}}</div>
                    <div class="productDesc">{{$product['description']}}</div>
                </a>
            @endforeach
        @endif
    </div>
</div>

@endsection
