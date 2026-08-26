@extends('front.layouts.app')

@section('title', 'My Coupons')

@section('content')

<div class="container">    
                @include('front.account.common.sidebar')  
        
                <div class="col-md-9 col-12 px-md-0">
                    <div class="details-accounts w-100">
                        @include('front.account.common.message')        

                        <div class="sort-coupon">
                            @php
                                $sortOptions = [
                                    'trending' => 'Trending',
                                    'discount' => 'Discount',
                                    'expiring' => 'Expiring Soon',
                                    'all' => 'All'
                                ];
                            @endphp

                            <ul>
                                <li><p><b>Sort by:</b></p></li>
                                @foreach($sortOptions as $key => $label)
                                    <li>
                                        <a href="{{ route('account.coupons', ['sort'=>$key]) }}" class="{{ request('sort','all') == $key ? 'active' : '' }}">
                                            {{ $label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="order-history2">
                            <div class="row">
                                @if($coupons)
                                    @foreach ($coupons as $coupon)
                                        <div class="col-md-6 col-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            @if ($coupon->image)
                                                                <img src="{{ asset('uploads/coupon/'.$coupon->image) }}" class="img-fluid" >
                                                            @else
                                                                <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                                            @endif 
                                                        </div>
                                                        <div class="col-7">
                                                            <p><b>Flat {{ $coupon->discount_amount }}{{ $coupon->type == 'percent' ? '%' : '₹' }} off</b></p>
                                                            <div class="tiny-font text-muted mb-2">
                                                                @if($coupon->min_amount)
                                                                    <p>On min. purchase of ₹{{ $coupon->min_amount }}</p>    
                                                                @endif                                                    
                                                                <p class="mt-2">Code: <b>{{ $coupon->code }}</b></p>
                                                                <p>Expiry: <b>{{ \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y') }}</b></p>
                                                            </div>

                                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('front.shop', ['coupon' => $coupon->code]) }}" target="_blank">
                                                                View Products
                                                            </a>
                                                        </div>            
                                                    </div>                            
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>                
</div>

@endsection

@section('customJs')
<script>
    
</script>
@endsection