@extends('shop.layouts.app')

@section('content')

<div class="loginPage flex w-full">
    <div class="loginWrapper flex flex-col w-full sm:w-1/2 mx-auto justify-center h-full">
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
        <form method="post" action="{{ url("/shop/loginuser") }}" class="loginBox flex flex-col gap-2 w-1/2 mx-auto border-2 p-4 rounded bg-white shadow-lg">
            @csrf
            <h2 class="text-center text-2xl font-bold">Login</h2>
            <label for="email">E-Mail:</label>
            <input type="email" name="email" id="userEmail" value="{{ old('email') }}">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">

            <button type="submit" class="text-white font-bold border border-2 border-white bg-green-500 p-2">Login</button>
            <div class="text-center"><a href="{{ Route('shop.register') }}">Register</a></div>
        </form>
    </div>
</div>

@endsection
