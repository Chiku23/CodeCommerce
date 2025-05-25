@extends('admin.dashboard.dashboard')

@section('dashboard-content')
@php
    $arrBrands = $brands->toArray();
@endphp
<div class="admin-UsersList">
    All Brands
    @if (!empty($arrBrands))
        <div class="w-full grid grid-cols-12">
            @foreach ($arrBrands as $brand)
                <div class="col-span-3 border-2 border-gray-400 m-4 p-4 text-center">{{ $brand['name'] }}</div>
            @endforeach
        </div>
    @endif
</div>

@endsection