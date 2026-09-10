@props([
    'item' => null,    
    'category' => null,
    'subcategory' => null,
    'ratings' => collect(),
    'wishlistProductIds' => null,        
    'hover' => true,        
    'amount' => null,        
    'title_limit' => null,
    'brand' => null,
    'process' => null,
    'waranty' => null,
    'include' => null,
    'need' => null,
    'faqs' => null,
])

@php
    $service = $item->service ?? $item;
    $title = $service->title ?? '';
    $short = $service->short_description ?? '';
    $price = $service->price ?? '';    
    $qty = $service->qty ?? '';    
    $single = $service->image;                    
    //$single = $service->product_images ? $service->product_images->first() : null;
    $url = $service->url ?? null;   
    $discount = $service->discounts->first();
    $discount_percent = $discount?->discountPercentage?->percentage ?? 0;
    $discount_price = $price - ($price * $discount_percent / 100);         
@endphp

<div class="service-card">    
    <div class="left">
        <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>        
        
        @if($ratings->count())
            <div class="ratings">
                <svg class="svg" width="100%" height="100%" viewBox="0 0 20 20" fill="#572AC8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.333 10a8.333 8.333 0 11-16.667 0 8.333 8.333 0 0116.667 0zm-7.894-4.694A.476.476 0 009.999 5a.476.476 0 00-.438.306L8.414 8.191l-2.977.25a.48.48 0 00-.414.342.513.513 0 00.143.532l2.268 2.033-.693 3.039a.51.51 0 00.183.518.458.458 0 00.528.022L10 13.298l2.548 1.629a.458.458 0 00.527-.022.51.51 0 00.184-.518l-.693-3.04 2.268-2.032a.513.513 0 00.143-.532.48.48 0 00-.415-.342l-2.976-.25-1.147-2.885z" fill="#572AC8"></path></svg>
                {{ round($ratings->avg('ratings'), 1) }}
                ({{ $ratings->count() }} reviews)                    
            </div>            
        @endif                       
                
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

        <div class="text-details">
            <p>{{ isset($short_limit) ? Str::limit($short, $short_limit, '...') : $short }}</p>            
        </div>
                            
        <a href="javascript:0" class="view" data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">View Details</a>

        <div class="modal fade " id="service_{{ $item->id }}" tabindex="-1" aria-labelledby="serviceLabel_{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-custom">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                        
                    <div class="modal-scroll">
                        <div class="category-banner m-0">                                
                            <div class="details">
                                <div class="left">                                        
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

                        <div class="modal-body">
                            <div class="modal-body-wrapper">
                                <div class="left">
                                    <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>

                                    @if($ratings->count())
                                        <div class="ratings">
                                            <svg class="svg" width="100%" height="100%" viewBox="0 0 20 20" fill="#572AC8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.333 10a8.333 8.333 0 11-16.667 0 8.333 8.333 0 0116.667 0zm-7.894-4.694A.476.476 0 009.999 5a.476.476 0 00-.438.306L8.414 8.191l-2.977.25a.48.48 0 00-.414.342.513.513 0 00.143.532l2.268 2.033-.693 3.039a.51.51 0 00.183.518.458.458 0 00.528.022L10 13.298l2.548 1.629a.458.458 0 00.527-.022.51.51 0 00.184-.518l-.693-3.04 2.268-2.032a.513.513 0 00.143-.532.48.48 0 00-.415-.342l-2.976-.25-1.147-2.885z" fill="#572AC8"></path></svg>
                                            {{ round($ratings->avg('ratings'), 1) }}
                                            ({{ $ratings->count() }} reviews)                    
                                        </div>            
                                    @endif                       
                                            
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
                                </div>

                                <div class="right">
                                    <a class="btn btn-primary add-to-cart-btn" onclick="addToCart({{ $item->id }}, this)">Add</a>
                                </div>
                            </div>
                        
                            @if($process)
                                <div class="sections">
                                    <h4 class="mb-3">{{ $process->title }}</h4>
                                    
                                    @if(!empty($process->details))                            
                                        <ol class="process-list">
                                            @foreach($process->details as $detail)                                            
                                                <li class="process-item">
                                                    <h5>{{ $detail['name'] }}</h5>
                                                    <p>{{ $detail['description'] }}</p>
                                                    
                                                    @if(!empty($detail['image']))
                                                        <div class="process-image">
                                                            <img src="{{ asset('uploads/process/' . $detail['image']) }}" alt="{{ $detail['name'] ?? '' }}">
                                                        </div>
                                                    @endif
                                                </li>                                            
                                            @endforeach
                                        </ol>
                                    @endif
                                </div>
                            @endif
                            
                            @if($brand)
                                <div class="sections">
                                    <h3>{{ $brand->name }}</h3>
                                    <img src="{{ asset('uploads/brands/' . $brand->image) }}" alt="{{ $brand->name }}" />
                                </div>
                            @endif

                            @if($waranty)
                                <div class="sections-flex">
                                    <div class="section-left">
                                        <h3>{{ $waranty->name }}</h3>
                                        <p>{{ $waranty->description }}</p>
                                        <a href="#" class="btn btn-outline-primary mt-4">Know more ></a>
                                    </div>
                                    <div class="section-right">
                                        <img src="{{ asset('uploads/others/' . $waranty->image) }}" alt="{{ $waranty->name }}" />
                                    </div>
                                </div>
                            @endif

                            @if($include)
                                <div class="sections">
                                    <h4 class="mb-3">{{ $include->title }}</h4>

                                    @if(!empty($include->details))                            
                                        <ul class="process-list">
                                            @foreach($include->details as $detail)                                            
                                                <li class="process-item">{{ $detail['description'] }}</li>                                            
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endif

                            @if($need)
                                <div class="sections">                                    
                                    <h3 class="mb-3">{{ $need->title }}</h3>
                                    <img src="{{ asset('uploads/others/' . $need->image) }}" alt="{{ $need->title }}" />                                    
                                </div>
                            @endif
                            
                            <div class="sections">
                                <h3 class="mb-3">Top professinals</h3>                                
                            </div>                            

                            @if($brand)
                                <div class="sections">
                                    <h3 class="mb-3">{{ $brand->title }}</h3>
                                    <img src="{{ asset('uploads/others/' . $brand->image) }}" alt="{{ $brand->title }}" />
                                    <p class="mt-3">These trademarks and/or logos are used for illustration purposes only and we discliam any specific connection witht eh brand in this regard.</p>
                                </div>
                            @endif      
                            
                            @if($faqs)
                                <div class="sections">
                                    <h4 class="mb-3">{{ $faqs->title }}</h4>

                                    @if(!empty($faqs->details))
                                        <div class="accordion" id="faqAccordion">
                                            @foreach($faqs->details as $index => $detail)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                                        <button
                                                            class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#faqCollapse{{ $index }}"
                                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                            aria-controls="faqCollapse{{ $index }}"
                                                        >
                                                            {{ $detail['question'] }}
                                                        </button>
                                                    </h2>

                                                    <div id="faqCollapse{{ $index }}"
                                                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                        aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#faqAccordion">
                                                        <div class="accordion-body">{!! $detail['answer'] !!}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>            

    <div class="right">        
        <div class="thumb-details">
            <a data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">
                <img src="{{ $single ? asset('uploads/services/small/' . $single) : asset('admin-assets/img/default-150x150.png') }}" alt="{{ $item->title }}" />                
            </a>            
            <a href="javascript:void(0);" class="overlap-btn btn btn-outline-primary add-to-cart-btn" onclick="addToCart({{ $item->id }}, this)">Add</a>
        </div>
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