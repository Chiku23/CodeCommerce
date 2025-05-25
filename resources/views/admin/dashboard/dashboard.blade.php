@extends('admin.app')

@section('content')

@php
    $arrMenuItems = [
        'Products' => Route('admin.products.index'), 
        'Categories' => Route('admin.home'),
        'Brands' => Route('admin.brands.index'), 
        'Orders' => Route('admin.home'), 
        'Users' => Route('admin.users.index'), 
        'Settings' => Route('admin.home'),
        'Product Reviews' => Route('admin.reviews.index')
    ];
    $counts = getAdminCounts();
@endphp


<div class="DashboardMain w-full">
    <div class="ContainerTitle grid grid-cols-12 mx-auto h-full">
        <div class="leftSidebar border-r border-black col-span-1 bg-gray-400 relative">
            <div class="ActionGroup sticky top-12">
                <div class="Actions flex flex-col font-bold">
                    @foreach($arrMenuItems as $menu => $url)
                        <a href="{{ $url }}" class="">
                            <div class="actionItem px-2 py-2 hover:bg-gray-500 flex items-center justify-between">
                                <span class="p-1">{{ $menu }}</span>
                                @if(isset($counts[$menu]) && $counts[$menu] !== null)
                                    <span class="h-[24px] w-[24px] flex items-center justify-center rounded-full bg-red-500 text-white text-sm">
                                        {{ $counts[$menu] }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="RightMain px-4 pt-4 pb-8 col-span-11">
            <div class="DashboardContent">
                @yield('dashboard-content')
            </div> 
        </div>
    </div>
</div>
@endsection