@extends('shop.layouts.app')

@section('content')
@php
$arrProduct = $product->toArray();
$arrAttributes = getProductAttributes($arrProduct['id']);
$arrReviews = getProductReviews($arrProduct['id']);
@endphp
<div class="ProductDetailsPage flex flex-col w-full">
    <div class="Productp-4 m-2">
        <div class="productName text-xl font-bold">{{$arrProduct['name']}}</div>
        <div class="productDesc">{{$arrProduct['description']}}</div>
    </div>
    <div class="ProductAttributes overflow-x-auto">
        <table class="border border-gray-300">
            <tbody>
                @foreach ($arrAttributes as $attr => $value)
                    <tr class="bg-white hover:bg-gray-50 border-b border-gray-300">
                        <td class="px-4 py-2 border-b border-gray-300 font-semibold text-gray-800">{{ $attr }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="productReviews">
        @if (!empty($arrReviews))
            @foreach ($arrReviews as $review)
                <div class="review border-2 border-gray-400 p-2 m-2">
                    <div class="comment">{{ $review['comment'] }}</div>
                    <div class="rating text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review['rating'])
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="userName text-right">By {{getUserName($review['user_id'])}}</div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection
