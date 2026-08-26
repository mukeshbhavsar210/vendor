@extends('front.layouts.app')

@section('title', 'My Dashboard')

@section('content')

<div class="container">    
    @include('front.account.common.sidebar')  
    
    <div class="col-md-9 col-12 px-md-0">
        @include('front.account.common.message')

        @include('front.account.common.modal', [
            'form' => $profileFormConfig,
            'model' => $user
        ])          

                    <div class="orders-details">
                        <div class="showcase">
                            <div class="photo-email">
                                <div class="flex">
                                    <div class="photo">
                                        @if(Auth::user()->image)
                                            <img src="{{ asset('uploads/profile/' . Auth::user()->image) }}" class="profile-pic">
                                        @else

                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                            width="90px" height="90px" viewBox="0 0 60 60" enable-background="new 0 0 60 60" xml:space="preserve">
                                        <path fill="#CCCCCC" d="M48.35,50.783l0.254,0.305c-4.997,4.488-11.608,7.222-18.842,7.222s-13.833-2.721-18.83-7.196l0.28-0.331
                                            c0,0,3.293-2.619,7.171-3.585c3.878-0.966,5.632-3.687,5.632-3.687v-4.755c0,0-2.823-3.776-2.428-6.395c0,0-3.496-2.327-1.068-5.721
                                            c0,0-5.62-16.134,8.633-16.299c3.611-0.038,5.403,2.708,5.403,2.708c9.65-0.966,4.488,13.591,4.488,13.591
                                            c2.428,3.395-1.068,5.721-1.068,5.721c0.394,2.619-2.428,6.395-2.428,6.395v4.755c0,0,1.755,2.721,5.632,3.687
                                            C45.057,48.164,48.35,50.783,48.35,50.783z"/>
                                        <path fill="none" stroke="#555555" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="
                                            M48.35,50.783c0,0-3.293-2.619-7.171-3.585c-3.878-0.966-5.632-3.687-5.632-3.687v-4.755c0,0,2.823-3.776,2.428-6.395
                                            c0,0,3.496-2.327,1.068-5.721c0,0,5.162-14.558-4.488-13.591c0,0-1.793-2.746-5.403-2.708c-14.253,0.165-8.633,16.299-8.633,16.299
                                            c-2.428,3.395,1.068,5.721,1.068,5.721c-0.394,2.619,2.428,6.395,2.428,6.395v4.755c0,0-1.755,2.721-5.632,3.687
                                            c-3.878,0.966-7.171,3.585-7.171,3.585"/>
                                        <path fill="none" stroke="#555555" stroke-width="3" stroke-miterlimit="10" d="M10.932,51.113
                                            C5.16,45.939,1.524,38.425,1.524,30.071c0-15.6,12.638-28.238,28.238-28.238C45.349,1.833,58,14.471,58,30.071
                                            c0,8.353-3.624,15.854-9.396,21.016c-4.997,4.488-11.608,7.222-18.842,7.222S15.929,55.589,10.932,51.113z"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <div class="email">
                                        <h6>{{ $user->name }}</h6>
                                        <p>{{ $user->email }}</p>

                                        <div class="flex mt-2">
                                            <p><a href="javascript:0" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</a></p>
                                            <p><a href="{{ route('account.logout') }}" class="btn btn-danger btn-sm">Logout</a></p>
                                        </div>
                                    </div>            
                                </div>                                                                  
                            </div>
                        </div>            

                        <div class="card-link">
                            <div class="product-item">
                                <a href="{{ route('account.orders') }}" class="link dash-icon-1">
                                    <span class="sprites"></span>
                                    <h4>Orders</h4>
                                    <p>Check your order status</p>
                                </a>
                            </div>
                                <div class="product-item">
                                    <a href="{{ route('account.wishlist') }}" class="link dash-icon-2">
                                        <span class="sprites"></span>
                                        <h4>Wishlist</h4>
                                        <p>Check your order status</p>
                                    </a>
                                </div>
                                <div class="product-item">
                                    <a href="{{ route('account.cards') }}" class="link dash-icon-3">
                                        <span class="sprites"></span>
                                        <h4>Saved Card</h4>
                                        <p>Check your order status</p>
                                    </a>
                                </div>
                                <div class="product-item">
                                    <a href="{{ route('account.address') }}" class="link dash-icon-4">
                                        <span class="sprites"></span>
                                        <h4>Addresses</h4>
                                        <p>Check your order status</p>
                                    </a>
                                </div>
                                <div class="product-item">
                                    <a href="{{ route('account.coupons') }}" class="link dash-icon-5">
                                        <span class="sprites"></span>
                                        <h4>Coupons</h4>
                                        <p>Check your order status</p>
                                    </a>
                                </div>
                                <div class="product-item">
                                    <a href="{{ route('account.profile') }}" class="link dash-icon-6">
                                        <span class="sprites"></span>
                                        <h4>Profile Details</h4>
                                        <p>Check your order status</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>            
@endsection

@section('customJs')

@endsection