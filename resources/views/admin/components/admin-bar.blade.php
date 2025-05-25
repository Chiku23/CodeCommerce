@php
    $iconClass = "text-black flex justify-center items-center h-full px-3 me-2 hover:bg-gray-500";
@endphp

<div class="flex justify-between adminBar w-full min-h-12 bg-gray-400 border-b border-black sticky top-0 z-40">
    <div class="leftcol">
        <a href="{{ Route('home') }}" class="{{ $iconClass }}"><i class="fa-solid fa-shop text-2xl"></i>  <strong>Go To Shop</strong></a>
        
    </div>
    <div class="actions">

    </div>
    <div class="rightcol flex justify-center items-center">
        <a href="#" class="{{ $iconClass }}"><i class="fa-solid fa-user-tie text-2xl"></i></a>
    </div>
</div>