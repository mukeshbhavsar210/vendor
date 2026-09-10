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
                    <div class="card-custom mt-4">
                        <h4>Cart</h4>                            
                        
                        @foreach($cartContent as $item)
                            <div id="cart-item-{{ $item->rowId }}">                               
                                <div class="details">                                
                                    <p>{{ $item->name }}</p>                                    

                                    <a href="javascript:void(0);" class="update-cart-modal" data-type="qty" data-rowid="{{ $item->rowId }}" data-selected="{{ $item->qty }}">
                                        {{ $item->qty }}
                                    </a>
                                    
                                    <div class="price">
                                        <span class="dark">₹{{ round($item->options->discount_price) }}</span>
                                        @if($item->options->discount_percent)
                                            <span class="mrp">MRP <del>₹{{ $item->options->original_price }}</del></span>    
                                            <span class="discount">({{ $item->options->discount_percent }}% OFF)</span>
                                        @endif
                                    </div>                                                                                                         
                                </div>

                                <div class="remove">                                
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#removeItemModal_{{ $item->id }}" class="delete-icon">
                                        <span class="sprites"></span>
                                    </a>
                                </div>                               
                            </div>
                        @endforeach  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    
@endsection