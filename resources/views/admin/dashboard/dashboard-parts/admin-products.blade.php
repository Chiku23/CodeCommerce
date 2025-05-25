@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrProducts = $products->toArray();
@endphp
<div class="admin-reviews">
    All Products
    @if (!empty($arrProducts))
        @foreach ($arrProducts as $product)
        <div class="review border-2 border-gray-400 p-2 m-2">
            <div class="ProductBrand"><strong>Brand: </strong>{{ getBrandName($product['brand_id']) }}</div>
            <div class="ProductName"><strong>Name: </strong>{{ $product['name'] }}</div>
            <div class="ProductDescription"><strong>Desctiption: </strong>{{ $product['description'] }}</div>
        </div>
        @endforeach
    @endif
</div>

@endsection