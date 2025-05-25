<header class="bg-background border-b">
    <div class="flex md:flex-row flex-col uppercase w-full sm:justify-between max-w-1200 mx-auto sm:min-h-28 font-bold">
        <div class="leftCol flex xl:w-1/6 capitalize text-3xl p-2 text-center items-center">
            <div class="w-full"><a href="{{ Route('home') }}">Code Commerce</a></div>
        </div>
        <div class="rightCol navbar xl:w-5/6 items-center p-2 flex flex-grow justify-end">
            <div class="">Menus</div>
            <span class="cartIcon h-24 w-24 flex items-center justify-center text-4xl" title="My Cart">
                <i class="fa-solid fa-cart-shopping cursor-pointer relative">
                    <span class="absolute top-[-4px] right-[-4px] h-[20px] w-[20px] flex items-center justify-center rounded-full bg-blue-500 text-white text-xs">
                        3
                    </span>
                </i>
            </span>
        </div>
    </div>
</header>
@include('shop.components.error', ['errors' => $errors, 'successMessage' => session('status')])
