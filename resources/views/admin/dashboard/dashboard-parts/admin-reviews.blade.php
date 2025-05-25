@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrReviews = $reviews->toArray();
@endphp
<div class="admin-reviews">
    All Reviews
    @if (!empty($arrReviews))
        @foreach ($arrReviews as $review)
        <div class="review border-2 border-gray-400 p-2 m-2">
            <div class="ProductName"><strong>Product:</strong>{{getProductName($review['product_id'])}}</div>
            <div class="comment">{{ $review['comment'] }}</div>
            <div class="rating text-yellow-500">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review['rating'])
                        <i class="fas fa-star"></i> {{-- Filled Star --}}
                    @else
                        <i class="far fa-star"></i> {{-- Empty Star --}}
                    @endif
                @endfor
            </div>
            <div class="userName text-right">By {{getUserName($review['user_id'])}}</div>
        </div>
        @endforeach
    @endif
</div>

@endsection