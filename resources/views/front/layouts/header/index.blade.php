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
                                    <x-common-modal 
                                        modalId="loginModal"
                                        modalName="login-modal" 
                                        title="Login / Signup"
                                        button="Login"
                                    >
                                    </x-common-modal>

                                    <a href="#" class="link user-icon" data-bs-toggle="modal" data-bs-target="#loginModal">
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

<div class="menu-overlay"></div>