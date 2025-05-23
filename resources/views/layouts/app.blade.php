<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{asset('css/tailwind.css')}}">
    <title>{{config('app.name')}}</title>
    @stack('styles')
</head>
<body class="bg-foreground text-black flex flex-col min-h-screen">
    {{-- Include Header --}}
    @if(!Request::routeIs('login') && !Request::routeIs('register'))
        @include('includes.header')
    @endif

    @if(!Request::routeIs('login') && !Request::routeIs('register'))
        <div class="flex flex-grow max-w-1200 mx-auto w-full">
    @else
        <div class="flex flex-col max-w-1200 mx-auto w-full h-dvh">
    @endif
        @yield('content')
    </div>

    {{-- Include Footer --}}
    @if(!Request::routeIs('login') && !Request::routeIs('register'))
        @include('includes.footer')
    @endif

    {{-- Include Custom Js Scripts --}}
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>