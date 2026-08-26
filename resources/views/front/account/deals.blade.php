@extends('front.layouts.app')

@section('title', 'Deals')

@section('content')

<div class="container">                
    <h5 class="h5 mb-4 mt-3">
        Deals
        <span class="text-muted">- {{ $deals->count() }} items</span>
    </h5>
    
    @include('front.account.common.message')

    <div class="row">
        @if ($deals->isNotEmpty())
            @foreach ($deals as $deal)
                <div class="col-md-3 col-6" id="deal-{{ $deal->affiliate_product->id }}">
                    <div class="product-card deals">
                        <div class="product-image-wrapper">                            

                            <a href="http://{{ $deal->affiliate_product->affiliate_url }}" target="_blank">
                                @if (!empty($deal->affiliate_product->image))
                                    <img src="{{ asset('uploads/affiliate_products/'.$deal->affiliate_product->image) }}" class="rounded" alt="{{ $deal->affiliate_product->title }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="rounded">
                                @endif
                            </a>

                            @php
                                $likesCount = \App\Models\AffiliateWishlist::where('id', $deal->affiliate_product->id)->count();
                                $discount = $deal->affiliate_product->discounted_percentage ?? 0;
                                $price = $deal->affiliate_product->price ?? 0;
                                $finalPrice = $price - ($price * $discount / 100);
                            @endphp

                            <div class="product-info">            
                                <h2>{{ Str::limit($deal->affiliate_product->title, 100) }}</h2>   
                                
                                

                                <p>{{ $deal->affiliate_product->affiliate_platform }}</p>                                                       
                            </div>
                            
                            <div class="price">
                                <span class="dark">₹{{ round($finalPrice) }}</span>
                                <span class="mrp"><del>₹{{ round($price) }}</del></span>  
                                <span class="discount">({{ $discount }}% OFF)</span>                                        
                            </div>

                            <div class="hover-product"> 
                                <div class="flex">
                                    @if($likesCount)
                                        <p>You liked this</p>
                                    @endif                                    
                                    <p>Views: {{ $deal->affiliate_product->views }}</p>
                                    <a href="javascript:0" onclick="removeFromDeals({{ $deal->affiliate_product->id }})" class="remove_coupon delete-icon-new">
                                        <span class="sprites"></span>
                                    </button> 
                                </div>
                                <a href="http://{{ $deal->affiliate_product->affiliate_url }}" target="_blank" class="btn btn-primary mt-3 w-100">
                                    Buy Now @ 
                                    @if ($deal->affiliate_product->affiliate_platform == 'Amazon')
                                        Amazon
                                    @elseif ($deal->affiliate_product->affiliate_platform == 'Flipkart')
                                        Flipkart
                                    @elseif ($deal->affiliate_product->affiliate_platform == 'Meesho')
                                        Meesho
                                    @endif   
                                </a>

                                                                
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-md-7 mx-auto">
                    <div class="card">
                        <div class="card-body text-center p-5">
                            <h3 class="mb-2">Deal is Empty</h3>
                            <a href="{{ route('front.deals') }}" class="btn btn-primary mt-1">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('customJs')
    <script>      
    function removeFromDeals(id) {        
        $.ajax({
            url: "{{ route('account.remove.deals') }}", // adjust route
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status) {
                    location.reload(); // quick way

                    // OR better:
                    $("#deal-" + id).remove();

                    console.log(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    }
    </script>
@endsection
