@props([
    'item' => null,    
    'data' => null,
    'service' => null,
    'category' => null,
    'subcategory' => null,
    'ratings' => collect(),
    'wishlistProductIds' => null,
    'hover' => true,        
    'price' => null,        
    'title_limit' => null,
    'brand' => null,
    'process' => null,
    'waranty' => null,
    'include' => null,
    'need' => null,
    'faqs' => null,
    'class' => null,
    'servicetitle' => null,
    'show' => null,
    'description' => null, 
    'gallery' => null,   
    'discount' => null,
])

@php
    $service = $item->service ?? $item;
    $title = $service->title ?? '';
    $short = $service->short_description ?? '';
    $price = $service->price;    
    $single = $service->image;
    $qty = $service->qty ?? '';    
    $galleryRepeate = $service->product_images ? $service->product_images->first() : null;
    $url = $service->url ?? null;   
    //$discount = $service->discounts->first();
    $discount_percent = $discount?->discountPercentage?->percentage ?? 0;
    $discount_price = $price - ($price * $discount_percent / 100);         
    $cartItems = Cart::content();
    $cartServiceIds = $cartItems->pluck('id')->toArray();    
@endphp

<div class="{{ $class }}">
    @if($show == "services")    
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

                @if (in_array($item->id, $cartServiceIds))
                    @php
                        $cartItem = $cartItems->firstWhere('id', $item->id);
                    @endphp

                    <div class="qty-control overlap-qty">
                        <button type="button" class="qty-btn qty-minus" data-rowid="{{ $cartItem->rowId }}">−</button>
                        <span class="cart-qty" id="qty-{{ $cartItem->rowId }}">{{ $cartItem->qty }}</span>
                        <button type="button" class="qty-btn qty-plus" data-rowid="{{ $cartItem->rowId }}">+</button>
                    </div>
                @else
                    <a href="javascript:void(0);" class="overlap-btn btn btn-outline-primary add-to-cart-btn" onclick="addToCart({{ $item->id }}, this)">Add</a>
                @endif           
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
    
    @else        
        @if($gallery == 'yes')
            <div class="product-slider">
                @if($images->count() > 0)
                    @foreach($images as $image)
                        <div class="slider-item">
                            @if($url)
                                <a href="{{ $url }}" target="_blank" title="{{ $title }}">
                            @endif
                                <img src="{{ asset('uploads/services/small/'.$image->image) }}" class="rounded" alt="{{ $title }}">
                            @if($url)
                                </a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="rounded">
                @endif
            </div>                     

        @elseif($gallery == 'homeCategory')  
            <a href="{{ route('front.category', [$data->category_slug]) }}" class="link">
                @if ($data->image != "")
                    <img src="{{ asset('uploads/category/thumb/'.$data->image) }} " alt="" class="product-img rounded">
                @endif
                <p>{{ Str::limit($data->category_name, 27, '...') }}</p>
            </a>

            @if($price)
                <p class="price">₹{{ $data->price }}</p>
            @endif
            
        @elseif($gallery == 'homeServices')  
            <a href="{{ route('front.category', [$data->category->category_slug]) }}" class="link">
                @if ($data->category->thumb != "")
                    <img src="{{ asset('uploads/category/thumb/'.$data->category->thumb) }} " alt="" class="thumb">
                @endif

                <h5>{{ Str::limit($data->category->category_name, 20, '...') }}</h5>

                @php
                    $averageRating = round($data->ratings->avg('rating') ?? 0);
                @endphp

                <div class="rating">
                    <div class="part">
                        <p class="icon">
                            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M12.923 2.616a1 1 0 00-1.846 0l-2.41 5.795-6.257.502a1 1 0 00-.571 1.756l4.767 4.084-1.457 6.105a1 1 0 001.494 1.086L12 18.672l5.357 3.272a1 1 0 001.494-1.086l-1.457-6.105 4.767-4.084a1 1 0 00-.57-1.756l-6.257-.502-2.41-5.795z" fill="#545454"></path></svg>
                        </p>
                        {{-- @for($i = 1; $i <= 5; $i++)
                            @if($i <= $averageRating)                            
                                <i class="fa fa-star"></i>
                            @else                            
                                <i class="fa fa-star-o"></i>
                            @endif
                        @endfor --}}
                        <p>{{ $data->ratings->count() }}</p>
                    </div>

                    @if ($data->category->instant == 'yes')
                        <div class="part">
                            <p class="icon"><svg width="100%" height="100%" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" fill="#545454"></path></svg></p>
                            <p class="icon"><svg width="100%" height="100%" viewBox="0 0 12 12" fill="#07794C" xmlns="http://www.w3.org/2000/svg"><path d="M1.576 7.77a.2.2 0 01-.16-.32L6.609.546a.2.2 0 01.36.11l.19 3.384a.2.2 0 00.2.19h3.067a.2.2 0 01.16.32l-5.192 6.903a.2.2 0 01-.36-.109l-.19-3.385a.2.2 0 00-.199-.189H1.576z" fill="#07794C"></path></svg></p>
                            <p>Instant</p>
                        </div>
                    @endif
                </div>

                <p>₹{{ $data->category->price }}</p>
            </a>
        @endif       

        @if($servicetitle)
            <div class="product-info">            
                <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>
                @if($description)
                    <p class="short">{{ isset($short_limit) ? Str::limit($short, $short_limit, '...') : $short }}</p>
                @endif
            </div>
        @endif            
    @endif
</div>