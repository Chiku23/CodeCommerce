@extends('shop.layouts.app')

@section('content')
@php
    $totalCartItems = $cart->items()->sum('quantity');
@endphp
<div class="CheckoutEmailsPage w-full p-4 bg-gray-100 bg-white shadow-md">
    {{-- Show Errors --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative w-1/2 mx-auto mb-4">
        <strong class="font-bold">Whoops!</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="container grid grid-cols-12 gap-8">
        <div class="emailPageTop col-span-8 border-r-2 border-gray-400">
            <div class="CartDetails">
                <ol class="px-8 list-decimal text-md">
                    <div class="text-xl py-2">Hello.... You have {{ $totalCartItems }} items in your cart. Please check and confirm before you checkout.</div>
                    <div class="text-xl py-2 border-b-2 mb-4">Here are your cart contents:</div>

                    @foreach ($cart->items as $item)
                        <li class="">
                            {{-- Product Name (Mobile & Desktop) --}}
                            <div class="items-center">
                                {{-- Placeholder for product image --}}
                                <span class="font-medium text-gray-900">{{ $item->product->name ?? 'Unknown Product' }}</span>
                            </div>

                            {{-- Price (Mobile & Desktop) --}}
                            <div class="justify-between md:block">
                                <span class="text-gray-700">{{ $item->quantity }} X </span>
                                <span class="md:hidden font-semibold text-gray-600">Price:</span>
                                <span class="text-gray-700">{!! dollarSign() !!}{{ number_format($item->price_at_add, 2) }}</span>
                            </div>

                            {{-- Subtotal (Mobile & Desktop) --}}
                            <div class="justify-between text-right">
                                <span class="md:hidden font-semibold text-gray-600">Subtotal:</span>
                                <span class="font-bold text-gray-900">{!! dollarSign() !!}{{ number_format($item->price_at_add * $item->quantity, 2) }}</span>
                            </div>
                        </li>
                    @endforeach
                    <div class="flex justify-between items-center text-xl font-bold text-gray-800 mt-4 border-t-2 p-4">
                        <span>Total:</span>
                        <span>{!! dollarSign() !!}{{ number_format($cart->items->sum(fn($item) => $item->price_at_add * $item->quantity), 2) }}</span>
                    </div>
                </ol>
            </div>
        </div>

        <form action="{{ route('shop.checkout.email') }}" method="post" class="checkEmailBox col-span-4 p-4 text-md">
            @csrf
            <div class="text-lg mb-2 border-b-2 border-gray-300">Please fill in the below details, you will recieve an email about your order confirmation and all the order details.</div>
            <div class="formElementBox gap-8">
                <div class="formElements flex flex-col">
                    <label class="text-lg font-bold" for="purchaser-name">Purchaser Name:</label>
                    <input type="text" name="name" class="rounded p-2" id="purchaser-name">
                </div>
                
                <div class="formElements flex flex-col">
                    <label class="text-lg font-bold" for="purchaser-email">Purchaser E-mail:</label>
                    <input type="email" name="email" class="rounded p-2" id="purchaser-email">
                </div>
                <div class="formElements flex flex-col">
                    <label class="text-lg font-bold" for="purchaser-phone">Phone:</label>
                    <input type="tel" name="phone" class="rounded p-2" id="purchaser-phone">
                </div>
                {{-- Submit Button --}}
                <div class="submitBox w-full py-2">
                    <button type="submit" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out">Checkout</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection