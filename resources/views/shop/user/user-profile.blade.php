@extends('shop.layouts.app')

@php
$arrUser = $user->toArray();

@endphp

@section('content')
<div class="UserProfilePage flex flex-col w-full p-4 md:p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Hello {{ $arrUser['name'] }}</h1>
    <div class="userInformation w-1/2 flex self-start flex-col">
        <div class="userName grid grid-cols-12 p-4">
            <p class="text-md font-bold col-span-4">Name:</p>
            <p class="col-span-8 px-2 border-b-2 text-sm">{{ $arrUser['name'] }}</p>
        </div>
        <div class="userEmail grid grid-cols-12 p-4">
            <p class="text-md font-bold col-span-4">Email:</p>
            <p class="col-span-8 px-2 border-b-2 text-sm">{{ $arrUser['email'] }}</p>
        </div>
        <div class="userPhone grid grid-cols-12 p-4">
            <p class="text-md font-bold col-span-4">Phone:</p>
            <p class="col-span-8 px-2 border-b-2 text-sm">{{ $arrUser['phone'] }}</p>
        </div>
    </div>
</div>

@endsection