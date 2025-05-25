@extends('shop.layouts.app')

@section('content')

<div class="registerPage flex h-full">
    <div class="registerWrapper flex flex-col w-full sm:w-1/2 mx-auto justify-center h-full">
        <div class="registerBox flex flex-col gap-2 w-1/2 mx-auto border-2 p-4 rounded bg-white shadow-lg">
            <h2 class="text-center text-2xl font-bold">Register</h2>
            <label for="name">username:</label>
            <input type="text" name="name" id="userName">
            <label for="userEmail">E-Mail:</label>
            <input type="email" name="email" id="userEmail">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirmPassword">

            <button type="submit" class="text-white font-bold border border-2 border-white bg-green-500 p-2">Register</button>
            <div class="text-center"><a href="{{ Route('login') }}">login</a></div>
        </div>
    </div>
</div>

@endsection
