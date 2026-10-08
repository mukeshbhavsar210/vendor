@extends('front.layouts.app')

@section('title', 'My Wishlist')

@section('content')

<div class="container">      
    @include('front.account.common.sidebar')

    <div class="col-md-9 col-12 px-md-0">
        <div class="details-accounts"> 
            <h3>My Wishlist <span class="text-muted">- {{ $wishlists->count() }}</span></h3>

            <div class="row mt-4">
                @if ($wishlists->isNotEmpty())
                    @foreach ($wishlists as $wishlist)
                        @php
                            $item = $wishlist->service;
                            $isInWishlist = true;
                        @endphp
                
                        <div class="col-md-3 col-6">                    
                            <x-services 
                                :item="$wishlist->service"
                                :isInWishlist="$isInWishlist"
                                section="show_wishlist" 
                                gallery="wishlist"
                                class="wishlist"
                                :producttitle="true"
                                :price="true"
                                :title_limit="18"                                
                            />
                        </div>
                    @endforeach
                @else            
                    <div class="card">
                        <div class="card-body text-center p-5">
                            <h3 class="mb-2">Your Wishlist is empty</h3>
                            <p>Add items that you like to your wishlist. <br />Review them anytime and easily move them to the cart.</p>
                            <a href="{{ route('front.home') }}" class="btn btn-primary mt-5">Continue to add Services</a>
                        </div>
                    </div>                
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    <script>
       
    </script>
@endsection
