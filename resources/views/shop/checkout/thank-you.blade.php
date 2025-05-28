@extends('shop.layouts.app')

@section('content')
@php
    $purchaser = session()->get('purchaser');
@endphp

<div class="paymentsPage w-full p-4 bg-gray-100 bg-white shadow-md">
    <div class="container">
        Hey! Thank You for shopping with us.
    </div>
</div>

@endsection