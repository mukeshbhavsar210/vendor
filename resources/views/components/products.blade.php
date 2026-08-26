@props([
    'item' => null,    
    'category' => null,
    'subcategory' => null,
    'wishlistProductIds' => null,
    'class' => null,
    "section" => null,
    "variable" => null,    
    'hover' => true,
    'producttitle' => null,
    'description' => null,    
    'amount' => null,
    "notifyData" => null,
    'gallery' => null,
    'class' => null,      
    'title_limit' => null
])

@php
    $product = $item->product ?? $item;
    $title = $product->title ?? '';
    $short = $product->short_description ?? '';
    $price = $product->price ?? '';
    $discount_price = $product->discount_price ?? '';
    $discount_percent = $product->discount_percent ?? '';
    $qty = $product->qty ?? '';
    $images = $product->images ?? collect();
    $single = $product->product_images ? $product->product_images->first() : null;
    $url = $product->url ?? null;
    $affiliate_product_image = $product->image ?? null;
    $affiliate_url = $product->affiliate_url ?? null;
    $rating = $product->average_rating ?? 0;
    $count  = $product->rating_count ?? 0;
@endphp

<div class="product-card {{ $class }}">
    <div class="product-image-wrapper">
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

        @if($gallery == 'yes')
            <div class="product-slider">
                @if($images->count() > 0)
                    @foreach($images as $image)
                        <div class="slider-item">
                            @if($url)
                                <a href="{{ $url }}" target="_blank" title="{{ $title }}">
                            @endif
                                <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="rounded" alt="{{ $title }}">
                            @if($url)
                                </a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="rounded">
                @endif
            </div>
            
        @elseif($gallery == 'no')
            <a href="{{ $url }}" class="product-img" target="_blank">
                <img src="{{ $single ? asset('uploads/product/small/'.$single->image) : asset('admin-assets/img/default-150x150.png') }}" 
                    class="img-fluid rounded" alt="{{ $product->title }}" />
            </a>

        @elseif($gallery == 'affiliate_image')
            <a href="{{ $affiliate_url }}" class="product-img" target="_blank">
                @if(!empty($affiliate_product_image))
                    <img src="{{ asset('uploads/affiliate_products/'.$affiliate_product_image) }}" class="rounded">
                @else
                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="rounded">
                @endif
            </a>

        @elseif($gallery == 'category')  
            <a href="{{ route('front.shop', [$category->category_slug, $subcategory->sub_category_slug]) }}" >
                @if ($subcategory->image != "")
                    <img src="{{ asset('uploads/category/subcategory/'.$subcategory->image) }} " alt="" class="product-img rounded">
                @endif
            </a>

            <div class="product-info">
                <h2>{{ Str::limit($subcategory->sub_category_name, 27, '...') }}</h2>                                
                <p class="text-muted tiny-font"><b>{{ $item->products_count }} Products</b></p>
            </div>      

        @elseif($gallery == 'brand')
            <a href="{{ route('front.shop') }}?brand={{ $item->slug }}" class="brand-details">
                <div class="photo">
                    <img src="{{ asset('uploads/brands/' . $item->logo) }}" class="logo">
                    <img src="{{ asset('uploads/brands/' . $item->model) }}" class="model">
                </div>
                <div class="details">
                    <p class="title">{{ $item->description }}</p>
                    <p class="discount">{{ $item->discount }}</p>
                </div>
            </a>
        @endif

        @if($producttitle)
            <div class="product-info">            
                <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>
                @if($description)
                    <p class="short">{{ isset($short_limit) ? Str::limit($short, $short_limit, '...') : $short }}</p>
                @endif
                
                @if($section == 'show_notify')
                    <p>                 
                        @if($product->qty > 0)
                            <span class="badge bg-success">Back in Stock</span>
                        @else
                            <span class="badge bg-warning">Waiting</span>
                        @endif
                        
                        {{-- @if($notifyData && $notifyData->notified == 1)
                            <div class="text-success small">✔ You were notified</div>
                        @endif --}}
                    </p>
                    
                @elseif($section == 'show_affiliate')
                    <p>                 
                        @if($product->qty > 0)
                            <span class="badge bg-success">Back in Stock</span>
                        @else
                            <span class="badge bg-warning">Waiting</span>
                        @endif
                    </p> 
                @endif                
            </div>
        @endif

        @if($amount)
            <div class="price">
                @if($discount_percent > 0)
                    <span class="dark">₹{{ round($discount_price) }}</span>
                    <span class="mrp"><del>₹{{ $price }}</del></span>  
                    <span class="discount">({{ $discount_percent }}% OFF)</span>
                @else
                    <span class="dark">₹{{ $price }}</span>
                @endif                  
            </div>
        @endif               
            
        @if($hover)
            <div class="hover-product">
                @if($section == 'show_products')
                    @php
                        $isInWishlist = in_array(${$variable}->id, $wishlistProductIds);
                    @endphp                    

                    @if (Auth::check())
                        <a onclick="addToWishlist({{ ${$variable}->id }})" class="btn {{ $isInWishlist ? 'btn-primary' : 'btn-outline' }}" href="javascript:void(0)">
                            <span class="sprites {{ $isInWishlist ? 'added-wishlist-ico-btn' : 'wishlist-ico-btn' }} "></span>
                            {{ $isInWishlist ? 'Added' : 'Wishlist' }}
                        </a>

                        <p class="show-size">
                            @if(optional($product->sizes->first())->code)
                                Size: {{ optional($product->sizes->first())->code ?? 'N/A' }} 
                            @endif

                            @if(optional($product->colors->first())->name)
                                | Color: {{ optional($product->colors->first())->name ?? 'N/A' }}   
                            @endif
                        </p>    
                    @else
                        <a href="{{ route('account.login') }}" class="btn btn-outline-dark retirectBack" data-product-id="{{ ${$variable}->id }}">
                            <span class="sprites wishlist-ico-btn"></span>
                            Wishlist
                        </a>                          
                    @endif

                @elseif($section == 'show_notify')
                    <a href="{{ $url }}" class="btn btn-outline-primary" target="_blank">
                        Buy Now
                    </a> 
                    
                @elseif($section == 'show_affiliate')
                    <a href="{{ $affiliate_url }}" class="btn btn-outline-primary" target="_blank">
                        Buy Now
                    </a> 

                @elseif($section == 'show_wishlist')
                    @if ($qty < 1)
                        <button onclick="notifyMe({{ $item->product->id }})" class="btn btn-outline-primary">
                            Notify Me
                        </button>
                    @else
                        <button 
                            class="btn btn-outline-danger btn-sm move-to-cart"
                            data-wishlist-id="{{ $item->id }}" data-product-id="{{ $product->id }}"
                            data-size-id="{{ optional($product->sizes->first())->id }}"
                            data-color-id="{{ optional($product->colors->first())->id }}" type="button">
                            Move to Bag 
                        </button>                                     
                    @endif
                        <p class="show-size">
                            @if(optional($product->sizes->first())->code)
                                Size: {{ optional($product->sizes->first())->code ?? 'N/A' }}  
                            @endif

                            @if(optional($product->colors->first())->name)
                                | Color: {{ optional($product->colors->first())->name ?? 'N/A' }}   
                            @endif
                        </p>            
                    @endif
                </div> 
        @else
                        
        @endif
    </div>
</div>