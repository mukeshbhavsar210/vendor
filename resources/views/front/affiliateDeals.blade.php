@extends('front.layouts.app')

@section('title', 'Deals')

@section('content')

<div class="container">
    <h2>Deals</h2>

    <div class="row affiliate-products mt-3">  
        @php
            $imagePath = asset('uploads/affiliate_products/');
        @endphp

        @foreach($affiliateProducts as $product)
            <div class="col-md-6 col-6 mb-3">
                <div class="product-card" data-bs-toggle="modal" data-bs-target="#dealsModal_{{ $product->id }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-3">
                                
                                
                                @if (!empty($product->image))
                                    <img src="{{ asset('uploads/affiliate_products/'.$product->image) }}" class="thumb-product" alt="{{ $product->title }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="thumb-product">
                                @endif
                            </div>
                            <div class="col-md-9 col-9 relative">                            
                                <h6>{{ Str::limit($product->title, 100) }}</h6>                                
                                                                
                                <div class="row g-0 mt-3">
                                    @php
                                        $discount = $product->discounted_percentage ?? 0;
                                        $price = $product->price ?? 0;
                                        $finalPrice = $price - ($price * $discount / 100);
                                    @endphp
                                
                                    <div class="col">
                                        <span class="badge bg-success">{{ $discount }}% OFF</span>                                        
                                    </div>
                                    <div class="col text-center">
                                        <span class="fw-bold">₹{{ round($finalPrice) }}</span>
                                    </div>
                                    <div class="col">
                                        <p class="float-end">
                                            <del class="text-muted small ">₹{{ round($price) }}</del>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="aligned-bottom tiny-font">
                                    <div class="row g-0">
                                        <div class="col">
                                            <div class="flex-sm">
                                                <span class="sprites views-icon"></span>
                                                <p class="mt2">{{ $product->views }}</p>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="flex-sm">
                                                <span class="sprites likes-icon"></span>
                                                <p class="mt2">{{ $product->likes }}</p>
                                                {{-- {{ $product->increment('likes'); }} --}}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="flex-sm">
                                                <span class="sprites time-icon"></span>
                                                <p class="mt2">{{ timeAgo($product->created_at) }}</p>
                                            </div>
                                        </div>
                                        <div class="col">                                            
                                            @if ($product->affiliate_platform == 'Amazon')
                                                <img src="{{ asset('front-assets/images/amazon-logo.png') }}" alt="Amazon" class="platform-logo" />
                                            @elseif ($product->affiliate_platform == 'Flipkart')
                                                <img src="{{ asset('front-assets/images/flipkart-logo.png') }}" alt="Flipkart" class="platform-logo" />
                                            @elseif ($product->affiliate_platform == 'Meesho')
                                                <img src="{{ asset('front-assets/images/meesho-logo.png') }}" alt="Meesho" class="platform-logo" />
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="thumb-product">
                                            @endif                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                

                <div class="modal fade" id="dealsModal_{{ $product->id }}" tabindex="-1" aria-labelledby="dealsModalLabel_{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>{{ Str::limit($product->title, 100) }}</h5>                                
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">                                
                                <div class="row mt-3">
                                    <div class="col-8">
                                        @if (!empty($product->image))
                                            <img src="{{ asset('uploads/affiliate_products/'.$product->image) }}" class="img-fluid rounded" alt="{{ $product->title }}">
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="thumb-product">
                                        @endif                                        
                                    </div>
                                    <div class="col-4">
                                        <div class="highlights">
                                            <p class="modalPrice">₹{{ round($finalPrice) }}</p>  
                                            <p class="badge bg-success">{{ $discount }}% OFF</p>
                                            <p><del class="text-muted mt-2">{{ $product->price }}</del></p>
                                            
                                            @if ($product->affiliate_platform == 'Amazon')
                                                <img src="{{ asset('front-assets/images/amazon-logo-big.png') }}" alt="Amazon" class="platform-logo2" />
                                            @elseif ($product->affiliate_platform == 'Flipkart')
                                                <img src="{{ asset('front-assets/images/flipkart-logo-big.png') }}" alt="Flipkart" class="platform-logo2" />
                                            @elseif ($product->affiliate_platform == 'Meesho')
                                                <img src="{{ asset('front-assets/images/meesho-logo-big.png') }}" alt="Meesho" class="platform-logo2" />
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="thumb-product">
                                            @endif

                                            <p>{{ timeAgo($product->created_at) }}</p>
                                        </div>
                                    </div>                            
                                </div>

                                <a href="http://{{ $product->affiliate_url }}" target="_blank" target="_blank" class="btn btn-primary mt-3 w-100">
                                    Buy Now @ 
                                    @if ($product->affiliate_platform == 'Amazon')
                                        Amazon
                                    @elseif ($product->affiliate_platform == 'Flipkart')
                                        Flipkart
                                    @elseif ($product->affiliate_platform == 'Meesho')
                                        Meesho
                                    @endif   
                                </a>
                            
                                <div class="row mt-3">
                                    <div class="col text-center">
                                        @if($product->likes)
                                            <a href="#" class="like-icon-big-filled" data-id="{{ $product->id }}">
                                                <span class="sprites"></span>
                                                {{ $product->likes }} Like
                                            </a>
                                        @else
                                            <a href="#" class="like-icon-big" data-id="{{ $product->id }}">
                                                <span class="sprites"></span>
                                                {{ $product->likes }} Like
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col text-center">
                                        @php
                                            $isNotified = in_array($product->id, $notifiedIds);
                                        @endphp

                                        @if(!$isNotified)
                                            <a href="javascript:0" class="notify-icon" onclick="affiliateNotify({{ $product->id }})" data-bs-dismiss="modal">
                                                <span class="sprites"></span>
                                                Notify
                                            </a>
                                        @else
                                            <a href="javascript:0" class="notify-icon">
                                                <span class="sprites"></span>
                                                Requested
                                            </a>
                                        @endif
                                    </div>

                                    <div class="col text-center">
                                        @if (Auth::check())
                                            @php
                                                $isInAffiliateWishlist = in_array($product->id, $affiliateProductIds);                                        
                                            @endphp

                                            <a href="javascript:void(0)" data-id="{{ $product->id }}" onclick="addToAffiliate({{ $product->id }})" class="save-icon {{ $isInAffiliateWishlist ? 'disabled-link' : 'add-to-affiliate' }}" data-bs-dismiss="modal" aria-label="Close">
                                                <span class="sprites"></span>
                                                {{ $isInAffiliateWishlist ? 'Saved' : 'Save' }}
                                            </a>                                    
                                        @else
                                            <a href="{{ route('account.login') }}" class="btn btn-outline-dark retirectBack" data-product-id="{{ $product->id }}">
                                                <span class="sprites wishlist-ico-btn"></span>                                        
                                                Save
                                            </a>                          
                                        @endif
                                    </div>
                                    
                                    <div class="col text-center">
                                        <a href="javascript:void(0);" class="copy-icon copy-link" data-url="{{ $product->affiliate_url }}">
                                            <span class="sprites"></span>
                                            <span class="copy-label">Copy</span>
                                            <span class="copied-label">Copied</span>
                                        </a>
                                    </div>
                                </div>  
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endsection

@section('customJs')
<script>
    $(document).ready(function () {
        $(document).on('click', '.add-to-affiliate', function () {
            let id = $(this).data('id');
            console.log("Clicked ID:", id);
        });
    });

    $(document).on('click', '.like-icon-big', function () {
        let btn = $(this);
        let id = btn.data('id');

        $.post('/affiliate-product/' + id + '/like', {
            _token: '{{ csrf_token() }}'
        }, function (res) {

            if (res.success) {
                btn.find('.like-count').text(res.likes);
            } else {
                alert(res.message);
            }
        });
    });

    $(document).on('click', '.product-card', function () {
        let id = $(this).data('id');

        $.post('/affiliate-product/' + id + '/view', {
            _token: '{{ csrf_token() }}'
        });
    });


    $(document).on('click', '.copy-link', function (e) {
        e.preventDefault();

        let url = $(this).data('url');
        let $text = $(this).find('.copy-text');

        navigator.clipboard.writeText(url).then(() => {
            $text.text('Copied!');
            $(this).addClass('copied');

            setTimeout(() => {
                $text.text('Copy');
                $(this).removeClass('copied');
            }, 2000);
        });
    });   
</script>
@endsection
