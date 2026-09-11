@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')
    
<div class="container">    
    <div class="row">
        <div class="col-md-4 col-6">
            <h1>{{ $category->category_name }}</h1>
            <div class="card mt-3">
                <div class="card-body">
                    <h5>UC Cover</h5>
                    <p>Upto 30 days warranty on repairs</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5>Select a service</h5>
                    <div class="flex-card">
                        @foreach($categories as $category)
                            @foreach($category->subCategories as $subcategory)
                                <div class="thumb">
                                    <a href="{{ route('front.category', [$subcategory->category_slug]) }}">
                                        <img src="{{ asset('uploads/subcategory/' . $subcategory->image) }}" alt="{{ $subcategory->category_name }}">
                                    </a>
                                    <p>{{ $subcategory->sub_category_name }}</p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8 col-6">
            <div class="gallery-big">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 col-6">
                                <h2>{{ $category->category_name }} in 60 minutes</h2>
                                <h4>Start at {{ $category->price }}</h4>
                            </div>
                            <div class="col-md-8 col-6">                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="services">                        
                <div class="service-listings">
                    @foreach($services as $subCategoryId => $subCategoryServices)
                        @php
                            $firstService = $subCategoryServices->first();
                            $subCategory = $firstService?->subCategory;
                            $category = $firstService?->category;
                        @endphp

                        @if($subCategory)
                            <h2>{{ $subCategory->sub_category_name }}</h2>
                        @endif

                        @if($category)
                            <div class="category-banner">                                
                                <div class="details">
                                    <div class="left">
                                        <p class="label">{{ $category->banner_label }}</p>
                                        <div class="text">
                                            <h3>{{ $category->banner_title }}</h3>
                                            <p>{{ $category->banner_details }}</p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <img src="{{ asset('uploads/category/' . $category->banner_image) }}" alt="{{ $category->banner_title }}">
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>Coming Soon</p>
                        @endif

                        @foreach($subCategoryServices as $value)                            
                                <x-services 
                                    :item="$value"
                                    :category="$category"
                                    :subcategory="$subCategory"
                                    :ratings="$value->ratings"
                                    :brand="$value->brand"
                                    :process="$value->process"
                                    :waranty="$value->waranty"
                                    :include="$value->include"
                                    :need="$value->need"
                                    :faqs="$value->faqs"
                                    :hover="false"                                    
                                    :amount="true"
                                    :title_limit="25"
                                    :short_limit="7"
                                />                            
                        @endforeach
                    @endforeach                                                               
                </div>
                
                <div class="service-right">
                    <div class="card-custom">
                        <h4>UC Promise</h4>
                        <ul>
                            <li>Verified Professionals</li>
                            <li>Hassle Free Booking</li>
                            <li>Transparent Pricing</li>
                        </ul>                        
                    </div>
                    <div class="cart mt-4">
                        <div class="cart-body">
                            @include('front.layouts.toast')

                            @if (Cart::count() > 0)                            
                                <h4>Cart</h4>
                                @foreach($cartContent as $item)
                                    <div class="cart-repeate" id="cart-item-{{ $item->rowId }}">
                                        <div class="item">{{ $item->name }}</div>
                                        <div class="right">                                    
                                            <div class="qty-control">
                                                <button type="button" class="qty-btn qty-minus" data-rowid="{{ $item->rowId }}">−</button>
                                                <span class="cart-qty" id="qty-{{ $item->rowId }}">{{ $item->qty }}</span>
                                                <button type="button" class="qty-btn qty-plus" data-rowid="{{ $item->rowId }}">+</button>
                                            </div>
                                            
                                            <div class="amount">
                                                <p>₹{{ round($item->options->discount_price) }}</p>
                                                @if($item->options->discount_percent)
                                                    <del>₹{{ $item->options->original_price }}</del>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach                                      
                                </div>
                                <div class="cart-footer">
                                    <a href="{{ route('front.cart') }}" class="view-cart">
                                        <div class="amount">
                                            ₹{{ Cart::total() }}                                            
                                        </div>
                                        <div class="view">View Cart</div>
                                    </a>
                                </div>
                            @else
                                <div class="empty-cart">
                                    <svg width="100%" height="100%" viewBox="0 0 128 96" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M77.5 34a.5.5 0 01-.5.5h-2.5V30a.5.5 0 011 0v3.5H77a.5.5 0 01.5.5z" fill="#FFD47F"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M79.5 34a.5.5 0 01-.5.5h-2.5V30a.5.5 0 011 0v3.5H79a.5.5 0 01.5.5z" fill="#FFD47F"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M73 69a1 1 0 011 1v1H61a1 1 0 00-1 1v7h-2v-7a3 3 0 013-3h12zm3 2h9a1 1 0 011 1v7h2v-7a3 3 0 00-3-3h-9.17c.11.313.17.65.17 1v1z" fill="#E2E2E2"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M60 60v10h-2V60h2z" fill="#E2E2E2"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M72 72a1 1 0 00-1-1H47a1 1 0 00-1 1v7h-2v-7a3 3 0 013-3h24a3 3 0 013 3v7h-2v-7z" fill="#E2E2E2"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M74 70v9h-2v-9h2z" fill="#E2E2E2"></path><path d="M50 79a5 5 0 11-10 0 5 5 0 0110 0zM64 79a5 5 0 11-10 0 5 5 0 0110 0zM78 79a5 5 0 11-10 0 5 5 0 0110 0zM92 79a5 5 0 11-10 0 5 5 0 0110 0z" fill="#757575"></path><path d="M48 79a3 3 0 11-6 0 3 3 0 016 0zM62 79a3 3 0 11-6 0 3 3 0 016 0zM76 79a3 3 0 11-6 0 3 3 0 016 0zM90 79a3 3 0 11-6 0 3 3 0 016 0z" fill="#EEE"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M74 60v10h-2V60h2z" fill="#E2E2E2"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M30.832 25.445l8 12-1.664 1.11-8-12 1.664-1.11zm16 0l8 12-1.664 1.11-8-12 1.664-1.11z" fill="#CBCBCB"></path><path d="M44 34h52l-5.694 30.369A2 2 0 0188.34 66H53.32a4 4 0 01-3.932-3.263L44 34z" fill="#CBCBCB"></path><path d="M34 34h48l-6 32H41.66a2 2 0 01-1.966-1.631L34 34z" fill="#E2E2E2"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M46 40h-2v7.059h2V40zm0 12.941h-2V60h2v-7.059zM50 40h2v7.059h-2V40zm2 12.941h-2V60h2v-7.059zM56 40h2v7.059h-2V40zm2 12.941h-2V60h2v-7.059zM62 40h2v7.059h-2V40zm2 12.941h-2V60h2v-7.059zM68 40h2v7.059h-2V40zm2 12.941h-2V60h2v-7.059z" fill="#fff"></path><path d="M24 24h28v4H24v-4z" fill="#97674E"></path><path d="M78 20h6v4a6 6 0 01-6 6V20zM78 15a3 3 0 116 0v5h-6v-5zM78 30V18L66 30h12z" fill="#997BED"></path><path d="M88 16l-4-1v2l4-1z" fill="#FFD47F"></path><path d="M81 15a1 1 0 112 0 1 1 0 01-2 0z" fill="#0F0F0F"></path><path d="M72 30h-6l12-12v6a6 6 0 01-6 6z" fill="#6E42E5"></path></svg>
                                    <p class="mb-3">No items in your cart</p>
                                </div>                            
                            @endif                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    
@endsection