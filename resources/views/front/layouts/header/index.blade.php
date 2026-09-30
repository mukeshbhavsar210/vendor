<header class="header">    
    <div class="container">
        <div class="row row-hide">
            <nav class="navbar navbar-expand-lg">							
                <div class="col-md-5 col-6">
                    <div class="logo-controls">                
                        @if(request()->routeIs(['front.home']))
                            <div class="d-block d-md-none">
                                <a href="javascript:0" class="navbar-toggler mobile-menu-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                                    <span class="sprites"></span>
                                </a>
                            </div>
                        @else
                            <a href="{{ url()->previous() }}" class="navbar-toggler mobile-back-icon">
                                <span class="sprites"></span>
                            </a>
                        @endif

                        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileMenu">
                            <div class="offcanvas-body">                            
                                <ul class="navbar-nav">
                                    @if (getCategories()->isNotEmpty())
                                        @foreach (getCategories() as $item1)
                                            <li class="nav-item">
                                                <a href="javascript:void(0);" class="nav-link toggle-category" data-target="#cat-{{ $item1->id }}">
                                                    {{ $item1->category_name }}
                                                    <span class="sprites mobile-arrow-1 float-end"></span>
                                                </a>                                            

                                                @if ($item1->subCategories->isNotEmpty())														
                                                    <ul class="mobile-dropdown" id="cat-{{ $item1->id }}">                                                    
                                                        @foreach ($item1->subCategories as $item2)
                                                            @if ($item2->subSubCategories->isNotEmpty())
                                                                <li>    
                                                                    <a href="javascript:void(0);" class="dropdown-item toggle-subcategory"  data-target="#subcat-{{ $item2->id }}">
                                                                        {{ $item2->sub_category_name }}
                                                                        <span class="sprites mobile-arrow-1 float-end"></span>
                                                                    </a>
                                                                    {{-- <a href="{{ route('front.shop', [$item1->category_slug, $item2->sub_category_slug]) }}" data-target="#subcat-{{ $item2->id }}">
                                                                        {{ $item2->sub_category_name }}
                                                                        <span class="float-end">▶</span>
                                                                    </a>                                 --}}
                                                                    
                                                                    @if ($item2->subSubCategories->isNotEmpty())
                                                                        <ul class="sub-dropdown" id="subcat-{{ $item2->id }}">
                                                                            @foreach ($item2->subSubCategories as $item3)
                                                                                <li>
                                                                                    <a class="dropdown-item"  href="{{ route('front.shop', [$item1->category_slug, $item2->sub_category_slug, $item3->sub_sub_category_slug]) }}">
                                                                                        {{ $item3->sub_sub_category_name }}
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('front.shop', [$item1->slug, $item2->slug]) }}" title="{{ $item2->slug }}">
                                                                        {{ $item2->name }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach                                                        
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="accountDetails">   
                            <div class="offcanvas-body">                                    
                                @if (Auth::check())
                                    <p><b>Hello {{ Auth::user()->name }}</b></p>
                                    {{ Auth::user()->phone }}            
                                @else
                                    <p><b>Welcome</b></p>
                                    <p class="text-muted tiny-font">To access account and manage orders</p>
                                    <a class="btn btn-primary mt-2 retirectBack" href="#" >Login / Signup</a>
                                @endif

                                <hr />

                                <ul class="navbar-listings">
                                    @if (Auth::check())  
                                        <li><a href="{{ route('account.dashboard') }}" class="{{ request()->routeIs(['account.dashboard', 'account.orderDetail', 'account.order.view', 'account.orders.cancelled']) ? 'active' : '' }}">Dashboard</a></li>
                                        <li><a href="{{ route('account.orders') }}" class="{{ request()->routeIs(['account.orders', 'account.orderDetail', 'account.order.view', 'account.orders.cancelled']) ? 'active' : '' }}">Orders</a></li>
                                        <li><a href="{{ route('account.wishlist') }}" class="{{ request()->routeIs(['account.wishlist']) ? 'active' : '' }}">Wishlist</a></li>
                                        <li><a href="{{ route('account.wishlist') }}" class="{{ request()->routeIs(['account.wishlist']) ? 'active' : '' }}">Coupons</a></li>
                                        <li><a href="{{ route('account.cards') }}" class="{{ request()->routeIs(['account.cards']) ? 'active' : '' }}">Saved Cards</a></li>
                                        <li><a href="{{ route('account.address') }}" class="{{ request()->routeIs(['account.address']) ? 'active' : '' }}">Saved Address</a></li>

                                        <hr />
                                        <li><a href="{{ route('account.profile') }}" class="{{ request()->routeIs(['account.profile', 'account.profile.edit', 'account.changePassword']) ? 'active' : '' }}">Edit Profile</a></li>
                                        <li><a href="{{ route('account.logout') }}">Logout</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <a href="{{ route('front.home') }}" class="logo" >
                            <img src="{{ asset('front-assets/images/logo.png') }}" alt="Business">
                        </a>

                        <div class="collapse navbar-collapse d-none d-lg-block" id="mainNavbar">
                            <ul class="navbar-nav">
                                <li><a href="#">Homes</a></li>
                                <li><a href="#">Native</a></li>
                                <li><a href="#">Beauty</a></li>
                            </ul>
                        </div>
                    </div>
                </div>        
                <div class="col-md-7 col-6">
                    <div class="search-controls">

                        <div class="header-search desktop-form d-none d-md-block">
                            <div class="search-control">
                                <span class="sprites search-icon"></span> 
                                <input type="text" id="headerSearch" class="form-control" placeholder="Search for services..." autocomplete="off">
                                 <button type="button" id="clearSearch" class="search-back" style="display: none;">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12zm6.293 2.293L10.586 12 8.293 9.707l1.414-1.414L12 10.586l2.293-2.293 1.414 1.414L13.414 12l2.293 2.293-1.414 1.414L12 13.414l-2.293 2.293-1.414-1.414z"
                                            fill="#545454">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div id="searchDropdown" class="search-dropdown">
                                <h2>Trending searches</h2>
                                <ul class="search-content" id="searchContent">                                    
                                </ul>
                            </div>
                        </div>

                        {{-- <form class="search-form desktop-form d-none d-md-block" action="{{ route('front.shop') }}">
                            <div class="search-control">
                                <span class="sprites search-icon"></span> 
                                <input value="{{ Request::get('search') }}" type="text" placeholder="Search for products, brands and more" class="form-control" name="search" id="search">
                            </div>
                        </form> --}}
                        
                        <ul class="icon-controls">  
                            <li class="item">
                                <a href="{{ route('front.cart') }}" class="link bag-icon">
                                    <span class="sprites"></span>                                 

                                    <span id="cartCount" class="card-count">
                                        {{ Cart::count() }}
                                    </span>					                                                                                   
                                </a>
                            </li>                      
                            <li class="item d-block d-md-none">
                                @if (Auth::check())                                                                               
                                    <a href="javascript:0" type="button" data-bs-toggle="offcanvas" data-bs-target="#accountDetails">                                                                  
                                        @if (!empty(Auth::user()->image))                        
                                            <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" class="profile-pic">
                                        @else                            
                                            @php
                                                $name = Auth::user()->name;
                                                $words = explode(' ', $name);
                                                $initials = '';
                                                foreach ($words as $word) {
                                                    $initials .= strtoupper(substr($word, 0, 1));
                                                }
                                            @endphp
                                            <div class="avatar" style="background-color: {{ Auth::user()->avatar_color ?? '#777' }};">
                                                {{ $initials }}
                                            </div>                                            
                                        @endif                                                                              
                                    </a>    
                                @else
                                    <a href="{{ route('account.login') }}" class="link user-icon">
                                        <span class="sprites"></span>                                        
                                    </a>                        
                                @endif
                            </li>
                            <li class="item d-block d-md-none">
                                <a href="javascript:0" class="search-btn search-icon">
                                    <span class="sprites"></span>
                                </a>
                            </li>
                            <li class="item d-none d-md-block">       
                                @if (Auth::check())
                                    <a href="{{ route('account.profile') }}" class="link user-link">
                                        @if (!empty(Auth::user()->image))                        
                                            <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" class="profile-pic">
                                        @else                            
                                            @php
                                                $name = Auth::user()->name;
                                                $words = explode(' ', $name);
                                                $initials = '';
                                                foreach ($words as $word) {
                                                    $initials .= strtoupper(substr($word, 0, 1));
                                                }
                                            @endphp
                                            <div class="avatar" style="background-color: {{ Auth::user()->avatar_color ?? '#777' }};">
                                                {{ $initials }}
                                            </div>                                            
                                        @endif                                                                              
                                    </a>
                                    
                                    <div class="hover-parent">
                                        <div class="hover-content">
                                            @if (Auth::check())
                                                <p><b>Hello {{ Auth::user()->name }}</b><br />
                                                    {{ Auth::user()->phone }}
                                                </p>
                                                <a href="{{ route('account.profile')}}" class="btn btn-outline-primary btn-sm mt-2">My Account</a>
                                                <hr />                                            
                                            @endif

                                            @php
                                                $guestAttr = 'data-bs-toggle=modal data-bs-target=#login href=javascript:void(0)';
                                            @endphp

                                            <ul class="navbar-listings">
                                                <li>
                                                    <a class="{{ request()->routeIs(['account.dashboard', 'account.orderDetail', 'account.order.view', 'account.orders.cancelled']) ? 'active' : '' }}"
                                                        @if(Auth::check()) href="{{ route('account.dashboard') }}" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Dashboard
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="{{ request()->routeIs(['account.orders', 'account.orderDetail', 'account.order.view', 'account.orders.cancelled']) ? 'active' : '' }}"
                                                        @if(Auth::check()) href="{{ route('account.orders') }}" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Orders
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="{{ request()->routeIs(['account.wishlist']) ? 'active' : '' }}"
                                                        @if(Auth::check()) 
                                                        href="{{ route('account.wishlist') }}" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Wishlist
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="{{ request()->routeIs(['account.deals']) ? 'active' : '' }}"
                                                        @if(Auth::check()) 
                                                        href="{{ route('account.deals') }}" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Deals
                                                    </a>
                                                </li>
                                                <li>
                                                    <a 
                                                        @if(Auth::check()) 
                                                        href="" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Coupons
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="{{ request()->routeIs('account.cards') ? 'active' : '' }}"
                                                        @if(Auth::check()) 
                                                        href="" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Saved Cards
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="{{ request()->routeIs('account.address') ? 'active' : '' }}" 
                                                        @if(Auth::check()) 
                                                        href="{{ route('account.address') }}" 
                                                        @else 
                                                        {!! $guestAttr !!} 
                                                        @endif
                                                        >Saved Address
                                                    </a>
                                                </li> 
                                                @if (Auth::check())
                                                    <hr />
                                                    <li><a href="{{ route('account.profile') }}" class="{{ request()->routeIs(['account.profile', 'account.profile.edit', 'account.changePassword']) ? 'active' : '' }}">Edit Profile</a></li>
                                                    <li><a href="{{ route('account.logout') }}">Logout</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @else                                        
                                    <a href="#" class="link user-icon" data-bs-toggle="modal" data-bs-target="#modal_login">
                                        <span class="sprites"></span>                                        
                                    </a>
                                @endif                                                                
                            </li>
                            {{-- <li class="item">
                                @if (Auth::check())
                                    <a href="{{ route('account.wishlist') }}" class="link wishlist-icon">
                                        <span class="sprites"></span>                                                                            
                                    </a>                                                                   
                                @else
                                    <a href="{{ route('account.login') }}" class="link wishlist-icon">
                                        <span class="sprites"></span>                                    
                                    </a>
                                @endif                            
                            </li> --}}                            
                        </ul>
                    </div> 
                </div>        
            </nav>
        </div>							    

        <form class="search-form bottom-form d-none" action="{{ route('front.shop') }}">
            <div class="search-control">
                <span class="sprites search-icon"></span> 
                <input value="{{ Request::get('search') }}" type="text" placeholder="Search for products, brands and more" class="form-control" name="search" id="search">            
                <a href="javascript:0" class="close-search-icon d-none">
                    <span class="sprites"></span> 
                </a>
            </div>
        </form>

        <div class="modal fade" id="modal_login" tabindex="-1" aria-labelledby="categoryLabel_login" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-custom">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                    <div class="modal-body">
                        @include('front/layouts/message')    
                        <div class="login-form">
                            <h4 class="modal-title">Login / Signup</h4>
                            <p class="tiny-font">Join us now to be a part of {{ config('app.name') }} family.</p>

                            <form action="{{ route('account.authenticate') }}" method="post" class="mt-4" >
                                @csrf                        
                                <div class="form-group">
                                    <input type="text" class="form-control floating-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                    <label class="floating-label">Email</label>
                                    @error('email')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control floating-input @error('password') is-invalid @enderror" name="password" >
                                    <label class="floating-label">Password</label>
                                    @error('password')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="flex-end">
                                    {{-- <a href="#" class="forgot-link mt-3">Forgot Password?</a> --}}
                                    <p class="mt-2">Don't have an account? <a href="{{ route('account.register') }}" ><b>Sign up</b></a></p>
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                            
                            <div class="social-btns">
                                <p class="or">OR</p>
                                <div class="flex">                            
                                    <a href="{{ url('auth/google') }}" class="btn btn-outline-dark w-50">
                                        {{-- <span class="sprites"></span> --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-google_svg__a)"><path fill="#4285F4" d="M15.844 8.184c0-.544-.044-1.09-.138-1.625H8.16v3.08h4.321a3.703 3.703 0 0 1-1.6 2.431v2h2.579c1.514-1.394 2.384-3.452 2.384-5.886Z"></path><path fill="#34A853" d="M8.16 16c2.158 0 3.977-.708 5.303-1.93l-2.578-2c-.717.488-1.643.765-2.722.765-2.087 0-3.857-1.409-4.492-3.302h-2.66v2.061A8.001 8.001 0 0 0 8.16 16Z"></path><path fill="#FBBC04" d="M3.668 9.534a4.792 4.792 0 0 1 0-3.063V4.41H1.011a8.007 8.007 0 0 0 0 7.184l2.657-2.06Z"></path><path fill="#EA4335" d="M8.16 3.166a4.347 4.347 0 0 1 3.069 1.2l2.284-2.284A7.689 7.689 0 0 0 8.16 0 7.998 7.998 0 0 0 1.011 4.41l2.657 2.06C4.3 4.575 6.073 3.167 8.16 3.167Z"></path></g><defs><clipPath id="login-google_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                        Google
                                    </a>                        
                                    <a href="{{ url('auth/facebook') }}" class="btn btn-outline-dark w-50">
                                        {{-- <span class="sprites"></span> --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-facebook_svg__a)"><path fill="#1877F2" d="M16 8a8 8 0 1 0-9.25 7.903v-5.59H4.719V8H6.75V6.237c0-2.005 1.194-3.112 3.022-3.112.875 0 1.79.156 1.79.156V5.25h-1.008c-.994 0-1.304.617-1.304 1.25V8h2.219l-.355 2.313H9.25v5.59A8.002 8.002 0 0 0 16 8Z"></path><path fill="#fff" d="M11.114 10.313 11.47 8H9.25V6.5c0-.633.31-1.25 1.304-1.25h1.008V3.281s-.915-.156-1.79-.156c-1.828 0-3.022 1.107-3.022 3.112V8H4.719v2.313H6.75v5.59c.828.13 1.672.13 2.5 0v-5.59h1.864Z"></path></g><defs><clipPath id="login-facebook_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                        Facebook
                                    </a>                                             
                                </div>          
                            </div>

                            <p class="mt-3 tiny-font">By creating an account or logging in, you agree with {{ config('app.name') }} T&C and Privacy Policy</p>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="menu-overlay"></div>