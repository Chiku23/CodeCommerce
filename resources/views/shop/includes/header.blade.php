<header class="bg-primary border-b border-black sticky top-0 z-[999]">
    <div class="flex md:flex-row flex-col w-full sm:justify-between max-w-1200 mx-auto sm:min-h-28 font-bold">
        <div class="leftCol flex capitalize text-3xl p-2 text-center items-center">
            <div class="w-full border-r-2 border-gray-500 h-full p-4 items-center flex"><a href="{{ route('home') }}">Code Commerce</a></div>
        </div>
        <div class="rightCol navbar items-center p-2 flex flex-grow justify-end">
            <div class="border-r border-2 border-gray-500 h-full"></div>
            <span class="px-4 capitalize">{{ session('user')? 'Hello '.session('user')['name'] : '' }}</span>
            <div class="iconMenus flex gap-8">
                @if (Auth::check())
                    <a href="{{ route('shop.user') }}" class="mx-2 flex items-center justify-center text-4xl" title="Profile">
                        <i class="fa-solid fa-user text-blue-500"></i>
                    </a>
                    <a href="{{ route('shop.logout') }}" class="mx-2 flex items-center justify-center text-4xl" title="Log Out">
                        <i class="fa-solid fa-right-from-bracket text-red-500"></i>
                    </a>
                @else
                    <a href="{{ route('shop.login') }}" class="mx-2 flex items-center justify-center text-4xl" title="Login">
                        <i class="fa-solid fa-right-to-bracket text-blue-500"></i>
                    </a>
                @endif
            </div>
            <span class="favoritesIcon mx-2 flex items-center justify-center text-4xl" title="My Favorites">
                <i class="fa-solid text-red-500 fa-heart cursor-pointer relative">
                    <span class="absolute top-[-8px] right-[-8px] h-[20px] w-[20px] flex items-center justify-center rounded-full bg-blue-500 text-white text-xs">
                        3
                    </span>    
                </i>
            </span>
            <a href="{{ route('shop.cart') }}" class="cartIcon mx-2 flex items-center justify-center text-4xl" title="My Cart">
                <i class="fa-solid fa-cart-shopping cursor-pointer relative">
                    <span id="cart-count" class="absolute top-[-8px] right-[-8px] h-[20px] w-[20px] flex items-center justify-center rounded-full bg-blue-500 text-white text-xs">
                        {{ getCartCount() }}
                    </span>
                </i>
            </a>
        </div>
    </div>
</header>
@if (!Request::routeIs(['shop.login', 'shop.register']))
    @include('shop.components.error', ['errors' => $errors, 'successMessage' => session('status')])
@endif