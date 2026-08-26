<a href="{{ route('front.home') }}" class="logo" >
    <img src="{{ asset('front-assets/images/logo.png') }}" alt="Business">
</a>

<div class="collapse navbar-collapse d-none d-lg-block" id="mainNavbar">
    <ul class="navbar-nav">
        @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $item1)
                <li class="nav-item dropdown position-static">
                    <a href="{{ route('front.category', [$item1->category_slug]) }}" class="nav-link dropdown-toggle {{ request()->segment(2) == $item1->category_slug ? 'menu-active' : '' }}">
                        {{ $item1->category_name }}
                    </a>

                    @if ($item1->subCategories->isNotEmpty())														
                        <ul class="dropdown-menu">
                            <div class="container">
                                <div class="row">
                                    @foreach ($item1->subCategories as $item2)
                                        @if ($item2->subSubCategories->isNotEmpty())																	
                                            <div class="col-md-2 col-12">
                                                <ul>
                                                    <li class="dropdown-header">
                                                        <a href="{{ route('front.shop', [$item1->category_slug, $item2->sub_category_slug]) }}" class="{{ request()->segment(3) == $item2->sub_category_slug ? 'sub-menu-active' : '' }}">
                                                            {{ $item2->sub_category_name }}
                                                        </a>
                                                    </li>                                                                
                                                    @foreach ($item2->subSubCategories as $item3)
                                                        <li>                                                                            
                                                            <a class="dropdown-item {{ request()->segment(4) == $item3->sub_sub_category_slug ? 'sub-menu-active' : '' }}" href="{{ route('front.shop', [$item1->category_slug, $item2->sub_category_slug, $item3->sub_sub_category_slug]) }}">
                                                                {{ $item3->sub_sub_category_name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('front.shop', [$item1->slug, $item2->slug]) }}" title="{{ $item2->slug }}">
                                                    {{ $item2->name }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</div>