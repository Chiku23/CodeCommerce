@extends('shop.layouts.app')

@section('content')

<div class="loginPage flex h-full">
    <div class="loginWrapper flex flex-col w-full sm:w-1/2 mx-auto justify-center h-full">
        <form method="post" action="{{ url("/loginuser") }}" class="loginBox flex flex-col gap-2 w-1/2 mx-auto border-2 p-4 rounded bg-white shadow-lg">
            @csrf
            <h2 class="text-center text-2xl font-bold">Login</h2>
            <label for="email">E-Mail:</label>
            <input type="email" name="email" id="userEmail">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">

            <button type="submit" class="text-white font-bold border border-2 border-white bg-green-500 p-2">Login</button>
            <div class="text-center"><a href="{{ Route('register') }}">Register</a></div>
        </form>
    </div>
</div>

@endsection
