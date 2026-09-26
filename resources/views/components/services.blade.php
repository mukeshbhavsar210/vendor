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
    'reviews' => null,
    'description' => null, 
    'gallery' => null,   
    'discount' => null,
])

@php
    $service = $item->service ?? $item;
    $title = $service->title ?? '';
    $short = $service->short_description ?? '';    
    $single = $service->image;
    $qty = $service->qty ?? '';    
    $galleryRepeate = $service->product_images ? $service->product_images->first() : null;
    $url = $service->url ?? null;   
    //$discount = $service->discounts->first();
    $discount_percent = $discount?->discountPercentage?->percentage ?? 0;
    $discount_price = $price - ($price * $discount_percent / 100);         
    $cartItems = Cart::content();
    $cartServiceIds = $cartItems->pluck('id')->toArray();   
    $ratingCount = $ratings->count();
    $averageRating = $ratings->avg('ratings') ?? 0;            
    $ratingCounts = $ratings->groupBy('ratings')->map->count();
    $ratingTotal = $ratings->count(); 
@endphp

<div class="{{ $class }}">
    @if($show == "services")    
        <div class="left">
            <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>            
                            
            @if($ratings->count())
                <div class="ratings">
                    <svg class="svg" width="100%" height="100%" viewBox="0 0 20 20" fill="#572AC8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.333 10a8.333 8.333 0 11-16.667 0 8.333 8.333 0 0116.667 0zm-7.894-4.694A.476.476 0 009.999 5a.476.476 0 00-.438.306L8.414 8.191l-2.977.25a.48.48 0 00-.414.342.513.513 0 00.143.532l2.268 2.033-.693 3.039a.51.51 0 00.183.518.458.458 0 00.528.022L10 13.298l2.548 1.629a.458.458 0 00.527-.022.51.51 0 00.184-.518l-.693-3.04 2.268-2.032a.513.513 0 00.143-.532.48.48 0 00-.415-.342l-2.976-.25-1.147-2.885z" fill="#572AC8"></path></svg>
                    @if($ratingCount > 0)
                        <span>{{ number_format($averageRating, 1) }}</span>
                        <span>({{ $ratingCount }} reviews)</span>
                    @endif
                </div>
            @endif
                    
            <div class="price tiny-font">
                @if($discount_percent > 0)
                    @php
                        $total_price = $item->qty * round($discount_price);
                    @endphp

                    <span>₹{{ round($total_price ) }}</span>                    
                    <span><del>₹{{ $price }}</del></span>  
                    {{-- <span class="discount">({{ $discount_percent }}% OFF)</span> --}}
                @else
                    <span>₹{{ $item->subcategory->price }}</span>
                @endif
                <svg style="top:2px; position:relative;" width="12px" height="12px" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" fill="#545454"></path></svg>
                <span>{{ $item->subcategory->time }}</span>
            </div>
            {{-- <p><b>₹{{ $item->price }}</b></p> --}}

            <div class="text-details">
                <p>{{ isset($short_limit) ? Str::limit($short, $short_limit, '...') : $short }}</p>            
            </div>
                                
            <a href="javascript:0" class="view" data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">View Details</a>

            <div class="modal fade " id="service_{{ $item->id }}" tabindex="-1" aria-labelledby="serviceLabel_{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-custom">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-scroll">
                            @if($process)                                    
                                <img src="{{ asset('uploads/process/' . $process->banner) }}"  />
                            @endif

                            {{-- <div class="category-banner m-0">                                
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
                            </div>                             --}}
                                                    
                            <div class="modal-body-wrapper">
                                <div class="left">
                                    <h2>{{ isset($title_limit) ? Str::limit($title, $title_limit, '...') : $title }}</h2>

                                    @if($ratings->count())
                                        <div class="ratings">
                                            <svg class="svg" width="100%" height="100%" viewBox="0 0 20 20" fill="#572AC8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.333 10a8.333 8.333 0 11-16.667 0 8.333 8.333 0 0116.667 0zm-7.894-4.694A.476.476 0 009.999 5a.476.476 0 00-.438.306L8.414 8.191l-2.977.25a.48.48 0 00-.414.342.513.513 0 00.143.532l2.268 2.033-.693 3.039a.51.51 0 00.183.518.458.458 0 00.528.022L10 13.298l2.548 1.629a.458.458 0 00.527-.022.51.51 0 00.184-.518l-.693-3.04 2.268-2.032a.513.513 0 00.143-.532.48.48 0 00-.415-.342l-2.976-.25-1.147-2.885z" fill="#572AC8"></path></svg>
                                            @if($ratingCount > 0)
                                                <span>{{ number_format($averageRating, 1) }}</span>
                                                <span>({{ $ratingCount }} reviews)</span>
                                            @endif
                                        </div>
                                    @endif                      
                                            
                                    <div class="price tiny-font">
                                        @if($discount_percent > 0)
                                            @php
                                                $total_price = $item->qty * round($discount_price);
                                            @endphp

                                            <b>₹ {{ round($total_price ) }}</b>
                                            <span><del>₹{{ $price }}</del></span>  
                                            {{-- <span class="discount">({{ $discount_percent }}% OFF)</span> --}}
                                        @else
                                            <b>₹{{ $item->subcategory->price }}</b>
                                        @endif
                                        <svg style="top:2px; position:relative;" width="12px" height="12px" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" fill="#545454"></path></svg>
                                        {{ $item->subcategory->time }}
                                    </div>                                    
                                </div>

                                <div class="right">
                                    <a class="btn btn-primary add-to-cart-btn" onclick="addToCart({{ $item->id }}, this)">Add</a>
                                </div>
                            </div>

                            @if($process)
                                <div class="sections">
                                    <h5 class="mb-3">Highlights</h5>
                                    <p>{{ $process->highlights }}</p>
                                </div>
                                                                
                                @if(!empty($process->details))   
                                    <div class="sections">
                                        <h2 class="mb-3">How it works</h2>                                        
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
                                    </div>
                                @endif                                

                                @if($process->bring)
                                    <div class="sections">
                                        <h4 class="mb-3">Things will bring</h4>
                                        <img src="{{ asset('uploads/process/' . $process->bring) }}"  />
                                    </div>
                                @endif

                                @if(!empty($process->notes))
                                    <div class="sections">
                                        <h4 class="mb-3">Please Note</h4>                                    
                                        <ul>
                                            @foreach($process->notes as $note)
                                                <li>{{ $note['description'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif                                
                                
                                @if(!empty($process->tips))
                                    <div class="sections">
                                        <h4 class="mb-3">Altercare tips</h4>
                                        <ul>
                                            @foreach($process->tips as $tip)
                                                <li>{{ $tip['description'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @if(!empty($process->professionals))
                                    <div class="professional">
                                        <div class="leftDetails">
                                            <h4 class="mb-3">Top Professioanls</h4>
                                            <ul>
                                                @foreach($process->professionals as $value)
                                                    <li>{{ $value['description'] }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="rightDetails">
                                            <img src="{{ asset('front-assets/images/professional.png') }}" alt="Professioals" />
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($process->needs))   
                                    <div class="sections">
                                        <h2 class="mb-3">What we will need from you</h2>                                        
                                        <div class="flex">
                                            @foreach($process->needs as $value)                                                                                            
                                                @if(!empty($value['image']))                                                        
                                                    <img src="{{ asset('uploads/process/' . $value['image']) }}" alt="{{ $value['name'] ?? '' }}">                                                        
                                                @endif
                                            @endforeach
                                        </ol>
                                        </div>
                                    </div>
                                @endif 
                                
                                @if(!empty($process->brand))                                   
                                    <img src="{{ asset('uploads/process/' . $process->brand) }}"  />
                                @endif

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
                            @endif  

                            @if($ratings->count())
                                <div class="sections">                                    
                                    <div class="ratings-at-bottom">
                                        <div class="details">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.923 2.616a1 1 0 00-1.846 0l-2.41 5.795-6.257.502a1 1 0 00-.571 1.756l4.767 4.084-1.457 6.105a1 1 0 001.494 1.086L12 18.672l5.357 3.272a1 1 0 001.494-1.086l-1.457-6.105 4.767-4.084a1 1 0 00-.57-1.756l-6.257-.502-2.41-5.795z" fill="#0F0F0F"></path></svg>
                                            @if($ratingCount > 0)
                                                <h1>{{ number_format($averageRating, 1) }}</h1>
                                            @endif
                                        </div>                                        
                                        
                                        @if($ratingCount > 0)                                            
                                            <p class="small-text">{{ $ratingCount }} reviews</p>
                                        @endif
                                    </div>

                                    <div class="rating-breakdown">
                                        @for($star = 5; $star >= 1; $star--)
                                            @php
                                                $count = $ratingCounts->get($star, 0);
                                                $percentage = $ratingTotal > 0
                                                    ? ($count / $ratingTotal) * 100
                                                    : 0;
                                            @endphp

                                            <div class="rating-row">
                                                <span class="rating-star">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.923 2.616a1 1 0 00-1.846 0l-2.41 5.795-6.257.502a1 1 0 00-.571 1.756l4.767 4.084-1.457 6.105a1 1 0 001.494 1.086L12 18.672l5.357 3.272a1 1 0 001.494-1.086l-1.457-6.105 4.767-4.084a1 1 0 00-.57-1.756l-6.257-.502-2.41-5.795z" fill="#0F0F0F"></path></svg>
                                                    {{ $star }}                                                    
                                                </span>

                                                <div class="rating-bar">
                                                    <div class="rating-bar-fill" style="width: {{ $percentage }}%;"></div>
                                                </div>

                                                <span class="rating-count">{{ $count }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="sections">
                                    <h4 class="mb-2">All reviews</h4>

                                    <div id="reviewsList" class="reviews-list">
                                        @foreach($ratings->sortByDesc('created_at') as $key => $rating)
                                            <div class="review-item review-item-{{ $key }}" @if($key >= 10) style="display:none;" @endif>
                                                <div class="top-line">
                                                    <div class="user">
                                                        <h5>{{ $rating->user?->name ?? 'Anonymous' }}</h5>
                                                    </div>

                                                    <div class="rating {{ $rating->ratings >= 4 ? 'green' : ($rating->ratings >= 3 ? 'orange' : 'red') }}">
                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.923 2.616a1 1 0 00-1.846 0l-2.41 5.795-6.257.502a1 1 0 00-.571 1.756l4.767 4.084-1.457 6.105a1 1 0 001.494 1.086L12 18.672l5.357 3.272a1 1 0 001.494-1.086l-1.457-6.105 4.767-4.084a1 1 0 00-.57-1.756l-6.257-.502-2.41-5.795z" fill="#ffffff"></path></svg>
                                                        <p>{{ $rating->ratings }}</p>
                                                    </div>
                                                </div>

                                                <div class="service">     
                                                    {{ $rating->created_at?->format('d M Y') }} -                                               
                                                    {{ $rating->service?->title ?? $item->service_name }}
                                                </div>

                                                <div class="text">
                                                    <p>{{ $rating->review }}</p>                                                    
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($ratings->count() > 10)
                                        <button type="button" id="showMoreReviews" class="show-more-reviews btn btn-outline-primary w-100 mt-3">
                                            Show more
                                        </button>
                                    @endif                                    
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>            

        <div class="right-card">        
            <div class="thumb-details">
                <a data-bs-toggle="modal" data-bs-target="#service_{{ $item->id }}">
                    <img src="{{ asset('uploads/subcategory/'.$subcategory->image) }}" alt="{{ $subcategory->category_name }}" />
                    {{-- <img src="{{ $single ? asset('uploads/services/small/' . $single) : asset('admin-assets/img/default-150x150.png') }}" alt="{{ $item->title }}" /> --}}
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
                <p class="price">₹{{ $data->subcategory->price }}</p>
            @endif
            
        @elseif($gallery == 'homeServices')  
            <a href="{{ route('front.category', [$data->category->category_slug]) }}#{{ $data->sub_category_slug }}" class="link">                

                @if ($data->image != "")                    
                    <img src="{{ asset('uploads/subcategory/'.$data->image) }}" alt="" class="thumb">
                @endif                

                <h5>{{ Str::limit($data->sub_category_name, 29, '...') }}</h5>               

                @if($reviews)
                    <div class="rating">
                        <div class="part">
                            @if($ratingCount > 0)
                                <span>★ {{ number_format($averageRating, 1) }}</span>                                
                            @endif
                        </div>

                        @if ($data->instant == 'yes')
                            <div class="part">
                                <span class="icon"><svg width="13px" height="13px" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" fill="#545454"></path></svg></span>
                                <span class="icon"><svg width="13px" height="13px" viewBox="0 0 12 12" fill="#07794C" xmlns="http://www.w3.org/2000/svg"><path d="M1.576 7.77a.2.2 0 01-.16-.32L6.609.546a.2.2 0 01.36.11l.19 3.384a.2.2 0 00.2.19h3.067a.2.2 0 01.16.32l-5.192 6.903a.2.2 0 01-.36-.109l-.19-3.385a.2.2 0 00-.199-.189H1.576z" fill="#07794C"></path></svg></span>
                                <span>Instant</span>
                            </div>
                        @endif
                    </div>
                @endif
                <p class="mt-1">₹{{ $data->price }}</p>
            </a>

        @elseif($gallery == 'homeServicesModal')  
            <a href="{{ route('front.category', [$data->category->category_slug]) }}#{{ $data->sub_category_slug }}" class="link">                
                @if ($data->image != "")                    
                    <img src="{{ asset('uploads/subcategory/'.$data->image) }}" alt="" class="thumb">
                @endif                
                <h5>{{ Str::limit($data->sub_category_name, 29, '...') }}</h5>
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