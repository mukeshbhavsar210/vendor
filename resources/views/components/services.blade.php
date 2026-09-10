@props([
    'item' => null,    
    'category' => null,
    'subcategory' => null,
    'wishlistProductIds' => null,
    'class' => null,    
    "variable" => null,    
    'hover' => true,
    'servicetitle' => null,
    'description' => null,    
    'amount' => null,
    "notifyData" => null,
    'gallery' => null,    
    'title_limit' => null
])

@php
    $service = $item->service ?? $item;
    $title = $service->title ?? '';
    $short = $service->short_description ?? '';
    $price = $service->price ?? '';
    $discount_price = $service->discount_price ?? '';
    $discount_percent = $service->discount_percent ?? '';
    $qty = $service->qty ?? '';    
    $single = $service->product_images ? $service->product_images->first() : null;
    $url = $service->url ?? null;    
    $rating = $service->average_rating ?? 0;
    $count  = $service->rating_count;
@endphp

{{ $rating }}

<div class="service-card">           
    {{-- <a href="{{ route('front.shop', [$category->category_slug, $subcategory->sub_category_slug]) }}" >
        @if ($subcategory->image != "")
            <img src="{{ asset('uploads/category/subcategory/'.$subcategory->image) }} " alt="" class="product-img rounded">
        @endif
    </a> --}}

    @if($servicetitle)
        <div class="left">
            <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>
            @if($description)
                <p class="short">{{ isset($short_limit) ? Str::limit($short, $short_limit, '...') : $short }}</p>
            @endif    
            
            @if ($count > 0)
                <div class="rating-wrapper">
                    <small>
                        {{ ${$variable}->average_rating }}
                        <svg fill="#666666" width="15px" height="15px" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1915.918 737.475c-10.955-33.543-42.014-56.131-77.364-56.131h-612.029l-189.063-582.1v-.112C1026.394 65.588 995.335 43 959.984 43c-35.237 0-66.41 22.588-77.365 56.245L693.443 681.344H81.415c-35.35 0-66.41 22.588-77.365 56.131-10.955 33.544.79 70.137 29.478 91.03l495.247 359.831-189.177 582.212c-10.955 33.657 1.13 70.25 29.817 90.918 14.23 10.278 30.946 15.487 47.66 15.487 16.716 0 33.432-5.21 47.775-15.6l495.134-359.718 495.021 359.718c28.574 20.781 67.087 20.781 95.662.113 28.687-20.668 40.658-57.261 29.703-91.03l-189.176-582.1 495.36-359.83c28.574-20.894 40.433-57.487 29.364-91.03" fill-rule="evenodd"/>
                        </svg> 
                        <small>{{ ${$variable}->rating_count ?? 0 }}</small>
                    </small>
                </div>
            @endif

            @if($amount)
                @php
                    $discount = $service->discounts->first();
                    $discount_percent = $discount?->discountPercentage?->percentage ?? 0;
                    $discount_price = $price - ($price * $discount_percent / 100);
                @endphp

                <div class="price">
                    @if($discount_percent > 0)
                        @php
                            $total_price = $item->qty * round($discount_price);
                        @endphp

                        <span class="dark">₹ {{ round($total_price ) }}</span>
                        <span class="mrp"><del>₹{{ $price }}</del></span>  
                        {{-- <span class="discount">({{ $discount_percent }}% OFF)</span> --}}
                    @else
                        <span class="dark">₹{{ $price }}</span>
                    @endif
                    {{ $item->time }}
                </div>

                <p>{{ $item->price }} per AC</p>
            @endif    

            <div class="text-details">
                <p>Applicable for both window or split ACs</p>
            </div>
            
            {{-- <h2>{{ Str::limit($subcategory->sub_category_name, 27, '...') }}</h2> --}}
            <p class="text-muted tiny-font"><b>{{ $item->products_count }} Products</b></p>

            <a href="javascript:0" class="view" data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">View Details</a>

            <div class="modal fade " id="service_{{ $item->id }}" tabindex="-1" aria-labelledby="serviceLabel_{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-custom">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-header">
                            <h2>{{ $item->title }}</h2>                            
                        </div>
                        <div class="modal-body">
                            <a class="btn btn-primary add-to-cart-btn" onclick="addToCart({{ $item->id }}, this)">Add</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    @endif    

    <div class="right">
        <a data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">
           <img src="{{ $single ? asset('uploads/services/small/'.$single->image) : asset('admin-assets/img/default-150x150.png') }}" class="img-fluid rounded" alt="{{ $item->title }}" />
           <p>Add</p>
        </a>               
    </div> 
        
    @if($hover)        
        @if($section == 'show_products')
            @php
                $isInWishlist = in_array(${$variable}->id, $wishlistProductIds);
            @endphp                    

            @if (Auth::check())
                <a onclick="addToWishlist({{ ${$variable}->id }})" class="btn {{ $isInWishlist ? 'btn-primary' : 'btn-outline' }}" href="javascript:void(0)">
                    <span class="sprites {{ $isInWishlist ? 'added-wishlist-ico-btn' : 'wishlist-ico-btn' }} "></span>
                    {{ $isInWishlist ? 'Added' : 'Wishlist' }}
                </a>              
            @else
                <a href="{{ route('account.login') }}" class="btn btn-outline-dark retirectBack" data-product-id="{{ ${$variable}->id }}">
                    <span class="sprites wishlist-ico-btn"></span>
                    Wishlist
                </a>                          
            @endif                                                      

        @elseif($section == 'show_wishlist')
            @if ($qty < 1)
                <button onclick="notifyMe({{ $item->product->id }})" class="btn btn-outline-primary">
                    Notify Me
                </button>
            @else
                <button class="btn btn-outline-danger btn-sm move-to-cart" data-wishlist-id="{{ $item->id }}" data-product-id="{{ $product->id }}" type="button">
                    Move to Bag 
                </button>                                     
            @endif                        
        @endif        
    @endif    
</div>