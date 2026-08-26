@extends('layouts.app')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <div class="lg:col-span-5">
        <h1>Home services at your doorstep</h1>

        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm mt-5">
            <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-y-6 gap-x-4">
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_01.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_02.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_03.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_04.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_05.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
                <div class="flex flex-col">
                    <dd><img src="front-assets/images/icons/icon_06.jpeg" alt="" ></dd>
                    <dt class="text-xs tracking-wider text-gray-500 font-medium mt-2">Women's Salon & Spa</dt>                    
                </div>
            </div>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 snap-x [&::-webkit-scrollbar]:hidden mt-5">
            <div class="flex-shrink-0 w-[200px] p-5 transition-all duration-300 snap-start cursor-pointer hover:border-blue-200 group block">
                <div>4.8
                    Service Rating
                </div>
            </div>
            <div class="flex-shrink-0 w-[200px] transition-all duration-300 snap-start cursor-pointer hover:border-blue-200 group block">
                <div>2</div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-7">1</div>
</div>

<div class="css-175oi2r r-1awozwy r-ndvcnb r-aci1zz r-6koalj r-eqz5dr r-p1pxzi r-1mnahxq r-1mdbw0j r-gy4na3 r-9aemit r-wk8lta r-13qz1uu" id="requestStateFooter">
    <div class="css-175oi2r" style="max-width:100%;width:100%;opacity:1">
    <div style="display:flex;flex-direction:column;height:100%;width:100%;min-height:inherit">
        <div class="css-175oi2r r-gtdqiz r-ipm5af r-mhe3cw">
        <div class="css-175oi2r r-q3muym r-109y4c4 r-1d2f490 r-zchlnj r-ipm5af r-mhe3cw" style="position:fixed;opacity:0"></div>
        </div>
    </div>
    </div>
</div>
                      
@if(!auth()->check())
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input
            type="email"
            name="email"
            placeholder="Email"
            value="{{ old('email') }}"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <label>
            <input type="checkbox" name="remember">
            Remember me
        </label>

        <button type="submit">
            Login
        </button>
    </form>
@endif

@endsection