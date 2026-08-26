@extends('front.layouts.app')

@section('title', $product->title . ' - ' .$product->short_description . ' | Category: ' . $product->category->category_name . ' | '  . config('app.name'))
@section('meta_description', Str::limit($product->short_description, 155))
@section('meta_keywords', $product->title)

@section('content')

<div class="container">
    <div class="light-font">
        @include('front/layouts/breadcrumb')        
    </div>
</div>

<div class="container mt-4">
    <div class="product-details-wrapper">
        <div class="row">
            <div class="col-md-4 col-12">
                @if($product->product_images->isNotEmpty())                    
                    <img src="{{ asset('uploads/product/large/'.$product->product_images->first()->image) }}" alt="Image">                    
                @endif                
            </div>        

            <div class="col-md-8 col-12">
                <h1>{{ $product->title }}</h1>
                <p class="tag">{{ $product->short_description }}</p>

                <div class="part mb-4">
                    <div class="rating-breakdown">
                        <div class="total-numbers">
                            <div class="title">
                                <h4>{{ number_format($averageRating,1) }} </h4>
                                <span class="sprites green-star-icon"></span>
                            </div>                            
                            <p>{{ $totalRatings >= 1000 ? round($totalRatings / 1000, 1).'k' : $totalRatings }} Verified buyers</p>
                        </div>
                        <div class="breakdown">
                        @foreach($ratings as $star => $count)
                            @php
                                $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
                                if($star >= 4){
                                    $color = 'green';
                                } elseif($star == 3){
                                    $color = 'yellow';
                                } else {
                                    $color = 'red';
                                }
                            @endphp

                            <div class="rating-row">
                                <div class="rating-label">{{ $star }} ★</div>
                                <div class="rating-bar">
                                    <div class="rating-fill {{ $color }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="rating-count">({{ $count }})</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                @foreach($reviews as $review)
                    <div class="review-box">
                        <div class="name">
                            <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                            <p class="star-repeate">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <span class="sprites rating-star2-ico"></span>
                                    @else
                                        <span class="sprites rating-star1-ico"></span>
                                    @endif
                                @endfor
                            </p>                                                        
                        </div>
                        <p>{{ $review->review }}</p>
                        <p class="date">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y')}}</p>
                    </div>
                @endforeach
                {{ $reviews->links() }}                
            </div>
        </div>
    </div>
</div>
@endsection