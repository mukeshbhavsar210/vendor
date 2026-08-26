@extends('front.layouts.app')

@section('title', 'Shopping Bag' . (Cart::count() > 0 ? ' (' . Cart::count() . ')' : ''))

@section('content')    
    <div class="container">                
        <x-customer-address-form 
            :states="$states"
            :homeExists="$homeExists"
            :action="route('customer.address.store')" 
            method="POST" 
            title="Add New Address" 
            buttonText="Save"
            modalId="createAddressModal"
        />

        <div class="row">
            @if (Cart::count() > 0)
                <div class="col-md-8 col-12 left-border"> 
                    <div class="left-summary">                  
                    @php
                        $defaultAddressId = old(
                            'customer_address_id',
                            optional($address->firstWhere('default_address', 1))->id
                        );
                    @endphp

                    @if (Auth::check())
                        <div class="delivery-time">
                            <div class="row">
                                <div class="col-md-9 col-12">                                    
                                    @foreach($address as $value)
                                        @if($value->default_address == 1)
                                            <p>Delivery to: <b>{{ $value->name }} - M. {{ $value->mobile }}</b></p>                                                
                                            <p class="mt-1 font-13">
                                                {{ $value->address }}, {{ $value->locality }},<br />
                                                {{ $value->city }}-{{ $value->zip }}, {{ $value->state->name }}
                                            </p>
                                        @endif                                        
                                    @endforeach                                    
                                </div>
                                <div class="col-md-3 col-12">
                                    @if($address->count() > 0)
                                        <a href="#" class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#deliveryAddress">
                                            Change Address
                                        </a>
                                    @else
                                        <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#deliveryAddress">
                                            Add Shipping Address
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="delivery-time">
                            <div class="row">
                                <div class="col-md-10 col-9">                                    
                                    <p class="mt-2">Login to get delivery at your place.</p>                                    
                                </div>
                                <div class="col-md-2 col-3">
                                    <a href="{{ route('account.login') }}?redirect={{ url()->current() }}" class="btn btn-outline-primary retirectBack ">Login</a>
                                </div> 
                            </div>
                        </div>
                    @endif

                    <div class="product-title-cart">                            
                        <div class="title">
                            <label class="custom-checkbox">
                                <input type="checkbox" id="selectAll" checked >
                                <span class="checkmark"></span>
                                <span id="selectedCount">0</span>/{{ Cart::count() }} items selected
                            </label>                                
                        </div>

                        <div class="priceDetailsBox">
                            <button type="submit" name="action" value="remove" class="btn p-0 caps-btn text-muted bulk-action">Remove All</button>                            
                            {{-- @auth                                
                                <button type="submit" name="action" value="wishlist" class="btn bulk-action">Move to Wishlist</button>                               
                            @else
                                <a href="#" class="btn btn-link text-secondary" data-bs-toggle="modal" data-bs-target="#login" >
                                    Move Wishlist
                                </a>
                            @endauth --}}
                        </div>                            
                    </div>
                
                    @foreach($cartContent as $item)
                        <div class="product-repeate active-card" id="cart-item-{{ $item->rowId }}"> 
                            <div class="checkbox">
                                <label class="custom-checkbox">                                    
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->rowId }}" class="item-checkbox" checked
                                        data-rowid="{{ $item->rowId }}"                                                
                                        data-price="{{ $item->price }}"
                                        data-qty="{{ $item->qty }}"
                                        data-discount_percentage="{{ $item->options->discount_percent }}"
                                        >
                                    <span class="checkmark"></span>
                                </label>
                            </div>                                                                                             
                            <div class="photo">   
                                @if ($item->options->productImage)
                                    <img src="{{ asset('uploads/product/large/'.$item->options->productImage) }}" >
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                @endif
                            </div>
                            <div class="details">                                
                                <h3>{{ $item->name }}</h3>
                                <p class="short-desc">{{ $item->options->short_description ?? '' }}</p>

                                <div class="manuplate">
                                    @php
                                        $size = \App\Models\Size::find($item->options->size_id);
                                        $color = \App\Models\Color::find($item->options->color_id);
                                    @endphp
                                    
                                    @if($item->options->size_id)
                                        <div class="select">
                                            <a href="javascript:void(0);" class="update-cart-modal" data-type="size_id" data-productid="{{ $item->id }}"
                                            data-rowid="{{ $item->rowId }}" data-selected="{{ $item->options->size_id }}">
                                                Size: <b>{{ $size->name ?? '' }}</b> <span class="caret"></span>
                                            </a>
                                        </div>    
                                    @endif                                    

                                    @if($item->options->variant_id)
                                        <div class="select">                                
                                            <p data-type="color_id" data-productid="{{ $item->id }}"
                                                data-rowid="{{ $item->rowId }}" data-selected="{{ $item->options->color_id }}">
                                                Color: <b>{{ $color->name ?? '' }}</b>
                                            </p>
                                        </div>
                                        @else
                                            @if($item->options->color_id)
                                                <div class="select">                                
                                                    <a href="javascript:void(0);" class="update-cart-modal" data-type="color_id" data-productid="{{ $item->id }}"
                                                        data-rowid="{{ $item->rowId }}" data-selected="{{ $item->options->color_id }}">
                                                        Color: <b>{{ $color->name ?? '' }}</b> <span class="caret"></span>
                                                    </a>
                                                </div>
                                            @endif
                                    @endif
                                    
                                    <div class="select">   
                                        <a href="javascript:void(0);" class="update-cart-modal" data-type="qty" data-rowid="{{ $item->rowId }}" data-selected="{{ $item->qty }}">
                                            Qty: <b>{{ $item->qty }}</b> <span class="caret"></span>
                                        </a>                                                                    
                                    </div>
                                </div>
                                
                                <div class="price">
                                    <span class="dark">₹{{ round($item->options->discount_price) }}</span>
                                    @if($item->options->discount_percent)
                                        <span class="mrp">MRP <del>₹{{ $item->options->original_price }}</del></span>    
                                        <span class="discount">({{ $item->options->discount_percent }}% OFF)</span>
                                    @endif
                                </div>
                                
                                <div class="return-notice">
                                    <p><b>{{ $item->options->return_days ?? '' }}</b> return available</p>
                                </div>

                                <div class="delivery-notice">                                        
                                    @php                                            
                                        $minDate = $item->options->delivery_min_days;
                                        $maxDate = $item->options->delivery_max_days;
                                    @endphp
                                    <p>Delivery between <b>{{ \Carbon\Carbon::parse($minDate)->format('d M')}} - {{ \Carbon\Carbon::parse($maxDate)->format('d M')}}</b></p>
                                </div>
                            </div>

                            <div class="remove">                                
                                <a href="#" data-bs-toggle="modal" data-bs-target="#removeItemModal_{{ $item->id }}" class="delete-icon">
                                    <span class="sprites"></span>                                    
                                </a>
                            </div>

                            <div class="modal fade" id="removeItemModal_{{ $item->id }}" tabindex="-1" aria-labelledby="removeItemModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered item-remove-modal">
                                    <div class="modal-content">                                
                                        <div class="modal-body">
                                            <div class="item-remove-cart">
                                                <div class="image">                                            
                                                    @if ($item->options->productImage)
                                                        <img src="{{ asset('uploads/product/large/'.$item->options->productImage) }}" class="photo" >
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                                    @endif
                                                </div>
                                                <div class="text">
                                                    <h5>Move from Bag</h5>
                                                    <p>Are you sure you want to move this item from bag?</p>
                                                </div>
                                                <div class="close">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-group-details">                                                
                                            <a href="#" class="btn btn-link text-secondary w-50" onclick="deleteItem('{{ $item->rowId}}' );" data-bs-dismiss="modal">
                                                Remove
                                            </a>
                                            @auth
                                                <a href="#" class="btn btn-link text-secondary w-50" onclick="moveToWishlist('{{ $item->rowId }}')" data-bs-dismiss="modal">
                                                    Move to Wishlist
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-link text-secondary" data-bs-toggle="modal" data-bs-target="#login" >
                                                    Login to Move Wishlist
                                                </a>
                                            @endauth
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach  
                </div>
            </div>

                <div class="col-md-4 col-12">
                    <div class="cart-summery">
                        <form name="orderForm" id="orderForm" method="POST">                        
                            @csrf

                            @foreach($address as $value) 
                                @if($value->default_address == 1)                                        
                                    <input type="radio" name="customer_address_id" value="{{ $value->id }}" class="address-radio d-none" {{ $defaultAddressId == $value->id ? 'checked' : '' }} checked >
                                @endif                                        
                            @endforeach

                            @if($store_discount)
                                <div class="part">
                                    <h5>Coupon</h5>
                                    <div class="repeate-row">
                                        <div class="left">
                                            <div class="flex">
                                                <div>
                                                    <svg height="40px" width="40px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                                        viewBox="0 0 505 505" xml:space="preserve">
                                                    <circle style="fill:#FD8469;" cx="252.5" cy="252.5" r="252.5"/>
                                                    <path style="fill:#FFFFFF;" d="M382.3,296.1l26.3-26.3c9.6-9.6,9.6-25,0-34.6l-26.3-26.3c-4.6-4.6-7.2-10.8-7.2-17.3v-37.2
                                                        c0-13.5-11-24.5-24.5-24.5h-37.2c-6.5,0-12.7-2.6-17.3-7.2l-26.3-26.3c-9.6-9.6-25-9.6-34.6,0l-26.3,26.3
                                                        c-4.6,4.6-10.8,7.2-17.3,7.2h-37.2c-13.5,0-24.5,11-24.5,24.5v37.2c0,6.5-2.6,12.7-7.2,17.3l-26.3,26.3c-9.6,9.6-9.6,25,0,34.6
                                                        l26.3,26.3c4.6,4.6,7.2,10.8,7.2,17.3v37.2c0,13.5,11,24.5,24.5,24.5h37.2c6.5,0,12.7,2.6,17.3,7.2l26.3,26.3c9.6,9.6,25,9.6,34.6,0
                                                        l26.3-26.3c4.6-4.6,10.8-7.2,17.3-7.2h37.2c13.5,0,24.5-11,24.5-24.5v-37.2C375.1,306.9,377.7,300.7,382.3,296.1z"/>
                                                    <path style="fill:#4CDBC4;" d="M241.2,207.7c0,9-3.2,16.6-9.6,22.8c-6.4,6.2-14.5,9.3-24.2,9.3s-17.8-3.1-24.2-9.4
                                                        c-6.4-6.3-9.6-13.9-9.6-22.8c0-8.9,3.2-16.5,9.6-22.8c6.4-6.2,14.5-9.4,24.2-9.4s17.7,3.1,24.2,9.4
                                                        C238,191.1,241.2,198.7,241.2,207.7z M329.1,171.5L214.3,331.7h-39.4l115-160.1h39.2V171.5z M200.5,215.8c1.8,2.1,4,3.1,6.8,3.1
                                                        c2.7,0,5-1,6.9-3.1c1.8-2.1,2.7-4.8,2.7-8.1s-0.9-6.1-2.7-8.4c-1.8-2.2-4.1-3.3-6.8-3.3s-4.9,1.1-6.8,3.3c-1.8,2.2-2.7,5-2.7,8.4
                                                        S198.7,213.8,200.5,215.8z M331.4,301.4c0,9-3.2,16.6-9.6,22.8c-6.4,6.2-14.5,9.3-24.2,9.3s-17.8-3.1-24.2-9.4
                                                        c-6.4-6.2-9.6-13.9-9.6-22.8s3.2-16.5,9.6-22.8s14.5-9.4,24.2-9.4s17.7,3.1,24.2,9.4C328.2,284.8,331.4,292.4,331.4,301.4z
                                                        M290.8,309.5c1.8,2.1,4,3.1,6.8,3.1s5-1,6.9-3.1c1.8-2.1,2.8-4.8,2.8-8.1c0-3.4-0.9-6.2-2.8-8.4c-1.8-2.2-4.1-3.3-6.8-3.3
                                                        c-2.7,0-4.9,1.1-6.8,3.3c-1.8,2.2-2.8,5-2.8,8.4S289,307.5,290.8,309.5z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <b>1 Coupon applied <span class="tiny-font">({{ $coupon_code }})</span></b>
                                                    <p class="compare-discount tiny-font">You saved additional ₹{{ $coupon_discount }}</p>                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#discount" class="btn btn-outline-danger btn-sm">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="part pb-3 ">
                                <div class="repeate-row">
                                    <h5>Delivery Estimates</h5>
                                    <p class="tiny-font">Delivery in-between</p>
                                </div>
                                @foreach (Cart::content() as $item)
                                    <div class="repeate-row tiny-font strike">
                                        <p class="show-tooltip">{{ $item->name }} - {{ $item->qty }} <span class="tooltip" style="bottom:20px; left:55px;">{{ $item->options->return_days }} returns available</span></p>
                                        <span class="tiny-font">{{ \Carbon\Carbon::parse($item->options->delivery_min_days)->format('d M')}} - {{ \Carbon\Carbon::parse($item->options->delivery_max_days)->format('d M')}}</span> 
                                    </div>                        
                                @endforeach        
                            </div>

                            <div class="part">                            
                                <h5 class="mb-2">Price Details (<span class="selected-items">0</span> <span>items</span>)</h5>

                                @if (Cart::count() > 0)                                                                    
                                    <div class="repeate-row mb-1">
                                        <div class="left">Total MRP</div>
                                        <div class="right">₹<span class="mrp_total">0.00</span></div>
                                    </div>

                                    <div class="repeate-row mb-1 priceDetailsBox">
                                        <div class="left">Discount on MRP</div>
                                        <div class="right">
                                            <span class="compare-discount">- ₹<span class="price_discount">0.00</span></span>                                            
                                        </div>
                                    </div>

                                    @if($coupon_discount)
                                        <div class="repeate-row mb-2 priceDetailsBox">
                                            <div class="left">
                                                <div class="flex">
                                                    Coupon Discount
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#discount" class="show-discount">
                                                        <span class="sprites"></span>
                                                    </a>
                                                    <a href="javascript:0" class="remove_coupon delete-icon-new" onclick="removeCoupon()">
                                                        <span class="sprites"></span>
                                                    </a>
                                                </div>
                                            </div>                                             

                                            <div class="right">
                                                <input type="hidden" id="coupon_discount" value="{{ $coupon_discount }}">
                                                <span class="compare-discount">- ₹<span class="coupon_discount">{{ $coupon_discount }}</span></span>
                                            </div>
                                        </div>
                                    @else
                                        @if($hasValidCoupon)
                                            <div class="repeate-row mb-1">
                                                <div class="left">Coupon Discount</div>
                                                <div class="right">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#discount">Apply Discount</a>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    @auth
                                        <div class="repeate-row priceDetailsBox">
                                            <div class="left">Platform Fee</div>
                                            <input type="hidden" id="shipping_charge" value="{{ $shipping_charge }}">
                                            <div class="right">₹{{ number_format($shipping_charge,2) }}</div>
                                        </div>
                                    @endauth

                                    <div class="repeate-row total-amount">
                                        <div class="left">Total Amount</div>
                                        <div class="right">₹<span class="grand_total">0.00</span></div>
                                    </div>
                                @endif
                            </div>

                            <div class="terms">
                                By placing the order, you agree to Myntra's <a href="https://www.myntra.com/termsofuse" target="_blank" class="privaryPolicyTermsOfUseStrip-base-link">Terms of Use</a> and 
                                <a href="https://www.myntra.com/privacypolicy" target="_blank" class="privaryPolicyTermsOfUseStrip-base-link">Privacy Policy</a>
                            </div>

                            <input type="hidden" name="grand_total" id="grand_total_input">
                            @if($item->options->cod == 1)
                                <div class="order-btn mt-3">                                            
                                    <div class="btn-group w-100 mb-3" role="group">
                                        <input type="radio" class="btn-check" name="payment_method" id="payment_cod" value="cod" autocomplete="off" checked>
                                        <label class="btn btn-outline-secondary" for="payment_cod">COD</label>

                                        <input type="radio" class="btn-check" name="payment_method" id="payment_razorpay" value="razorpay" autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="payment_razorpay">RazorPay</label>
                                    </div>
                                    
                                    @if (Auth::check())
                                        <button id="cod-form" class="btn-primary btn btn-block w-100 {{ Auth::check() ? '' : 'retirectBack' }}" type="submit">Pay on COD</button>
                                        <button id="razorpay-form" class="btn-primary btn btn-block w-100 d-none {{ Auth::check() ? 'placeOrderBtn' : 'retirectBack' }}" type="submit">Pay <span class="grand_total_button"></span></button>
                                    @else
                                        <a id="cod-form" href="{{ route('account.login') }}?redirect={{ url()->current() }}" class="btn btn-primary btn-block retirectBack w-100">Pay on COD</a>
                                        <a id="razorpay-form" href="{{ route('account.login') }}?redirect={{ url()->current() }}" class="btn btn-primary btn-block retirectBack w-100 d-none">Pay <span class="grand_total_button"></span></a>
                                    @endif
                                </div>  
                            @else                                    
                                <button id="razorpay-form" class="btn-primary btn btn-block w-100  {{ Auth::check() ? 'placeOrderBtn' : 'retirectBack' }}" type="submit">Pay <span class="grand_total_button"></span></button>
                            @endif                                                       
                        </form>                  
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4">
                <div class="card-body text-center p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 152 282" style="font-size: 250px;" class=" " stroke="none"><path stroke="#292D35" stroke-miterlimit="10" stroke-width="2" d="M69.77 164.671s16.247-25.884 25.001-31.001c8.755-5.117 26.314 4.48 23.955 12.185-3.595 11.787-26.53 30.897-26.53 30.897"></path><path fill="#B5B5B5" d="m9.967 225.217 47.249 33.296 21.857 3.957-45.457-38.54-23.65 1.287Z" style="mix-blend-mode: multiply;"></path><path fill="#FFD232" d="m64.764 145.521 48.862 26.543-34.191 90.406-60.166-31.807 45.495-85.142Z"></path><path fill="#FFD232" d="m19.27 231.243 45.565-85.057 48.126 26.072-33.967 89.547-59.724-30.562Z"></path><path fill="#FFD232" d="m57.81 258.099 9.316-6.925 11.947 11.296-21.263-4.371Z"></path><path fill="#FFD232" d="m66.915 251.838 10.83 9.967-19.27-3.859 8.44-6.108ZM113.626 172.028 79.338 262.47l-12.226-11.21 35.497-82.482 11.017 3.25Z"></path><path fill="#FFD232" d="m67.777 251.284 35.192-81.841 9.993 3.016-33.75 89.346-11.435-10.521Z"></path><path fill="#FFD232" d="m57.81 258.483 9.55-6.902 36.963-82.138-46.513 89.04Z"></path><path fill="#FFD232" d="m55.437 143.528-45.47 82.208 47.607 32.747 46.749-88.674-48.886-26.281Z"></path><path fill="#FFE998" d="m10.632 225.617 45.005-81.425 48.022 25.839-46.247 87.788-46.78-32.202Z"></path><path fill="#FFD232" d="m65.84 148.844.608-1.146-11.296-4.17 10.688 5.316Z"></path><path fill="#FFD232" d="m58.475 144.857 7.309 3.127-.396.86-6.913-3.987ZM64.492 145.521l1.956 1.994-2.658-.89.702-1.104Z"></path><path fill="#FFE998" d="m64.81 146.186.974 1.329-1.329-.604.356-.725Z"></path><path fill="url(#cart_empty_svg__a)" d="M67.063 157.772c.063.257.066.521.009.778a1.832 1.832 0 0 1-.34.72 2.13 2.13 0 0 1-.637.546 2.425 2.425 0 0 1-.83.288 2.668 2.668 0 0 1-1.746-.306c-.515-.296-.876-.754-1.009-1.278a1.704 1.704 0 0 1-.008-.777c.057-.258.172-.502.34-.72a2.15 2.15 0 0 1 .636-.547 2.663 2.663 0 0 1 2.577.019c.514.296.875.754 1.008 1.277Z"></path><path fill="url(#cart_empty_svg__b)" d="M62.513 158.543a1.714 1.714 0 0 1-.015-.775c.055-.257.168-.502.332-.72a2.14 2.14 0 0 1 .626-.554c.25-.147.53-.249.825-.301a2.678 2.678 0 0 1 1.754.29c.519.29.886.745 1.025 1.267.064.255.07.518.015.775a1.822 1.822 0 0 1-.332.72 2.14 2.14 0 0 1-.626.554c-.25.147-.53.249-.825.301a2.677 2.677 0 0 1-1.753-.29c-.52-.291-.887-.745-1.026-1.267Z"></path><path fill="url(#cart_empty_svg__c)" d="M63.18 158.569a1.981 1.981 0 0 1 .243-1.5 1.921 1.921 0 0 1 1.231-.872 2.028 2.028 0 0 1 1.51.274c.448.286.77.737.895 1.257a1.98 1.98 0 0 1-.244 1.499 1.948 1.948 0 0 1-1.23.869 2.024 2.024 0 0 1-1.508-.273 2.068 2.068 0 0 1-.898-1.254Z"></path><path fill="url(#cart_empty_svg__d)" d="M63.187 158.584a1.962 1.962 0 0 1 .225-1.504 1.925 1.925 0 0 1 1.228-.878 2.012 2.012 0 0 1 1.508.256c.45.28.775.727.905 1.246a1.971 1.971 0 0 1-.227 1.505 1.937 1.937 0 0 1-1.227.882 2.016 2.016 0 0 1-1.508-.257 2.058 2.058 0 0 1-.904-1.25Z"></path><path fill="url(#cart_empty_svg__e)" d="M63.19 158.616a1.99 1.99 0 0 1 .21-1.509 1.92 1.92 0 0 1 1.21-.899 2.017 2.017 0 0 1 1.51.235c.454.274.786.715.927 1.232a1.982 1.982 0 0 1-.207 1.509 1.922 1.922 0 0 1-1.213.899 2.008 2.008 0 0 1-1.511-.232 2.061 2.061 0 0 1-.925-1.235Z"></path><path fill="url(#cart_empty_svg__f)" d="M63.2 158.642a1.962 1.962 0 0 1 .694-2.089c.207-.157.443-.27.693-.332a1.988 1.988 0 0 1 1.517.203c.46.268.796.709.936 1.226a1.976 1.976 0 0 1-.187 1.509 1.94 1.94 0 0 1-1.2.913 1.989 1.989 0 0 1-1.517-.204 2.029 2.029 0 0 1-.936-1.226Z"></path><path fill="url(#cart_empty_svg__g)" d="M63.206 158.667a1.975 1.975 0 0 1 .167-1.513 1.932 1.932 0 0 1 1.19-.93 2 2 0 0 1 1.517.19c.462.262.805.697.954 1.211a1.984 1.984 0 0 1-.17 1.513 1.956 1.956 0 0 1-.5.587 1.92 1.92 0 0 1-.687.344 2 2 0 0 1-1.517-.191 2.044 2.044 0 0 1-.954-1.211Z"></path><path fill="url(#cart_empty_svg__h)" d="M63.198 158.696a2.367 2.367 0 0 1 .124-1.518c.102-.231.242-.433.41-.597a1.435 1.435 0 0 1 1.833-.176c.387.257.677.685.81 1.196a2.36 2.36 0 0 1-.122 1.518 1.91 1.91 0 0 1-.411.595 1.436 1.436 0 0 1-1.832.175c-.387-.255-.678-.683-.812-1.193Z"></path><path fill="url(#cart_empty_svg__i)" d="M63.206 158.72a2.364 2.364 0 0 1 .105-1.524c.1-.232.238-.437.405-.603.167-.166.36-.289.566-.362a1.437 1.437 0 0 1 1.262.163c.389.249.684.671.825 1.179a2.366 2.366 0 0 1-.106 1.523c-.1.232-.238.437-.405.603a1.522 1.522 0 0 1-.564.362 1.434 1.434 0 0 1-1.263-.162c-.388-.249-.683-.671-.825-1.179Z"></path><path fill="url(#cart_empty_svg__j)" d="M63.214 158.746a2.348 2.348 0 0 1 .086-1.522c.098-.233.233-.441.398-.609.165-.168.356-.294.562-.371.42-.157.874-.11 1.266.133s.691.662.834 1.168a2.35 2.35 0 0 1-.086 1.522 1.925 1.925 0 0 1-.398.609 1.526 1.526 0 0 1-.562.371c-.42.159-.874.112-1.266-.131-.393-.243-.692-.662-.834-1.17Z"></path><path fill="url(#cart_empty_svg__k)" d="M63.22 158.772a2.347 2.347 0 0 1 .07-1.521c.095-.235.227-.444.39-.615a1.55 1.55 0 0 1 .555-.381 1.418 1.418 0 0 1 1.27.108c.396.236.7.65.85 1.156a2.358 2.358 0 0 1-.072 1.524 1.968 1.968 0 0 1-.39.616 1.568 1.568 0 0 1-.556.382 1.42 1.42 0 0 1-1.268-.114c-.395-.236-.699-.651-.848-1.155Z"></path><path fill="url(#cart_empty_svg__l)" d="M63.23 158.802a2.338 2.338 0 0 1 .049-1.524c.092-.237.222-.448.382-.623a1.57 1.57 0 0 1 .552-.392 1.42 1.42 0 0 1 1.271.088c.398.23.707.639.862 1.141a2.347 2.347 0 0 1-.051 1.523 1.976 1.976 0 0 1-.382.623 1.57 1.57 0 0 1-.55.393 1.422 1.422 0 0 1-1.27-.09c-.398-.229-.708-.638-.863-1.139Z"></path><path fill="url(#cart_empty_svg__m)" d="M63.236 158.829a2.348 2.348 0 0 1 .034-1.526c.09-.238.216-.453.375-.63a1.59 1.59 0 0 1 .546-.404c.415-.177.87-.151 1.27.072.4.223.715.626.877 1.125a2.338 2.338 0 0 1-.034 1.523 1.988 1.988 0 0 1-.374.629 1.583 1.583 0 0 1-.546.402c-.415.18-.87.156-1.272-.066-.4-.222-.714-.625-.876-1.125Z"></path><path fill="url(#cart_empty_svg__n)" d="M63.248 158.855a2.325 2.325 0 0 1 .012-1.526c.086-.24.21-.456.367-.636.156-.18.34-.32.542-.411.413-.187.868-.17 1.271.046.403.215.722.613.888 1.11a2.335 2.335 0 0 1-.014 1.525c-.085.24-.21.456-.366.636s-.34.32-.541.412c-.413.186-.869.17-1.272-.046-.403-.215-.72-.614-.887-1.11Z"></path><path fill="url(#cart_empty_svg__o)" d="M63.254 158.899a2.361 2.361 0 0 1-.002-1.534c.084-.243.206-.463.36-.646a1.6 1.6 0 0 1 .538-.423c.407-.196.86-.19 1.264.014.404.205.728.593.903 1.084a2.345 2.345 0 0 1 .006 1.536 2.029 2.029 0 0 1-.362.647 1.597 1.597 0 0 1-.54.42 1.41 1.41 0 0 1-1.263-.014c-.404-.205-.728-.594-.904-1.084Z"></path><path fill="url(#cart_empty_svg__p)" d="M63.266 159.116a1.644 1.644 0 0 1 .327-1.812c.151-.154.332-.276.531-.358.407-.17.864-.171 1.272-.004a1.7 1.7 0 0 1 1.052 1.526 1.66 1.66 0 0 1-.455 1.174 1.615 1.615 0 0 1-.523.364 1.668 1.668 0 0 1-1.28.012 1.704 1.704 0 0 1-.924-.902Z"></path><path fill="url(#cart_empty_svg__q)" d="M63.278 159.136a1.646 1.646 0 0 1 .296-1.816c.149-.157.328-.282.526-.367a1.668 1.668 0 0 1 1.268-.02c.41.161.741.477.925.881a1.64 1.64 0 0 1-.293 1.824c-.15.157-.33.283-.53.368a1.664 1.664 0 0 1-1.27.016c-.41-.162-.74-.48-.922-.886Z"></path><path fill="url(#cart_empty_svg__r)" d="M63.288 159.164a1.648 1.648 0 0 1 .786-2.202 1.676 1.676 0 0 1 1.271-.037 1.709 1.709 0 0 1 1.101 1.496 1.653 1.653 0 0 1-.427 1.194 1.596 1.596 0 0 1-.519.376 1.663 1.663 0 0 1-1.27.043 1.698 1.698 0 0 1-.942-.87Z"></path><path fill="url(#cart_empty_svg__s)" d="M63.3 159.188a1.649 1.649 0 0 1 .237-1.826c.144-.162.319-.293.513-.386.4-.188.856-.21 1.272-.061.415.148.757.456.953.856a1.649 1.649 0 0 1-.238 1.826 1.617 1.617 0 0 1-.513.386 1.67 1.67 0 0 1-1.27.059 1.7 1.7 0 0 1-.954-.854Z"></path><path fill="url(#cart_empty_svg__t)" d="M63.31 159.211a1.651 1.651 0 0 1 .211-1.83c.141-.165.314-.299.507-.395a1.672 1.672 0 0 1 1.27-.079 1.703 1.703 0 0 1 1.145 1.464 1.659 1.659 0 0 1-.39 1.207 1.61 1.61 0 0 1-.506.395 1.675 1.675 0 0 1-1.27.077 1.707 1.707 0 0 1-.967-.839Z"></path><path fill="url(#cart_empty_svg__u)" d="M63.325 159.234a1.638 1.638 0 0 1-.128-1.264c.064-.209.168-.402.307-.569.138-.167.309-.303.5-.401.393-.2.847-.237 1.266-.103.42.134.77.429.98.822a1.652 1.652 0 0 1-.18 1.837c-.138.167-.309.304-.5.403a1.66 1.66 0 0 1-1.267.1 1.696 1.696 0 0 1-.978-.825Z"></path><path fill="url(#cart_empty_svg__v)" d="M63.336 159.258a1.647 1.647 0 0 1 .646-2.246c.39-.207.844-.251 1.265-.123.421.128.777.418.991.808a1.65 1.65 0 0 1-.646 2.251c-.39.205-.844.248-1.265.119a1.702 1.702 0 0 1-.991-.809Z"></path><path fill="url(#cart_empty_svg__w)" d="M63.351 159.286a1.647 1.647 0 0 1-.168-1.262 1.62 1.62 0 0 1 .776-1.001c.386-.212.839-.262 1.262-.141.423.122.782.406 1.002.792a1.645 1.645 0 0 1-.12 1.839 1.6 1.6 0 0 1-.487.418 1.658 1.658 0 0 1-1.262.145 1.688 1.688 0 0 1-1.003-.79Z"></path><path fill="url(#cart_empty_svg__x)" d="M63.364 159.295a1.639 1.639 0 0 1 .568-2.261c.386-.218.84-.274 1.267-.156.426.119.79.401 1.012.788a1.639 1.639 0 0 1-.572 2.262c-.386.215-.84.269-1.264.151a1.694 1.694 0 0 1-1.01-.784Z"></path><path fill="url(#cart_empty_svg__y)" d="M63.38 159.332a1.65 1.65 0 0 1 .527-2.279c.38-.226.832-.293 1.26-.185.427.107.796.38 1.028.759a1.65 1.65 0 0 1-.528 2.28 1.676 1.676 0 0 1-2.288-.575Z"></path><path fill="url(#cart_empty_svg__z)" d="M63.398 159.356a1.658 1.658 0 0 1 .017-1.843c.122-.179.28-.332.461-.449.378-.23.829-.303 1.258-.202.43.1.803.366 1.043.741a1.645 1.645 0 0 1 .236 1.246 1.612 1.612 0 0 1-.715 1.041 1.677 1.677 0 0 1-2.3-.534Z"></path><path fill="url(#cart_empty_svg__A)" d="M63.41 159.38a1.634 1.634 0 0 1-.253-1.245 1.639 1.639 0 0 1 .704-1.051c.371-.239.819-.321 1.249-.23.43.091.807.348 1.053.718a1.646 1.646 0 0 1 .254 1.247 1.647 1.647 0 0 1-.704 1.054c-.371.24-.82.322-1.25.23a1.68 1.68 0 0 1-1.052-.723Z"></path><path fill="url(#cart_empty_svg__B)" d="M63.424 159.403a1.652 1.652 0 0 1 .414-2.305 1.666 1.666 0 0 1 1.247-.249c.432.085.814.337 1.065.704a1.643 1.643 0 0 1 .272 1.241 1.633 1.633 0 0 1-.686 1.064 1.663 1.663 0 0 1-1.247.251 1.686 1.686 0 0 1-1.065-.706Z"></path><path fill="url(#cart_empty_svg__C)" d="M63.444 159.43a1.652 1.652 0 0 1 .372-2.316 1.666 1.666 0 0 1 1.24-.271c.433.077.82.323 1.077.684a1.65 1.65 0 0 1 .294 1.238 1.625 1.625 0 0 1-.667 1.078 1.666 1.666 0 0 1-1.24.271 1.683 1.683 0 0 1-1.076-.684Z"></path><path fill="url(#cart_empty_svg__D)" d="M63.458 159.452a1.64 1.64 0 0 1-.315-1.232 1.653 1.653 0 0 1 .65-1.09c.359-.257.803-.361 1.236-.291.434.07.824.309 1.087.666a1.649 1.649 0 0 1 .315 1.233 1.655 1.655 0 0 1-.65 1.089 1.667 1.667 0 0 1-2.323-.375Z"></path><path fill="url(#cart_empty_svg__E)" d="M63.477 159.475a1.644 1.644 0 0 1-.338-1.225 1.634 1.634 0 0 1 .626-1.103 1.67 1.67 0 0 1 1.233-.312c.436.062.83.295 1.1.648a1.649 1.649 0 0 1 .338 1.223 1.652 1.652 0 0 1-.626 1.101 1.665 1.665 0 0 1-2.333-.332Z"></path><path fill="url(#cart_empty_svg__F)" d="M63.495 159.498a1.642 1.642 0 0 1-.36-1.218 1.632 1.632 0 0 1 .608-1.112 1.664 1.664 0 0 1 2.332.288 1.644 1.644 0 0 1 .364 1.22 1.637 1.637 0 0 1-.608 1.115 1.667 1.667 0 0 1-2.336-.293Z"></path><path fill="url(#cart_empty_svg__G)" d="M66.088 157.476a1.645 1.645 0 0 1 .35 1.222 1.642 1.642 0 0 1-.621 1.105 1.668 1.668 0 0 1-1.231.321 1.682 1.682 0 0 1-1.103-.641 1.645 1.645 0 0 1-.345-1.223 1.656 1.656 0 0 1 .62-1.104 1.659 1.659 0 0 1 2.33.32Z"></path><path fill="url(#cart_empty_svg__H)" d="M66.102 157.491a1.644 1.644 0 0 1 .332 1.228 1.641 1.641 0 0 1-.636 1.097 1.663 1.663 0 0 1-2.323-.349 1.638 1.638 0 0 1-.336-1.227c.028-.216.099-.425.208-.614.108-.189.254-.353.426-.484a1.67 1.67 0 0 1 2.328.349Z"></path><path fill="url(#cart_empty_svg__I)" d="M66.114 157.507a1.642 1.642 0 0 1 .318 1.23 1.638 1.638 0 0 1-.65 1.087 1.656 1.656 0 0 1-1.235.295 1.673 1.673 0 0 1-1.086-.668 1.639 1.639 0 0 1-.318-1.232 1.649 1.649 0 0 1 .65-1.089 1.661 1.661 0 0 1 1.236-.291c.434.071.823.31 1.085.668Z"></path><path fill="url(#cart_empty_svg__J)" d="M66.127 157.523a1.645 1.645 0 0 1 .302 1.234 1.646 1.646 0 0 1-.662 1.079 1.664 1.664 0 0 1-2.32-.402 1.65 1.65 0 0 1-.301-1.236 1.656 1.656 0 0 1 .662-1.082 1.672 1.672 0 0 1 2.32.407Z"></path><path fill="url(#cart_empty_svg__K)" d="M66.14 157.541a1.648 1.648 0 0 1-.39 2.308 1.667 1.667 0 0 1-2.315-.431 1.651 1.651 0 0 1-.286-1.24 1.648 1.648 0 0 1 .676-1.073c.366-.248.812-.34 1.244-.259a1.69 1.69 0 0 1 1.07.695Z"></path><path fill="url(#cart_empty_svg__L)" d="M66.151 157.558a1.646 1.646 0 0 1 .27 1.241 1.642 1.642 0 0 1-.688 1.062 1.667 1.667 0 0 1-2.31-.462 1.643 1.643 0 0 1-.27-1.241 1.637 1.637 0 0 1 .69-1.062 1.662 1.662 0 0 1 1.246-.246c.432.086.813.34 1.062.708Z"></path><path fill="url(#cart_empty_svg__M)" d="M66.165 157.577a1.65 1.65 0 0 1 .253 1.245 1.649 1.649 0 0 1-.702 1.052c-.371.239-.82.321-1.25.229a1.687 1.687 0 0 1-1.054-.721 1.637 1.637 0 0 1-.255-1.245 1.638 1.638 0 0 1 .703-1.052c.371-.24.82-.323 1.251-.231.431.092.81.352 1.054.723Z"></path><path fill="url(#cart_empty_svg__N)" d="M66.175 157.603a1.646 1.646 0 0 1-.476 2.288 1.665 1.665 0 0 1-2.29-.533 1.615 1.615 0 0 1-.249-1.249 1.606 1.606 0 0 1 .727-1.039 1.65 1.65 0 0 1 1.251-.209c.428.099.8.366 1.037.742Z"></path><path fill="url(#cart_empty_svg__O)" d="M66.189 157.606a1.639 1.639 0 0 1 .217 1.261c-.05.214-.14.416-.269.593a1.6 1.6 0 0 1-.475.436c-.374.229-.82.3-1.244.199a1.683 1.683 0 0 1-1.268-1.347 1.663 1.663 0 0 1 .287-1.247c.128-.177.29-.325.475-.436.374-.231.82-.304 1.246-.203.425.101.795.367 1.031.744Z"></path><path fill="url(#cart_empty_svg__P)" d="M66.198 157.635a1.643 1.643 0 0 1 .207 1.254 1.642 1.642 0 0 1-.743 1.025 1.68 1.68 0 0 1-1.259.173 1.701 1.701 0 0 1-1.026-.761 1.64 1.64 0 0 1-.207-1.254c.05-.213.141-.413.269-.589.127-.176.288-.324.473-.435a1.664 1.664 0 0 1 1.26-.178c.427.11.795.384 1.026.765Z"></path><path fill="url(#cart_empty_svg__Q)" d="M66.22 157.655a1.652 1.652 0 0 1 .176 1.255 1.648 1.648 0 0 1-.757 1.01 1.67 1.67 0 0 1-1.263.162 1.688 1.688 0 0 1-1.2-2.034 1.653 1.653 0 0 1 .757-1.013c.386-.218.84-.275 1.267-.159.427.116.792.395 1.02.779Z"></path><path fill="url(#cart_empty_svg__R)" d="M66.226 157.509c.11.151.18.317.209.49.028.173.013.349-.045.517a1.224 1.224 0 0 1-.29.461c-.134.137-.3.25-.49.333a2.04 2.04 0 0 1-1.262.114c-.423-.096-.783-.322-1.004-.63a1.165 1.165 0 0 1-.205-.497 1.08 1.08 0 0 1 .048-.522c.059-.17.159-.329.294-.467.136-.138.304-.252.495-.337.388-.163.84-.198 1.258-.097.42.1.775.328.992.635Z"></path><path fill="url(#cart_empty_svg__S)" d="M66.243 157.521c.105.152.171.319.195.492.024.174.006.349-.054.516-.06.167-.161.323-.296.458a1.57 1.57 0 0 1-.49.33 2.046 2.046 0 0 1-1.272.101c-.423-.102-.781-.335-.997-.649a1.152 1.152 0 0 1-.193-.493 1.083 1.083 0 0 1 .055-.515c.06-.167.16-.322.295-.458a1.6 1.6 0 0 1 .489-.33c.392-.165.847-.2 1.27-.098.423.102.78.334.998.646Z"></path><path fill="url(#cart_empty_svg__T)" d="M66.245 157.534c.105.153.171.322.194.496.023.174.003.35-.06.518a1.254 1.254 0 0 1-.306.457 1.59 1.59 0 0 1-.501.323 2.041 2.041 0 0 1-1.263.085c-.418-.106-.77-.341-.978-.654a1.138 1.138 0 0 1-.196-.496 1.064 1.064 0 0 1 .06-.518c.063-.168.167-.324.307-.458.139-.134.31-.243.502-.322a2.04 2.04 0 0 1 1.263-.085c.419.106.77.341.978.654Z"></path><path fill="url(#cart_empty_svg__U)" d="M66.26 157.556c.1.154.163.323.182.496.02.174-.005.349-.071.515-.066.166-.173.32-.314.452a1.63 1.63 0 0 1-.507.316c-.395.158-.85.184-1.269.072-.418-.112-.765-.352-.966-.67a1.127 1.127 0 0 1-.183-.496c-.019-.174.005-.349.072-.515.066-.166.173-.32.314-.452.14-.131.313-.239.507-.316.394-.158.85-.184 1.268-.072.418.112.765.352.966.67Z"></path><path fill="url(#cart_empty_svg__V)" d="M66.273 157.577a1.074 1.074 0 0 1 .091 1.008 1.265 1.265 0 0 1-.32.445c-.142.13-.315.235-.508.309a2.035 2.035 0 0 1-1.275.063c-.418-.117-.763-.363-.96-.686a1.074 1.074 0 0 1-.091-1.008c.07-.164.178-.316.32-.445.143-.13.316-.235.509-.309.398-.157.856-.18 1.274-.063.419.117.764.363.96.686Z"></path><path fill="url(#cart_empty_svg__W)" d="M66.283 157.591a1.084 1.084 0 0 1 .072 1.017 1.29 1.29 0 0 1-.33.445 1.624 1.624 0 0 1-.518.304 2.06 2.06 0 0 1-1.272.034c-.413-.123-.751-.371-.944-.693a1.078 1.078 0 0 1-.071-1.015c.072-.164.185-.315.33-.443.146-.129.322-.232.517-.304a2.063 2.063 0 0 1 1.27-.035c.413.122.752.369.946.69Z"></path><path fill="url(#cart_empty_svg__X)" d="M66.296 157.609a1.092 1.092 0 0 1 .05 1.019c-.076.164-.191.314-.34.441a1.65 1.65 0 0 1-.522.299 2.077 2.077 0 0 1-1.272.015c-.41-.128-.745-.38-.934-.703a1.09 1.09 0 0 1-.05-1.017c.077-.164.192-.313.34-.44.148-.127.325-.228.522-.298a2.08 2.08 0 0 1 1.27-.016c.41.127.746.378.936.7Z"></path><path fill="url(#cart_empty_svg__Y)" d="M66.307 157.629a1.103 1.103 0 0 1 .03 1.021 1.317 1.317 0 0 1-.348.436c-.15.126-.33.225-.528.292-.409.131-.862.13-1.27-.003-.407-.133-.737-.387-.924-.711a1.103 1.103 0 0 1-.03-1.021 1.33 1.33 0 0 1 .349-.437c.15-.125.33-.224.528-.291.408-.131.862-.13 1.27.003.406.133.737.387.923.711Z"></path><path fill="url(#cart_empty_svg__Z)" d="M66.318 157.651c.085.161.13.333.13.508.002.175-.04.348-.123.509a1.319 1.319 0 0 1-.358.43 1.69 1.69 0 0 1-.536.283 2.06 2.06 0 0 1-1.271-.012c-.406-.138-.73-.4-.903-.728a1.103 1.103 0 0 1-.131-.508c-.002-.174.04-.347.122-.508.082-.162.204-.308.356-.43.153-.123.335-.219.534-.284.41-.129.867-.124 1.273.013.407.138.732.399.907.727Z"></path><path fill="url(#cart_empty_svg__aa)" d="M66.328 157.67a1.1 1.1 0 0 1 .12.511 1.106 1.106 0 0 1-.134.508c-.086.16-.21.305-.366.425-.157.121-.34.214-.542.276a2.059 2.059 0 0 1-1.27-.031c-.404-.143-.722-.408-.89-.739a1.093 1.093 0 0 1-.12-.509c.003-.175.048-.348.134-.508.086-.16.211-.304.367-.424s.34-.213.541-.274a2.057 2.057 0 0 1 1.27.027c.403.143.723.407.89.738Z"></path><path fill="url(#cart_empty_svg__ab)" d="M66.338 157.691a1.091 1.091 0 0 1-.034 1.018c-.09.159-.218.302-.376.42-.159.119-.345.21-.548.268-.414.119-.87.102-1.27-.047s-.713-.418-.873-.751a1.087 1.087 0 0 1 .034-1.016c.09-.159.217-.301.376-.419.158-.118.344-.208.547-.266.414-.121.87-.105 1.27.043s.713.417.874.75Z"></path><path fill="url(#cart_empty_svg__ac)" d="M66.347 157.712a1.085 1.085 0 0 1-.055 1.017c-.092.158-.223.3-.384.415a1.71 1.71 0 0 1-.554.26 2.053 2.053 0 0 1-1.269-.063c-.396-.155-.704-.428-.858-.764a1.082 1.082 0 0 1 .055-1.016c.093-.157.224-.298.385-.413.161-.116.35-.204.553-.259.416-.113.87-.091 1.267.062.397.153.705.426.86.761Z"></path><path fill="url(#cart_empty_svg__ad)" d="M66.357 157.733a1.082 1.082 0 0 1-.077 1.016c-.096.156-.23.296-.393.409-.164.114-.354.2-.559.253a2.06 2.06 0 0 1-1.269-.08c-.394-.16-.696-.438-.842-.776a1.082 1.082 0 0 1 .078-1.014c.096-.157.23-.295.393-.409.163-.113.353-.198.558-.251a2.067 2.067 0 0 1 1.266.08c.394.159.697.435.845.772Z"></path><path fill="url(#cart_empty_svg__ae)" d="M66.367 157.755a1.08 1.08 0 0 1-.1 1.015c-.1.155-.236.292-.402.403a1.737 1.737 0 0 1-.563.245 2.055 2.055 0 0 1-1.268-.098c-.39-.165-.687-.447-.827-.787a1.073 1.073 0 0 1-.076-.514 1.13 1.13 0 0 1 .177-.498c.1-.155.236-.292.402-.402.165-.111.357-.194.562-.244a2.058 2.058 0 0 1 1.266.097c.39.164.688.445.83.783Z"></path><path fill="url(#cart_empty_svg__af)" d="M66.373 157.779c.065.167.088.342.068.516-.02.174-.083.343-.185.496a1.408 1.408 0 0 1-.41.398 1.743 1.743 0 0 1-.57.235 2.05 2.05 0 0 1-1.265-.114c-.388-.17-.68-.455-.813-.796a1.077 1.077 0 0 1-.064-.516c.021-.174.085-.343.188-.496.103-.153.242-.288.41-.397a1.76 1.76 0 0 1 .568-.236 2.042 2.042 0 0 1 1.264.114c.386.17.677.456.809.796Z"></path><path fill="url(#cart_empty_svg__ag)" d="M66.395 157.802a1.314 1.314 0 0 1-.448 1.401 1.284 1.284 0 0 1-.46.227c-.34.091-.703.044-1.01-.131a1.372 1.372 0 0 1-.635-.809 1.319 1.319 0 0 1 .449-1.4c.136-.106.292-.184.459-.228.34-.09.703-.042 1.01.133.306.175.534.464.635.807Z"></path><path fill="url(#cart_empty_svg__ah)" d="M66.403 157.841a1.305 1.305 0 0 1-.134.998 1.288 1.288 0 0 1-.803.599 1.35 1.35 0 0 1-1.015-.157 1.362 1.362 0 0 1-.65-1.339 1.297 1.297 0 0 1 .972-1.088c.346-.082.71-.025 1.014.16.305.184.526.481.616.827Z"></path><path fill="url(#cart_empty_svg__ai)" d="M66.409 157.847a1.326 1.326 0 0 1-.155 1.01 1.285 1.285 0 0 1-.822.585c-.341.078-.7.018-.999-.168a1.372 1.372 0 0 1-.602-.829 1.323 1.323 0 0 1 .152-1.012 1.26 1.26 0 0 1 .824-.583 1.33 1.33 0 0 1 .998.169c.298.186.515.483.603.828Z"></path><path fill="url(#cart_empty_svg__aj)" d="M66.413 157.874a1.313 1.313 0 0 1-.165.998 1.292 1.292 0 0 1-.825.575 1.35 1.35 0 0 1-1.005-.187 1.373 1.373 0 0 1-.593-.841 1.313 1.313 0 0 1 .165-.998 1.292 1.292 0 0 1 .825-.575 1.35 1.35 0 0 1 1.005.187c.299.192.511.494.593.841Z"></path><path fill="url(#cart_empty_svg__ak)" d="M66.419 157.899a1.316 1.316 0 0 1-.188.998 1.268 1.268 0 0 1-.84.555 1.338 1.338 0 0 1-.997-.205 1.375 1.375 0 0 1-.575-.852 1.316 1.316 0 0 1 .188-.999 1.268 1.268 0 0 1 .84-.554c.346-.067.705.006.999.204.293.197.499.503.573.853Z"></path><path fill="url(#cart_empty_svg__al)" d="M65.418 159.446c-.343.073-.7.009-.997-.18a1.382 1.382 0 0 1-.596-.835 1.327 1.327 0 0 1 .167-1.007 1.293 1.293 0 0 1 .828-.576c.343-.075.701-.011.998.178.297.19.51.49.595.837a1.327 1.327 0 0 1-.167 1.007 1.293 1.293 0 0 1-.828.576Z"></path><path fill="url(#cart_empty_svg__am)" d="M65.444 159.442c-.347.079-.71.017-1.011-.171a1.361 1.361 0 0 1-.602-.836 1.3 1.3 0 0 1 .497-1.375 1.28 1.28 0 0 1 .466-.21 1.347 1.347 0 0 1 1.01.173 1.367 1.367 0 0 1 .63 1.348 1.297 1.297 0 0 1-.524.861 1.28 1.28 0 0 1-.466.21Z"></path><path fill="url(#cart_empty_svg__an)" d="M65.48 159.435a1.353 1.353 0 0 1-1.012-.146 1.383 1.383 0 0 1-.63-.814 1.32 1.32 0 0 1 .128-1.011 1.284 1.284 0 0 1 .808-.609c.338-.081.694-.029.996.147.302.176.525.461.625.798a1.322 1.322 0 0 1-.452 1.407 1.295 1.295 0 0 1-.462.228Z"></path><path fill="url(#cart_empty_svg__ao)" d="M65.503 159.428c-.34.093-.704.049-1.013-.124a1.375 1.375 0 0 1-.644-.801 1.32 1.32 0 0 1 .438-1.407 1.3 1.3 0 0 1 .459-.232 1.347 1.347 0 0 1 1.013.128c.308.175.538.464.639.807a1.325 1.325 0 0 1-.109 1.004 1.291 1.291 0 0 1-.783.625Z"></path><path fill="url(#cart_empty_svg__ap)" d="M65.525 159.419a1.34 1.34 0 0 1-1.012-.1 1.373 1.373 0 0 1-.658-.787 1.323 1.323 0 0 1 .082-1.014 1.284 1.284 0 0 1 .775-.643c.338-.102.702-.067 1.014.099a1.365 1.365 0 0 1 .718 1.304 1.32 1.32 0 0 1-.467.9c-.133.11-.287.192-.452.241Z"></path><path fill="url(#cart_empty_svg__aq)" d="M65.553 159.41a1.34 1.34 0 0 1-1.015-.077 1.367 1.367 0 0 1-.746-1.287 1.324 1.324 0 0 1 .447-.911c.13-.114.282-.2.446-.253a1.34 1.34 0 0 1 1.015.08c.316.16.557.437.675.774a1.32 1.32 0 0 1-.376 1.422c-.13.113-.282.199-.446.252Z"></path><path fill="url(#cart_empty_svg__ar)" d="M65.581 159.404a1.348 1.348 0 0 1-1.015-.061 1.375 1.375 0 0 1-.691-.756 1.322 1.322 0 0 1 .036-1.018 1.293 1.293 0 0 1 .748-.678 1.34 1.34 0 0 1 1.015.058c.319.153.566.424.691.759a1.32 1.32 0 0 1-.344 1.432 1.282 1.282 0 0 1-.44.264Z"></path><path fill="url(#cart_empty_svg__as)" d="M65.607 159.392c-.33.122-.694.11-1.015-.036a1.368 1.368 0 0 1-.802-1.252 1.32 1.32 0 0 1 .405-.93c.126-.119.274-.212.436-.272.33-.123.695-.111 1.016.034a1.362 1.362 0 0 1 .8 1.254 1.317 1.317 0 0 1-.404.93c-.126.12-.274.212-.436.272Z"></path><path fill="url(#cart_empty_svg__at)" d="M65.635 159.381c-.328.13-.692.125-1.016-.012a1.37 1.37 0 0 1-.829-1.234 1.32 1.32 0 0 1 .384-.94c.123-.122.269-.218.43-.282a1.332 1.332 0 0 1 1.016.011 1.362 1.362 0 0 1 .828 1.236 1.312 1.312 0 0 1-.384.939 1.257 1.257 0 0 1-.43.282Z"></path><path fill="url(#cart_empty_svg__au)" d="M65.661 159.37a1.33 1.33 0 0 1-1.014.01 1.36 1.36 0 0 1-.736-.709 1.312 1.312 0 0 1 .666-1.749 1.337 1.337 0 0 1 1.015-.008c.327.131.59.387.736.712a1.31 1.31 0 0 1-.243 1.453c-.12.125-.265.224-.424.291Z"></path><path fill="url(#cart_empty_svg__av)" d="M65.214 159.359a.774.774 0 0 1-.762.031c-.247-.123-.45-.372-.565-.693a1.734 1.734 0 0 1-.039-1.017c.044-.164.112-.315.2-.443.088-.128.195-.231.313-.302a.777.777 0 0 1 .762-.033c.248.124.45.373.565.696a1.738 1.738 0 0 1 .037 1.016 1.453 1.453 0 0 1-.2.443 1.027 1.027 0 0 1-.31.302Z"></path><path fill="url(#cart_empty_svg__aw)" d="M65.234 159.347a.778.778 0 0 1-.76.053c-.25-.115-.456-.357-.576-.675a1.74 1.74 0 0 1-.057-1.016c.041-.166.107-.318.193-.449.085-.131.19-.237.307-.311a.773.773 0 0 1 .761-.057c.25.116.456.36.575.679a1.744 1.744 0 0 1 .055 1.015 1.458 1.458 0 0 1-.192.449c-.085.131-.19.237-.306.312Z"></path><path fill="url(#cart_empty_svg__ax)" d="M65.254 159.331a.776.776 0 0 1-.76.08c-.251-.109-.462-.345-.587-.66a1.74 1.74 0 0 1-.074-1.013c.04-.167.102-.322.186-.456a1.07 1.07 0 0 1 .3-.32.776.776 0 0 1 .76-.08c.252.109.462.345.587.66a1.743 1.743 0 0 1 .074 1.013 1.485 1.485 0 0 1-.185.455 1.06 1.06 0 0 1-.301.321Z"></path><path fill="url(#cart_empty_svg__ay)" d="M65.76 159.315a1.33 1.33 0 0 1-1.005.107 1.335 1.335 0 0 1-.675-2.112 1.322 1.322 0 0 1 1.402-.439 1.352 1.352 0 0 1 .957 1.133 1.33 1.33 0 0 1-.281.982c-.11.136-.245.248-.398.329Z"></path><path fill="url(#cart_empty_svg__az)" d="M65.3 159.305a.79.79 0 0 1-.76.122c-.255-.093-.473-.317-.609-.625a1.612 1.612 0 0 1-.13-.49 1.75 1.75 0 0 1 .02-.52c.033-.17.091-.329.17-.468.08-.138.179-.254.292-.34a.786.786 0 0 1 .764-.114c.256.099.472.33.604.642.065.149.106.314.124.485.017.17.009.344-.024.511-.032.166-.09.322-.167.459a1.131 1.131 0 0 1-.285.338Z"></path><path fill="url(#cart_empty_svg__aA)" d="M65.313 159.29a.79.79 0 0 1-.754.145c-.255-.085-.476-.302-.616-.604a1.603 1.603 0 0 1-.14-.484 1.759 1.759 0 0 1 .013-.52c.03-.17.085-.33.161-.471.077-.141.174-.26.285-.349a.787.787 0 0 1 .754-.15c.257.086.478.305.616.609.07.147.117.312.138.484.022.172.017.349-.013.519-.03.17-.084.33-.16.471-.077.141-.173.26-.284.35Z"></path><path fill="url(#cart_empty_svg__aB)" d="M65.331 159.272a.795.795 0 0 1-.75.171c-.258-.078-.482-.289-.626-.587a1.582 1.582 0 0 1-.147-.48 1.753 1.753 0 0 1 .003-.519c.027-.172.079-.334.153-.477.074-.143.169-.264.279-.357.223-.188.493-.251.75-.173.258.078.482.289.626.589.072.145.122.308.147.48.024.172.023.348-.004.519a1.58 1.58 0 0 1-.153.477 1.173 1.173 0 0 1-.278.357Z"></path><path fill="url(#cart_empty_svg__aC)" d="M65.35 159.255c-.221.194-.49.264-.747.194-.258-.07-.486-.273-.635-.567a1.57 1.57 0 0 1-.156-.476 1.763 1.763 0 0 1-.005-.521c.023-.172.073-.336.145-.482a1.2 1.2 0 0 1 .272-.367c.221-.193.49-.262.748-.191.258.07.486.275.634.57.075.142.128.304.155.475.028.17.03.347.006.519a1.589 1.589 0 0 1-.145.481 1.194 1.194 0 0 1-.272.365Z"></path><path fill="url(#cart_empty_svg__aD)" d="M65.896 159.241a1.34 1.34 0 0 1-1.856-.336 1.313 1.313 0 0 1-.232-.991 1.327 1.327 0 0 1 .537-.86c.29-.201.647-.278.993-.215.346.063.654.26.858.55a1.323 1.323 0 0 1-.3 1.852Z"></path><path fill="url(#cart_empty_svg__aE)" d="M65.386 159.224c-.215.206-.481.29-.742.235-.26-.055-.495-.244-.654-.528a1.55 1.55 0 0 1-.169-.467 1.77 1.77 0 0 1-.021-.519c.018-.174.062-.34.129-.49a1.25 1.25 0 0 1 .258-.384c.215-.207.482-.292.742-.237.261.055.496.245.655.53.078.139.135.298.168.467.033.17.04.346.022.519-.019.173-.063.34-.129.49-.066.15-.154.281-.259.384Z"></path><path fill="url(#cart_empty_svg__aF)" d="M65.941 159.205a1.338 1.338 0 0 1-1.865-.251 1.317 1.317 0 0 1-.277-.979 1.326 1.326 0 0 1 .498-.885 1.33 1.33 0 0 1 1.865.251 1.317 1.317 0 0 1 .277.979 1.326 1.326 0 0 1-.498.885Z"></path><path fill="url(#cart_empty_svg__aG)" d="M65.965 159.185a1.332 1.332 0 0 1-1.87-.208 1.323 1.323 0 0 1-.15-1.471c.083-.155.194-.291.329-.4a1.333 1.333 0 0 1 1.87.213 1.309 1.309 0 0 1 .298.971 1.308 1.308 0 0 1-.477.895Z"></path><path fill="url(#cart_empty_svg__aH)" d="M65.987 159.171a1.338 1.338 0 0 1-1.874-.171 1.314 1.314 0 0 1-.32-.966 1.332 1.332 0 0 1 .458-.908 1.33 1.33 0 0 1 1.874.171 1.314 1.314 0 0 1 .32.966 1.33 1.33 0 0 1-.458.908Z"></path><path fill="url(#cart_empty_svg__aI)" d="M66.01 159.15a1.332 1.332 0 0 1-1.875-.129 1.329 1.329 0 0 1-.215-1.463c.075-.157.181-.298.311-.413a1.329 1.329 0 0 1 1.875.129 1.325 1.325 0 0 1-.097 1.876Z"></path><path fill="url(#cart_empty_svg__aJ)" d="M66.028 159.131a1.328 1.328 0 0 1-1.874-.092 1.313 1.313 0 0 1-.246-1.454c.071-.158.174-.301.301-.419a1.323 1.323 0 0 1 1.875.088 1.304 1.304 0 0 1 .363.95 1.327 1.327 0 0 1-.419.927Z"></path><path fill="url(#cart_empty_svg__aK)" d="M66.058 159.111a1.33 1.33 0 0 1-.95.364 1.331 1.331 0 0 1-.928-.416 1.313 1.313 0 0 1-.39-.939 1.33 1.33 0 0 1 .39-.94 1.334 1.334 0 0 1 1.878.051 1.328 1.328 0 0 1 0 1.88Z"></path><path fill="url(#cart_empty_svg__aL)" d="M66.059 159.087a1.332 1.332 0 0 1-2.167-.43 1.323 1.323 0 0 1 .29-1.449 1.327 1.327 0 1 1 1.877 1.879Z"></path><path fill="url(#cart_empty_svg__aM)" d="M66.089 159.073a1.331 1.331 0 0 1-1.88.026 1.324 1.324 0 0 1-.334-1.438c.062-.163.155-.312.274-.439a1.334 1.334 0 0 1 1.88-.03 1.322 1.322 0 0 1 .418.928 1.34 1.34 0 0 1-.358.953Z"></path><path fill="url(#cart_empty_svg__aN)" d="M66.109 159.052a1.33 1.33 0 0 1-1.878.066 1.32 1.32 0 0 1-.365-1.429c.059-.164.149-.315.265-.444a1.324 1.324 0 0 1 1.878-.07 1.316 1.316 0 0 1 .437.918 1.339 1.339 0 0 1-.337.959Z"></path><path fill="url(#cart_empty_svg__aO)" d="M66.125 159.031a1.325 1.325 0 0 1-1.875.104 1.312 1.312 0 0 1-.456-.908 1.334 1.334 0 0 1 .319-.967 1.33 1.33 0 0 1 1.875-.104 1.312 1.312 0 0 1 .456.908 1.334 1.334 0 0 1-.319.967Z"></path><path fill="#000" d="M66.142 159.015a1.332 1.332 0 0 1-1.872.139 1.327 1.327 0 0 1-.175-1.872c.23-.268.557-.434.907-.461.35-.026.697.088.965.318a1.33 1.33 0 0 1 .175 1.876Z"></path><g fill="#6F5439" opacity="0.5"><path d="m65.005 159.62-.134.075-.18.055-.095.025a.442.442 0 0 1-.115 0 1.61 1.61 0 0 1-.26 0 1.323 1.323 0 0 1-.554-.17 1.226 1.226 0 0 1-.46-.46 1.077 1.077 0 0 1-.145-.659 1.812 1.812 0 0 1 0-.195c.01-.047.021-.094.035-.14 0-.046.015-.086.045-.12a1.49 1.49 0 0 1 .115-.199c.04-.062.088-.118.14-.17a1.2 1.2 0 0 1 .18-.18.646.646 0 0 1 .41-.15c.14.017.276.061.399.13.103.057.202.122.295.195.19.138.388.263.594.374-.163.053-.33.094-.5.12h-.224a4.02 4.02 0 0 1-.165.215l-.04.05-.04.035a.286.286 0 0 0-.08.05.5.5 0 0 0-.135.185.501.501 0 0 0 0 .28c.025.105.082.2.165.269.204.203.466.338.75.385Z"></path><path d="M63.462 157.627a.997.997 0 0 1 .125-.365.59.59 0 0 1 .34-.275l.12-.045H64.465c.126-.01.253-.01.38 0 .57.013 1.123.196 1.588.525.35.312.722.598 1.114.854a2.88 2.88 0 0 1-1.169.055 3.282 3.282 0 0 1-1.498-.574l-.28-.2a1.72 1.72 0 0 0-.32-.185.711.711 0 0 0-.42-.05.878.878 0 0 0-.4.26Z"></path><path d="M66.524 157.767c0 .075.04.155.055.235a.92.92 0 0 1 0 .299 1.45 1.45 0 0 1-.265.72c-.124.17-.28.314-.46.424a1.488 1.488 0 0 1-.53.18 1.55 1.55 0 0 1-.499 0 1.763 1.763 0 0 1-.434-.15.633.633 0 0 1-.39-.524.5.5 0 0 1 .225-.41.213.213 0 0 0 .084.044.219.219 0 0 0 .096.006c.05 0 .055-.035.055-.035h.055a1.3 1.3 0 0 1 .124 0 1.3 1.3 0 0 1 .125 0h.125a2.81 2.81 0 0 0 .555-.085c.204-.054.399-.136.58-.245a.794.794 0 0 0 .13-.099s.024-.025.034-.03l.03-.03.06-.05c.1-.09.175-.165.245-.25Z"></path><path d="m65.006 159.62-.135.075-.18.055-.095.025a.442.442 0 0 1-.115 0 1.61 1.61 0 0 1-.26 0 1.324 1.324 0 0 1-.554-.17 1.226 1.226 0 0 1-.46-.46 1.106 1.106 0 0 1-.145-.664 1.881 1.881 0 0 1 0-.19c0-.06 0-.1.035-.145l.045-.115c.032-.07.07-.137.115-.199.04-.062.088-.118.14-.17.054-.068.116-.128.185-.18a.632.632 0 0 1 .404-.15c.14.017.277.061.4.13.104.057.202.122.295.195.19.138.388.263.594.374-.163.053-.33.094-.5.12h-.224c-.055.075-.11.15-.165.215l-.04.05-.04.035a.285.285 0 0 0-.08.05.5.5 0 0 0-.135.185.501.501 0 0 0 0 .28c.025.105.083.2.165.269.204.204.466.338.75.385Z"></path><path d="M66.9 155.733c.097.353.111.725.04 1.084a2.067 2.067 0 0 1-.705 1.264.988.988 0 0 1-.27.2 2.362 2.362 0 0 1-.87.25c.03-.135.05-.25.07-.34l.086-.37c.234-1.274 1.094-1.398 1.648-2.088Z"></path><path d="M66.519 157.767c.025.077.045.155.06.235v.125c.01.057.01.117 0 .174a1.45 1.45 0 0 1-.265.72c-.124.17-.28.314-.46.424a1.473 1.473 0 0 1-.534.18 1.55 1.55 0 0 1-.5 0 1.774 1.774 0 0 1-.43-.15.64.64 0 0 1-.389-.524.5.5 0 0 1 .225-.41.213.213 0 0 0 .084.044.219.219 0 0 0 .096.006c.05 0 .055-.035.055-.035h.055a1.3 1.3 0 0 1 .124 0 1.3 1.3 0 0 1 .125 0h.125a2.81 2.81 0 0 0 .555-.085c.204-.052.4-.134.58-.245a.794.794 0 0 0 .13-.099s.024-.025.034-.03l.03-.03.06-.05a2.45 2.45 0 0 0 .24-.25Z"></path></g><path fill="url(#cart_empty_svg__aP)" d="M88.998 170.003c.054.299.057.607.008.907a2.528 2.528 0 0 1-.292.84 2.15 2.15 0 0 1-.545.638 1.78 1.78 0 0 1-.712.336 1.782 1.782 0 0 1-1.497-.358c-.44-.345-.75-.88-.864-1.49a2.698 2.698 0 0 1-.008-.908c.05-.3.149-.585.293-.839.143-.254.329-.471.545-.638.215-.167.458-.282.712-.336a1.782 1.782 0 0 1 1.496.358c.44.345.75.879.864 1.49Z"></path><path fill="url(#cart_empty_svg__aQ)" d="M85.1 170.89a2.676 2.676 0 0 1-.015-.902c.046-.298.142-.583.283-.837.142-.255.324-.473.538-.643.214-.171.455-.289.709-.348a1.786 1.786 0 0 1 1.498.335c.443.336.757.862.875 1.466.06.297.069.605.025.906-.043.3-.138.588-.278.845-.14.258-.322.479-.536.652a1.8 1.8 0 0 1-.71.355 1.795 1.795 0 0 1-1.513-.343c-.446-.342-.76-.876-.876-1.486Z"></path><path fill="url(#cart_empty_svg__aR)" d="M85.108 171.193a1.977 1.977 0 0 1 .242-1.501 1.939 1.939 0 0 1 1.233-.869 2.02 2.02 0 0 1 1.508.271c.448.286.77.736.896 1.257a1.982 1.982 0 0 1-.244 1.5 1.937 1.937 0 0 1-1.23.87 2.023 2.023 0 0 1-1.507-.273 2.07 2.07 0 0 1-.898-1.255Z"></path><path fill="url(#cart_empty_svg__aS)" d="M85.114 171.213a1.972 1.972 0 0 1 .226-1.506 1.904 1.904 0 0 1 1.228-.879 2.013 2.013 0 0 1 1.508.255c.451.281.776.728.906 1.248a1.976 1.976 0 0 1-.23 1.503 1.933 1.933 0 0 1-1.225.882 2.013 2.013 0 0 1-1.507-.256 2.058 2.058 0 0 1-.906-1.247Z"></path><path fill="url(#cart_empty_svg__aT)" d="M85.118 170.831a1.391 1.391 0 0 1-.045-.647c.036-.216.122-.423.255-.61.132-.187.307-.35.515-.479.208-.129.445-.221.695-.271a2.368 2.368 0 0 1 1.51.196c.454.228.787.596.927 1.027.067.211.083.431.047.647a1.47 1.47 0 0 1-.256.612c-.133.187-.31.35-.518.478-.21.129-.446.22-.698.269a2.347 2.347 0 0 1-1.51-.193c-.454-.229-.784-.598-.922-1.029Z"></path><path fill="url(#cart_empty_svg__aU)" d="M85.134 170.871a1.38 1.38 0 0 1-.068-.648c.03-.217.11-.427.24-.618.128-.19.3-.356.508-.489.207-.132.443-.228.696-.281a2.34 2.34 0 0 1 1.52.17c.46.223.797.59.938 1.022.07.209.09.429.058.645a1.455 1.455 0 0 1-.246.612c-.13.189-.303.353-.51.484a2.087 2.087 0 0 1-.693.278 2.347 2.347 0 0 1-1.505-.169c-.456-.219-.793-.58-.938-1.006Z"></path><path fill="url(#cart_empty_svg__aV)" d="M85.134 170.874a1.386 1.386 0 0 1-.068-.645c.028-.217.109-.427.235-.617a1.75 1.75 0 0 1 .502-.489c.204-.133.438-.23.688-.286a2.363 2.363 0 0 1 1.517.159c.462.218.804.58.954 1.009.073.209.095.428.066.645-.029.216-.109.426-.236.615a1.74 1.74 0 0 1-.5.49 2.079 2.079 0 0 1-.687.286c-.511.117-1.056.06-1.517-.158-.462-.218-.805-.581-.954-1.009Z"></path><path fill="url(#cart_empty_svg__aW)" d="M85.14 170.897a1.395 1.395 0 0 1-.077-.645c.026-.217.102-.428.226-.62.123-.192.29-.361.492-.497s.434-.236.683-.295a2.376 2.376 0 0 1 1.517.149c.464.213.812.57.972.996.077.209.104.428.078.645a1.453 1.453 0 0 1-.225.62c-.123.191-.291.36-.493.496a2.084 2.084 0 0 1-.684.293 2.385 2.385 0 0 1-1.516-.147c-.463-.213-.812-.569-.972-.995Z"></path><path fill="url(#cart_empty_svg__aX)" d="M85.15 170.917a1.395 1.395 0 0 1-.09-.645c.022-.219.095-.431.216-.624.12-.194.286-.365.486-.503s.431-.241.679-.301a2.382 2.382 0 0 1 1.515.135c.466.208.82.559.99.982a1.405 1.405 0 0 1-.128 1.269c-.12.194-.286.365-.486.503s-.43.241-.677.302a2.389 2.389 0 0 1-1.515-.135c-.466-.208-.82-.56-.99-.983Z"></path><path fill="url(#cart_empty_svg__aY)" d="M85.16 170.937a1.388 1.388 0 0 1 .103-1.267c.117-.194.28-.367.477-.507.198-.14.427-.245.674-.309a2.375 2.375 0 0 1 1.52.111c.47.202.83.551 1 .972a1.399 1.399 0 0 1-.102 1.27 1.72 1.72 0 0 1-.478.508c-.198.14-.427.246-.674.31a2.372 2.372 0 0 1-1.521-.112c-.47-.203-.829-.553-1-.976Z"></path><path fill="url(#cart_empty_svg__aZ)" d="M85.168 170.964a1.398 1.398 0 0 1 .082-1.269c.114-.196.273-.37.468-.513.195-.143.422-.252.668-.319a2.365 2.365 0 0 1 1.523.09c.474.197.84.542 1.017.963a1.395 1.395 0 0 1-.082 1.269c-.114.196-.273.37-.468.513a2.058 2.058 0 0 1-.668.319 2.376 2.376 0 0 1-1.522-.092c-.474-.196-.84-.541-1.018-.961Z"></path><path fill="url(#cart_empty_svg__ba)" d="M85.177 170.985a1.407 1.407 0 0 1 .06-1.27 1.68 1.68 0 0 1 .459-.519 2.03 2.03 0 0 1 .661-.328 2.388 2.388 0 0 1 1.524.076c.477.191.849.531 1.036.949a1.405 1.405 0 0 1-.06 1.269c-.11.198-.266.375-.459.52a2.03 2.03 0 0 1-.66.328c-.501.143-1.048.116-1.526-.075-.477-.191-.849-.532-1.035-.95Z"></path><path fill="url(#cart_empty_svg__bb)" d="M85.186 171.009a1.415 1.415 0 0 1 .041-1.271c.107-.199.26-.378.45-.526.19-.148.412-.262.655-.336a2.383 2.383 0 0 1 1.525.059c.48.186.856.522 1.05.938a1.402 1.402 0 0 1-.038 1.268 1.667 1.667 0 0 1-.449.525c-.19.148-.412.262-.655.335-.498.15-1.045.13-1.526-.055-.482-.185-.86-.521-1.053-.937Z"></path><path fill="url(#cart_empty_svg__bc)" d="M85.2 171.03a1.412 1.412 0 0 1-.146-.637c.002-.219.057-.434.16-.634.103-.201.253-.381.44-.531.188-.15.41-.267.651-.342a2.372 2.372 0 0 1 1.526.038c.483.179.865.511 1.065.925.098.202.147.418.144.637a1.426 1.426 0 0 1-.16.634c-.103.2-.253.38-.44.53-.187.15-.408.267-.65.343a2.372 2.372 0 0 1-1.525-.038c-.483-.18-.865-.511-1.065-.925Z"></path><path fill="url(#cart_empty_svg__bd)" d="M85.207 171.053a1.42 1.42 0 0 1-.153-.636c0-.219.05-.435.151-.637.1-.201.248-.384.433-.536.185-.152.404-.271.645-.35a2.368 2.368 0 0 1 1.524.021c.485.174.872.501 1.078.912.102.201.155.418.156.637 0 .218-.051.435-.151.637a1.65 1.65 0 0 1-.434.536 2.006 2.006 0 0 1-.646.35 2.385 2.385 0 0 1-1.523-.023c-.486-.174-.872-.501-1.08-.911Z"></path><path fill="url(#cart_empty_svg__be)" d="M85.222 171.076a1.403 1.403 0 0 1-.03-1.27 1.62 1.62 0 0 1 .421-.541c.183-.155.4-.277.638-.359a2.373 2.373 0 0 1 1.526-.004c.49.167.884.49 1.097.899.105.199.161.414.166.633.005.218-.042.435-.138.638-.096.202-.239.387-.42.542a1.983 1.983 0 0 1-.638.36 2.368 2.368 0 0 1-1.526.001c-.49-.168-.883-.49-1.096-.899Z"></path><path fill="url(#cart_empty_svg__bf)" d="M85.233 171.103a1.412 1.412 0 0 1-.052-1.271 1.63 1.63 0 0 1 .41-.548c.178-.157.392-.283.628-.37a2.378 2.378 0 0 1 1.526-.02c.492.161.891.478 1.113.884a1.405 1.405 0 0 1 .056 1.272c-.092.204-.232.39-.41.548a1.98 1.98 0 0 1-.633.368 2.383 2.383 0 0 1-1.525.019c-.492-.161-.89-.478-1.113-.882Z"></path><path fill="url(#cart_empty_svg__bg)" d="M85.248 171.125a1.403 1.403 0 0 1-.079-1.27c.088-.206.224-.394.4-.554.175-.16.387-.289.623-.378a2.38 2.38 0 0 1 1.526-.037c.495.155.9.466 1.13.869a1.4 1.4 0 0 1 .076 1.269 1.615 1.615 0 0 1-.401.553 1.948 1.948 0 0 1-.625.375 2.366 2.366 0 0 1-1.523.043c-.495-.155-.9-.467-1.127-.87Z"></path><path fill="url(#cart_empty_svg__bh)" d="M85.26 171.148a1.4 1.4 0 0 1-.1-1.267c.084-.207.217-.396.39-.558.172-.162.381-.294.615-.386a2.362 2.362 0 0 1 1.524-.061c.498.148.908.455 1.143.856a1.397 1.397 0 0 1 .104 1.267 1.594 1.594 0 0 1-.389.558 1.916 1.916 0 0 1-.615.386 2.38 2.38 0 0 1-1.526.06c-.499-.148-.91-.455-1.147-.855Z"></path><path fill="url(#cart_empty_svg__bi)" d="M85.276 171.172a1.396 1.396 0 0 1-.127-1.268c.08-.207.21-.399.38-.564.169-.164.376-.298.608-.393a2.367 2.367 0 0 1 1.524-.079c.5.142.916.443 1.159.841a1.397 1.397 0 0 1 .125 1.266c-.08.208-.21.399-.378.564-.17.165-.376.299-.608.395a2.385 2.385 0 0 1-1.523.077c-.5-.142-.916-.443-1.16-.839Z"></path><path fill="url(#cart_empty_svg__bj)" d="M85.293 171.194a1.454 1.454 0 0 1-.23-.619c-.026-.218 0-.437.076-.646.076-.209.201-.403.368-.57.166-.167.371-.304.601-.402a2.372 2.372 0 0 1 1.521-.097c.503.136.923.432 1.173.825.125.191.202.402.229.619.026.217 0 .436-.076.644a1.57 1.57 0 0 1-.368.569 1.91 1.91 0 0 1-.6.401 2.352 2.352 0 0 1-1.522.102c-.503-.135-.924-.431-1.172-.826Z"></path><path fill="url(#cart_empty_svg__bk)" d="M85.304 171.219c-.127-.19-.208-.4-.237-.616a1.394 1.394 0 0 1 .064-.646c.073-.209.194-.404.356-.573.163-.169.365-.309.592-.411a2.36 2.36 0 0 1 1.519-.123c.506.128.933.418 1.19.808.128.19.21.4.24.617.03.217.008.437-.065.647-.072.21-.193.406-.357.575-.163.17-.365.31-.593.412a2.375 2.375 0 0 1-1.52.119c-.505-.129-.932-.419-1.19-.809Z"></path><path fill="url(#cart_empty_svg__bl)" d="M85.324 171.246a1.47 1.47 0 0 1-.253-.613 1.393 1.393 0 0 1 .05-.649c.069-.211.186-.408.346-.58.16-.172.36-.315.586-.42a2.362 2.362 0 0 1 1.514-.141c.508.121.94.405 1.204.791.132.188.218.397.252.613.035.216.018.437-.05.647a1.542 1.542 0 0 1-.346.579 1.87 1.87 0 0 1-.585.419 2.346 2.346 0 0 1-1.514.145c-.508-.121-.94-.404-1.204-.791Z"></path><path fill="url(#cart_empty_svg__bm)" d="M85.34 171.27a1.484 1.484 0 0 1-.264-.609 1.388 1.388 0 0 1 .037-.648 1.54 1.54 0 0 1 .333-.585 1.87 1.87 0 0 1 .575-.429 2.36 2.36 0 0 1 1.513-.164c.51.114.95.392 1.222.775.135.187.225.394.263.611.038.216.025.436-.039.648a1.544 1.544 0 0 1-.334.585 1.857 1.857 0 0 1-.577.427 2.356 2.356 0 0 1-1.51.163c-.51-.114-.946-.392-1.22-.774Z"></path><path fill="url(#cart_empty_svg__bn)" d="M85.358 171.292a1.496 1.496 0 0 1-.277-.605 1.387 1.387 0 0 1 .024-.648c.059-.213.168-.413.32-.59.152-.176.345-.325.566-.437a2.35 2.35 0 0 1 1.51-.183c.513.107.956.379 1.236.758.139.184.233.389.276.605.043.215.035.436-.024.648-.059.213-.168.413-.32.59a1.851 1.851 0 0 1-.566.437 2.35 2.35 0 0 1-1.51.183c-.513-.107-.956-.379-1.235-.758Z"></path><path fill="url(#cart_empty_svg__bo)" d="M85.38 171.317a1.516 1.516 0 0 1-.291-.6 1.387 1.387 0 0 1 .007-.648c.053-.214.157-.416.304-.595.147-.18.336-.332.554-.449.453-.23.994-.303 1.51-.203.514.101.963.367 1.251.742.143.181.242.385.29.599.05.214.047.434-.006.647a1.518 1.518 0 0 1-.305.594c-.147.178-.335.33-.553.447a2.353 2.353 0 0 1-1.509.206c-.515-.099-.965-.365-1.252-.74Z"></path><path fill="url(#cart_empty_svg__bp)" d="M85.395 171.341a1.51 1.51 0 0 1-.301-.596 1.385 1.385 0 0 1-.003-.649c.05-.214.151-.418.297-.598.145-.181.331-.335.548-.453a2.335 2.335 0 0 1 1.498-.231c.516.091.97.349 1.265.718.147.18.25.384.302.598.052.214.053.435.002.65-.05.215-.15.419-.296.6a1.826 1.826 0 0 1-.55.454 2.333 2.333 0 0 1-1.499.23c-.516-.092-.97-.351-1.263-.723Z"></path><path fill="url(#cart_empty_svg__bq)" d="M86.016 171.364a1.652 1.652 0 0 1 .414-2.306 1.67 1.67 0 0 1 1.247-.248 1.69 1.69 0 0 1 1.065.703 1.65 1.65 0 0 1 .272 1.242 1.641 1.641 0 0 1-.686 1.064 1.667 1.667 0 0 1-2.312-.455Z"></path><path fill="url(#cart_empty_svg__br)" d="M86.035 171.391a1.648 1.648 0 0 1-.296-1.238 1.65 1.65 0 0 1 .669-1.078 1.666 1.666 0 0 1 1.24-.271c.433.077.819.323 1.077.684a1.65 1.65 0 0 1 .294 1.238 1.648 1.648 0 0 1-.667 1.078 1.666 1.666 0 0 1-1.24.271 1.687 1.687 0 0 1-1.076-.684Z"></path><path fill="url(#cart_empty_svg__bs)" d="M86.05 171.413a1.65 1.65 0 0 1-.316-1.231 1.655 1.655 0 0 1 .646-1.09 1.67 1.67 0 0 1 1.24-.293c.434.07.825.309 1.09.667a1.657 1.657 0 0 1-.335 2.322c-.36.257-.803.361-1.237.291a1.686 1.686 0 0 1-1.088-.666Z"></path><path fill="url(#cart_empty_svg__bt)" d="M86.07 171.436a1.645 1.645 0 0 1-.34-1.225 1.635 1.635 0 0 1 .626-1.103 1.671 1.671 0 0 1 2.333.336 1.641 1.641 0 0 1 .338 1.225 1.65 1.65 0 0 1-.63 1.099 1.664 1.664 0 0 1-2.328-.332Z"></path><path fill="url(#cart_empty_svg__bu)" d="M86.087 171.459a1.64 1.64 0 0 1-.36-1.218 1.653 1.653 0 0 1 .609-1.112 1.66 1.66 0 0 1 2.333.292 1.645 1.645 0 0 1 .362 1.218 1.65 1.65 0 0 1-.606 1.113 1.674 1.674 0 0 1-2.338-.293Z"></path><path fill="url(#cart_empty_svg__bv)" d="M88.68 169.435a1.645 1.645 0 0 1 .35 1.223 1.653 1.653 0 0 1-.2.617c-.107.19-.25.356-.421.488a1.664 1.664 0 0 1-1.231.322 1.683 1.683 0 0 1-1.104-.642 1.645 1.645 0 0 1-.345-1.223 1.65 1.65 0 0 1 .621-1.105 1.662 1.662 0 0 1 1.229-.321c.435.06.83.29 1.1.641Z"></path><path fill="url(#cart_empty_svg__bw)" d="M88.692 169.452a1.633 1.633 0 0 1 .334 1.227 1.627 1.627 0 0 1-.638 1.097 1.667 1.667 0 0 1-2.326-.349 1.645 1.645 0 0 1-.33-1.227 1.624 1.624 0 0 1 .634-1.097 1.658 1.658 0 0 1 1.232-.306c.435.065.827.3 1.094.655Z"></path><path fill="url(#cart_empty_svg__bx)" d="M88.707 169.468a1.65 1.65 0 0 1 .317 1.232 1.653 1.653 0 0 1-.648 1.09c-.36.256-.805.36-1.239.289a1.689 1.689 0 0 1-1.088-.667 1.648 1.648 0 0 1-.314-1.233 1.655 1.655 0 0 1 .65-1.088 1.662 1.662 0 0 1 1.237-.291c.434.07.823.31 1.085.668Z"></path><path fill="url(#cart_empty_svg__by)" d="M88.717 169.484a1.644 1.644 0 0 1-.36 2.314 1.67 1.67 0 0 1-2.322-.402 1.66 1.66 0 0 1 .365-2.319c.363-.252.807-.35 1.24-.274.433.076.819.32 1.077.681Z"></path><path fill="url(#cart_empty_svg__bz)" d="M88.731 169.502a1.645 1.645 0 0 1 .286 1.237 1.644 1.644 0 0 1-.675 1.071 1.662 1.662 0 0 1-1.244.262 1.678 1.678 0 0 1-1.07-.694 1.64 1.64 0 0 1-.287-1.24 1.63 1.63 0 0 1 .676-1.073 1.672 1.672 0 0 1 2.314.437Z"></path><path fill="url(#cart_empty_svg__bA)" d="M88.743 169.519a1.64 1.64 0 0 1 .27 1.241 1.623 1.623 0 0 1-.689 1.062 1.66 1.66 0 0 1-1.246.246 1.68 1.68 0 0 1-1.061-.708 1.636 1.636 0 0 1-.273-1.242 1.622 1.622 0 0 1 .69-1.061 1.665 1.665 0 0 1 2.31.462Z"></path><path fill="url(#cart_empty_svg__bB)" d="M88.149 169.536c.097.18.165.383.2.597.035.214.035.434.002.649-.033.214-.1.417-.197.598-.096.181-.22.336-.364.455a1.116 1.116 0 0 1-1 .229c-.345-.092-.647-.351-.844-.722a1.874 1.874 0 0 1-.201-.596 2.06 2.06 0 0 1-.003-.649c.034-.215.1-.418.197-.599a1.5 1.5 0 0 1 .366-.454 1.116 1.116 0 0 1 1-.229c.345.092.647.351.844.721Z"></path><path fill="url(#cart_empty_svg__bC)" d="M88.157 169.564c.096.181.163.385.195.599.033.214.031.434-.004.648a1.883 1.883 0 0 1-.203.594 1.479 1.479 0 0 1-.369.447c-.3.231-.659.305-1 .205-.342-.099-.64-.364-.831-.738a1.858 1.858 0 0 1-.202-.599 2.031 2.031 0 0 1 .002-.651c.036-.214.106-.417.206-.596.1-.178.228-.329.375-.442.3-.234.659-.31 1.001-.21.343.1.64.367.83.743Z"></path><path fill="url(#cart_empty_svg__bD)" d="M88.168 169.566c.094.184.159.391.188.608.03.217.025.439-.014.654-.04.214-.113.416-.215.593a1.437 1.437 0 0 1-.38.435c-.3.229-.656.301-.996.2-.34-.101-.635-.366-.826-.74a1.907 1.907 0 0 1-.188-.607c-.03-.217-.025-.44.014-.654.04-.214.113-.416.215-.593a1.44 1.44 0 0 1 .38-.436c.3-.231.657-.304.997-.204.34.101.636.368.825.744Z"></path><path fill="url(#cart_empty_svg__bE)" d="M88.175 169.594c.092.185.155.391.183.606.028.216.022.437-.017.649-.04.213-.113.413-.215.589-.103.176-.231.325-.38.436a1.113 1.113 0 0 1-1.006.174c-.341-.11-.635-.383-.822-.761a1.92 1.92 0 0 1-.183-.607 2.063 2.063 0 0 1 .018-.649c.04-.212.113-.413.215-.589s.23-.324.379-.436a1.108 1.108 0 0 1 1.007-.175c.341.11.636.383.821.763Z"></path><path fill="url(#cart_empty_svg__bF)" d="M88.192 169.615c.088.187.146.394.17.61.025.215.015.435-.028.645a1.852 1.852 0 0 1-.223.582 1.447 1.447 0 0 1-.382.428c-.308.219-.67.278-1.011.163-.341-.115-.633-.393-.814-.777a1.944 1.944 0 0 1-.172-.61 2.073 2.073 0 0 1 .026-.647c.043-.211.119-.41.223-.584.103-.174.234-.32.383-.43a1.113 1.113 0 0 1 1.013-.158c.341.115.634.395.815.778Z"></path><path fill="url(#cart_empty_svg__bG)" d="M88.196 169.635c.088.188.145.397.168.613.023.217.011.437-.034.648a1.828 1.828 0 0 1-.231.579c-.108.171-.24.313-.392.418-.31.213-.673.264-1.013.144-.339-.121-.628-.405-.805-.791a2.056 2.056 0 0 1-.125-1.26c.092-.425.313-.785.615-1.003.31-.211.674-.261 1.013-.139.339.121.627.405.804.791Z"></path><path fill="url(#cart_empty_svg__bH)" d="M88.212 169.657c.083.19.136.4.156.616.019.217.004.436-.044.645a1.818 1.818 0 0 1-.237.572c-.108.17-.241.31-.392.412-.314.21-.679.255-1.018.127-.34-.128-.626-.419-.798-.811a1.99 1.99 0 0 1-.154-.616 2.08 2.08 0 0 1 .045-.643c.048-.209.128-.403.235-.573.108-.169.24-.309.39-.413a1.104 1.104 0 0 1 1.017-.123c.339.128.625.417.8.807Z"></path><path fill="url(#cart_empty_svg__bI)" d="M88.213 169.674a2.043 2.043 0 0 1 .107 1.267c-.05.209-.133.404-.244.571-.111.168-.248.305-.401.404-.313.201-.676.24-1.01.107-.335-.133-.616-.427-.783-.819a1.928 1.928 0 0 1-.157-.619 2.038 2.038 0 0 1 .048-.648c.05-.209.134-.404.245-.571.111-.168.248-.305.403-.404a1.09 1.09 0 0 1 1.01-.106c.335.133.615.427.782.818Z"></path><path fill="url(#cart_empty_svg__bJ)" d="M88.224 169.701a2.031 2.031 0 0 1 .09 1.264 1.78 1.78 0 0 1-.252.565c-.113.164-.25.299-.405.395-.316.197-.68.23-1.015.09-.335-.14-.612-.44-.773-.837a2.034 2.034 0 0 1-.09-1.265c.054-.207.14-.399.252-.564a1.37 1.37 0 0 1 .406-.395c.315-.198.68-.23 1.014-.09.335.139.613.44.773.837Z"></path><path fill="url(#cart_empty_svg__bK)" d="M88.235 169.727a2.024 2.024 0 0 1 .073 1.26 1.757 1.757 0 0 1-.256.557 1.356 1.356 0 0 1-.407.386 1.086 1.086 0 0 1-1.02.079c-.334-.146-.61-.454-.767-.857a2.027 2.027 0 0 1-.073-1.26c.056-.206.143-.395.257-.557.113-.162.252-.293.406-.386a1.083 1.083 0 0 1 1.02-.079c.334.146.61.454.767.857Z"></path><path fill="url(#cart_empty_svg__bL)" d="M88.245 169.745a2.047 2.047 0 0 1 .056 1.27 1.75 1.75 0 0 1-.264.555 1.356 1.356 0 0 1-.413.382 1.098 1.098 0 0 1-1.017.045c-.33-.153-.602-.462-.757-.863a2.038 2.038 0 0 1-.058-1.271 1.76 1.76 0 0 1 .265-.556 1.34 1.34 0 0 1 .415-.38 1.095 1.095 0 0 1 1.016-.045c.33.153.602.462.757.863Z"></path><path fill="url(#cart_empty_svg__bM)" d="M88.254 169.768a2.05 2.05 0 0 1 .04 1.273c-.061.205-.153.392-.271.551-.119.159-.26.286-.418.374a1.102 1.102 0 0 1-1.016.02c-.328-.159-.596-.472-.748-.875a2.042 2.042 0 0 1-.041-1.274c.06-.205.153-.393.271-.551.119-.159.261-.286.42-.373.323-.172.687-.18 1.015-.021.328.159.596.473.748.876Z"></path><path fill="url(#cart_empty_svg__bN)" d="M88.263 169.793a2.05 2.05 0 0 1 .023 1.275 1.743 1.743 0 0 1-.278.546c-.12.156-.264.28-.422.364a1.107 1.107 0 0 1-1.015-.001c-.326-.165-.59-.482-.74-.886a2.057 2.057 0 0 1-.024-1.278c.064-.204.158-.39.278-.547.12-.157.265-.282.423-.366.327-.164.69-.162 1.016.004.325.166.59.483.739.889Z"></path><path fill="url(#cart_empty_svg__bO)" d="M88.272 169.82a2.023 2.023 0 0 1 .005 1.272 1.715 1.715 0 0 1-.287.537 1.299 1.299 0 0 1-.428.353 1.09 1.09 0 0 1-1.017-.015c-.325-.172-.584-.499-.722-.91a2.023 2.023 0 0 1-.007-1.27c.065-.201.162-.384.284-.537.123-.153.268-.274.428-.355a1.101 1.101 0 0 1 1.018.017c.325.172.585.498.725.908Z"></path><path fill="url(#cart_empty_svg__bP)" d="M88.28 169.844a2.024 2.024 0 0 1-.011 1.273 1.683 1.683 0 0 1-.294.532 1.27 1.27 0 0 1-.433.344c-.33.155-.695.142-1.017-.037-.322-.18-.577-.511-.711-.925a2.016 2.016 0 0 1 .011-1.27c.069-.2.169-.381.294-.531.124-.15.272-.266.433-.342.329-.157.693-.145 1.016.033.322.179.578.509.712.923Z"></path><path fill="url(#cart_empty_svg__bQ)" d="M88.287 169.87a2.025 2.025 0 0 1-.027 1.272 1.674 1.674 0 0 1-.3.525 1.275 1.275 0 0 1-.439.334c-.332.15-.696.13-1.017-.056-.32-.186-.57-.524-.698-.941a2.016 2.016 0 0 1 .028-1.269 1.66 1.66 0 0 1 .3-.524c.127-.147.276-.26.438-.333.331-.15.695-.131 1.016.054.32.185.57.522.7.938Z"></path><path fill="url(#cart_empty_svg__bR)" d="M88.292 169.895a2.017 2.017 0 0 1-.04 1.271 1.632 1.632 0 0 1-.307.519c-.129.145-.28.256-.443.325-.334.143-.7.116-1.018-.077-.318-.193-.564-.536-.686-.956a2.037 2.037 0 0 1 .046-1.269c.074-.196.178-.372.307-.516.129-.145.279-.255.442-.324a1.096 1.096 0 0 1 1.013.077c.318.192.564.532.686.95Z"></path><path fill="url(#cart_empty_svg__bS)" d="M88.3 169.923a2.024 2.024 0 0 1-.057 1.27 1.631 1.631 0 0 1-.315.513 1.227 1.227 0 0 1-.447.314c-.336.135-.7.099-1.016-.1-.316-.2-.557-.548-.674-.97a2.027 2.027 0 0 1 .062-1.267c.077-.195.184-.369.315-.51.13-.142.282-.248.446-.314.335-.135.698-.1 1.013.099.314.198.556.544.672.965Z"></path><path fill="url(#cart_empty_svg__bT)" d="M88.306 169.952a2.024 2.024 0 0 1-.069 1.259 1.616 1.616 0 0 1-.315.506 1.234 1.234 0 0 1-.447.312 1.118 1.118 0 0 1-1.024-.124c-.315-.206-.555-.56-.668-.985a2.028 2.028 0 0 1 .082-1.268c.08-.194.19-.365.324-.504s.288-.243.455-.305c.337-.12.697-.072 1.007.135.31.206.544.555.655.974Z"></path><path fill="url(#cart_empty_svg__bU)" d="M88.317 169.985a2.033 2.033 0 0 1-.096 1.262c-.082.192-.193.36-.328.496a1.215 1.215 0 0 1-.454.295c-.34.119-.703.067-1.014-.146-.31-.214-.543-.571-.65-.997a2.05 2.05 0 0 1 .1-1.263c.082-.191.194-.36.328-.495.135-.136.29-.236.455-.295a1.1 1.1 0 0 1 1.012.145c.31.213.542.571.647.998Z"></path><path fill="url(#cart_empty_svg__bV)" d="M88.323 170.015c.049.209.064.428.045.644a1.93 1.93 0 0 1-.158.615 1.567 1.567 0 0 1-.335.487 1.169 1.169 0 0 1-.459.282 1.107 1.107 0 0 1-1.011-.163c-.308-.219-.536-.582-.637-1.011a2.054 2.054 0 0 1-.041-.645c.02-.217.074-.426.159-.615.085-.19.199-.356.335-.489s.292-.23.459-.285a1.103 1.103 0 0 1 1.01.166c.307.22.534.584.633 1.014Z"></path><path fill="url(#cart_empty_svg__bW)" d="M88.33 170.058c.045.208.057.426.035.641-.023.214-.08.421-.167.607a1.542 1.542 0 0 1-.341.477 1.12 1.12 0 0 1-1.477.075c-.305-.229-.526-.6-.617-1.033a2.025 2.025 0 0 1-.034-.642 1.92 1.92 0 0 1 .168-.609c.088-.187.204-.349.343-.478a1.19 1.19 0 0 1 .463-.272 1.123 1.123 0 0 1 1.012.199c.304.231.525.602.614 1.035Z"></path><path fill="url(#cart_empty_svg__bX)" d="M88.336 170.472a1.326 1.326 0 0 1-.154 1.01 1.275 1.275 0 0 1-.823.585 1.33 1.33 0 0 1-.999-.168 1.373 1.373 0 0 1-.603-.829 1.326 1.326 0 0 1 .155-1.01 1.284 1.284 0 0 1 .822-.584c.342-.078.7-.017.998.168.299.186.515.483.604.828Z"></path><path fill="url(#cart_empty_svg__bY)" d="M88.34 170.499a1.311 1.311 0 0 1-.52 1.37 1.27 1.27 0 0 1-.469.203 1.35 1.35 0 0 1-1.006-.187 1.373 1.373 0 0 1-.592-.841 1.311 1.311 0 0 1 .52-1.37 1.27 1.27 0 0 1 .47-.203 1.35 1.35 0 0 1 1.005.187c.298.192.51.494.593.841Z"></path><path fill="url(#cart_empty_svg__bZ)" d="M88.347 170.524a1.316 1.316 0 0 1-.188.998 1.268 1.268 0 0 1-.84.555 1.338 1.338 0 0 1-.998-.205 1.375 1.375 0 0 1-.574-.852 1.318 1.318 0 0 1 .188-.998 1.268 1.268 0 0 1 .84-.555c.346-.067.704.006.998.204.294.197.5.504.574.853Z"></path><path fill="url(#cart_empty_svg__ca)" d="M87.35 171.414c-.343.055-.701.006-.998-.136-.298-.141-.513-.365-.6-.625a.754.754 0 0 1-.016-.39.863.863 0 0 1 .185-.365 1.16 1.16 0 0 1 .356-.28c.142-.075.302-.126.47-.152a1.7 1.7 0 0 1 .999.133c.297.143.51.368.594.628.04.128.046.26.018.39a.858.858 0 0 1-.183.365 1.17 1.17 0 0 1-.355.28 1.494 1.494 0 0 1-.47.152Z"></path><path fill="url(#cart_empty_svg__cb)" d="M87.377 171.41c-.344.06-.705.016-1.007-.122-.302-.139-.522-.362-.612-.621a.75.75 0 0 1-.026-.389.847.847 0 0 1 .175-.367c.09-.112.21-.209.35-.285.14-.076.3-.13.468-.159.347-.058.71-.011 1.011.131.302.142.518.369.602.631.041.126.049.256.022.384a.839.839 0 0 1-.176.36c-.09.11-.207.205-.346.28a1.524 1.524 0 0 1-.461.157Z"></path><path fill="url(#cart_empty_svg__cc)" d="M87.401 171.406a1.743 1.743 0 0 1-1.008-.109c-.305-.134-.53-.353-.627-.61a.752.752 0 0 1-.038-.388.832.832 0 0 1 .165-.369c.087-.113.203-.212.34-.29.139-.079.296-.135.464-.166a1.726 1.726 0 0 1 1.008.107c.304.135.528.354.622.612.047.126.06.258.039.388a.828.828 0 0 1-.164.368 1.12 1.12 0 0 1-.34.29 1.467 1.467 0 0 1-.46.167Z"></path><path fill="url(#cart_empty_svg__cd)" d="M87.426 171.4c-.34.07-.702.037-1.01-.092-.308-.13-.539-.345-.643-.601a.755.755 0 0 1-.048-.387.826.826 0 0 1 .154-.371c.083-.115.196-.215.332-.296.135-.081.29-.14.457-.174a1.73 1.73 0 0 1 1.01.092c.309.129.54.345.642.601a.76.76 0 0 1 .049.388.827.827 0 0 1-.154.371 1.106 1.106 0 0 1-.332.295c-.136.081-.291.14-.457.174Z"></path><path fill="url(#cart_empty_svg__ce)" d="M87.453 171.394c-.337.076-.7.049-1.012-.076-.312-.124-.548-.336-.659-.59a.761.761 0 0 1-.06-.387.81.81 0 0 1 .143-.374c.08-.116.19-.218.323-.301.134-.083.287-.145.453-.181.337-.076.7-.049 1.011.075.312.125.548.336.66.591a.76.76 0 0 1-.084.76 1.09 1.09 0 0 1-.323.302 1.459 1.459 0 0 1-.452.181Z"></path><path fill="url(#cart_empty_svg__cf)" d="M87.48 171.387a1.742 1.742 0 0 1-1.014-.058c-.316-.119-.558-.327-.675-.58a.762.762 0 0 1 .061-.761c.077-.118.183-.222.314-.308.13-.085.282-.149.447-.189.335-.081.699-.059 1.014.06.315.12.557.328.674.581a.76.76 0 0 1-.06.761 1.067 1.067 0 0 1-.314.306 1.43 1.43 0 0 1-.447.188Z"></path><path fill="url(#cart_empty_svg__cg)" d="M87.509 171.382a1.762 1.762 0 0 1-1.015-.045c-.318-.114-.566-.317-.692-.567a.774.774 0 0 1 .037-.763c.073-.119.177-.225.306-.313a1.42 1.42 0 0 1 .441-.196 1.748 1.748 0 0 1 1.016.043c.318.115.566.319.69.569a.772.772 0 0 1-.038.762 1.054 1.054 0 0 1-.305.313 1.438 1.438 0 0 1-.44.197Z"></path><path fill="url(#cart_empty_svg__ch)" d="M87.535 171.373c-.33.092-.695.083-1.016-.026-.322-.109-.575-.309-.706-.557a.78.78 0 0 1-.095-.383.784.784 0 0 1 .11-.38c.069-.12.17-.228.295-.317.126-.09.274-.159.435-.204.33-.093.695-.083 1.017.025.322.109.575.309.706.558a.773.773 0 0 1-.015.763c-.07.119-.17.227-.296.317a1.407 1.407 0 0 1-.435.204Z"></path><path fill="url(#cart_empty_svg__ci)" d="M87.563 171.366a1.758 1.758 0 0 1-1.017-.01c-.324-.103-.583-.299-.722-.545a.78.78 0 0 1-.106-.381.777.777 0 0 1 .097-.382c.067-.121.164-.231.287-.322.123-.092.269-.164.429-.212a1.752 1.752 0 0 1 1.017.008c.324.104.583.3.721.546.069.121.105.25.107.381a.783.783 0 0 1-.098.383c-.066.121-.163.23-.286.322a1.374 1.374 0 0 1-.43.212Z"></path><path fill="url(#cart_empty_svg__cj)" d="M87.59 171.358a1.767 1.767 0 0 1-1.016.007c-.327-.098-.592-.288-.74-.531a.775.775 0 0 1-.028-.765c.063-.122.157-.234.277-.328.12-.094.264-.169.423-.22a1.749 1.749 0 0 1 1.016-.006c.326.098.59.29.735.534a.771.771 0 0 1 .032.763.994.994 0 0 1-.276.327 1.36 1.36 0 0 1-.422.219Z"></path><path fill="url(#cart_empty_svg__ck)" d="M87.619 171.349a1.761 1.761 0 0 1-1.017.023c-.33-.092-.601-.279-.755-.52a.767.767 0 0 1-.052-.762.98.98 0 0 1 .268-.332c.117-.096.259-.173.416-.226a1.741 1.741 0 0 1 1.016-.026c.33.093.6.28.75.522a.766.766 0 0 1 .054.762.975.975 0 0 1-.265.332 1.34 1.34 0 0 1-.415.227Z"></path><path fill="url(#cart_empty_svg__cl)" d="M87.644 171.34a1.75 1.75 0 0 1-1.014.04c-.332-.087-.608-.268-.769-.506a.764.764 0 0 1-.076-.763.962.962 0 0 1 .257-.337c.115-.098.254-.177.41-.233.318-.114.682-.13 1.015-.043.333.088.608.27.767.51a.765.765 0 0 1 .074.761.963.963 0 0 1-.256.337 1.308 1.308 0 0 1-.409.234Z"></path><path fill="url(#cart_empty_svg__cm)" d="M87.67 171.328a1.747 1.747 0 0 1-1.014.06c-.335-.082-.615-.259-.782-.495a.826.826 0 0 1-.15-.373.759.759 0 0 1 .052-.387.939.939 0 0 1 .247-.342c.11-.1.248-.181.401-.24.315-.12.678-.141 1.013-.06.335.081.616.259.782.495a.82.82 0 0 1 .15.372.76.76 0 0 1-.052.388.943.943 0 0 1-.246.341c-.111.1-.248.182-.402.241Z"></path><path fill="url(#cart_empty_svg__cn)" d="M87.688 171.316c-.31.125-.67.154-1.006.08-.336-.074-.621-.245-.796-.476a.837.837 0 0 1-.16-.372.764.764 0 0 1 .043-.39.928.928 0 0 1 .239-.346c.109-.102.243-.187.395-.248.31-.126.67-.155 1.007-.081.336.074.622.245.796.477.086.115.14.241.16.372a.763.763 0 0 1-.042.391.936.936 0 0 1-.239.346c-.11.102-.244.186-.397.247Z"></path><path fill="url(#cart_empty_svg__co)" d="M87.721 171.308a1.735 1.735 0 0 1-1.007.092c-.34-.07-.629-.238-.809-.468a.84.84 0 0 1-.174-.366.752.752 0 0 1 .029-.389.91.91 0 0 1 .225-.35c.105-.104.237-.191.387-.255a1.72 1.72 0 0 1 1.008-.093c.34.07.63.238.808.469a.84.84 0 0 1 .174.366.755.755 0 0 1-.028.39.9.9 0 0 1-.226.349 1.243 1.243 0 0 1-.387.255Z"></path><path fill="url(#cart_empty_svg__cp)" d="M87.749 171.297a1.733 1.733 0 0 1-1.005.109c-.341-.064-.636-.227-.823-.453a.856.856 0 0 1-.185-.364.749.749 0 0 1 .016-.389.895.895 0 0 1 .215-.354 1.24 1.24 0 0 1 .38-.261 1.712 1.712 0 0 1 1.006-.112c.342.064.637.228.821.456.093.11.155.234.184.363a.752.752 0 0 1-.017.389.897.897 0 0 1-.214.354c-.102.105-.23.195-.378.262Z"></path><path fill="url(#cart_empty_svg__cq)" d="M87.772 171.283a1.701 1.701 0 0 1-1 .128c-.343-.058-.642-.216-.835-.44a.864.864 0 0 1-.196-.36.747.747 0 0 1 .005-.389.877.877 0 0 1 .204-.357c.099-.108.225-.199.371-.268a1.703 1.703 0 0 1 1.001-.13c.343.059.643.217.834.442a.869.869 0 0 1 .196.36.746.746 0 0 1-.004.389.87.87 0 0 1-.204.357 1.188 1.188 0 0 1-.372.268Z"></path><path fill="url(#cart_empty_svg__cr)" d="M87.797 171.271a1.7 1.7 0 0 1-.996.145c-.344-.052-.647-.205-.846-.426a.88.88 0 0 1-.207-.357.749.749 0 0 1-.008-.39.86.86 0 0 1 .193-.362c.096-.11.22-.203.364-.276a1.71 1.71 0 0 1 .996-.142c.344.053.647.206.846.427.1.106.17.228.207.356.037.128.04.261.008.39a.866.866 0 0 1-.194.36c-.095.109-.219.203-.363.275Z"></path><path fill="url(#cart_empty_svg__cs)" d="M87.825 171.26c-.292.15-.649.207-.995.16-.346-.047-.655-.195-.86-.412a.896.896 0 0 1-.217-.353.753.753 0 0 1-.018-.39.847.847 0 0 1 .184-.364c.092-.111.213-.207.355-.281.29-.151.647-.208.993-.161.346.047.653.195.857.413a.893.893 0 0 1 .216.353c.04.127.046.259.019.389a.858.858 0 0 1-.182.364 1.165 1.165 0 0 1-.352.282Z"></path><path fill="url(#cart_empty_svg__ct)" d="M87.847 171.247a1.693 1.693 0 0 1-.99.177c-.347-.041-.66-.183-.873-.397a.913.913 0 0 1-.224-.35.758.758 0 0 1-.03-.389.853.853 0 0 1 .172-.368c.09-.112.207-.21.346-.288.286-.155.64-.218.987-.177.347.041.66.184.87.397a.918.918 0 0 1 .227.35.756.756 0 0 1 .031.389.842.842 0 0 1-.17.368 1.134 1.134 0 0 1-.346.288Z"></path><path fill="url(#cart_empty_svg__cu)" d="M87.869 171.233c-.282.16-.635.229-.983.194a1.49 1.49 0 0 1-.882-.382.93.93 0 0 1-.236-.346.757.757 0 0 1-.041-.388.835.835 0 0 1 .16-.371c.086-.113.2-.213.338-.293.281-.16.634-.231.983-.196.348.036.665.173.882.384a.93.93 0 0 1 .236.346.76.76 0 0 1 .04.389.836.836 0 0 1-.16.37 1.13 1.13 0 0 1-.337.293Z"></path><path fill="url(#cart_empty_svg__cv)" d="M87.89 171.218a1.66 1.66 0 0 1-.975.212 1.5 1.5 0 0 1-.893-.368.94.94 0 0 1-.246-.342.763.763 0 0 1-.052-.388.82.82 0 0 1 .15-.373 1.1 1.1 0 0 1 .327-.3c.277-.165.628-.24.977-.21.35.03.67.163.892.37.111.1.195.216.247.341.051.125.07.257.053.387a.818.818 0 0 1-.15.373 1.097 1.097 0 0 1-.33.298Z"></path><path fill="url(#cart_empty_svg__cw)" d="M87.915 171.208a1.666 1.666 0 0 1-.971.224 1.531 1.531 0 0 1-.903-.352.972.972 0 0 1-.256-.338.768.768 0 0 1 .075-.763c.078-.117.187-.22.319-.305.272-.17.62-.251.971-.227.35.024.674.151.902.355a.974.974 0 0 1 .256.338.767.767 0 0 1-.074.762 1.095 1.095 0 0 1-.32.306Z"></path><path fill="url(#cart_empty_svg__cx)" d="M87.937 171.192c-.267.173-.613.26-.963.242a1.538 1.538 0 0 1-.912-.339.972.972 0 0 1-.267-.333.77.77 0 0 1 .052-.764c.076-.118.182-.223.312-.31a1.63 1.63 0 0 1 .963-.243c.35.018.678.14.911.34.117.096.207.21.266.333a.775.775 0 0 1-.052.764c-.076.118-.18.223-.31.31Z"></path><path fill="url(#cart_empty_svg__cy)" d="M87.956 171.177c-.262.178-.604.27-.955.258a1.55 1.55 0 0 1-.92-.323.999.999 0 0 1-.276-.328.777.777 0 0 1 .03-.765c.072-.119.175-.226.303-.315a1.6 1.6 0 0 1 .954-.26c.351.012.681.129.92.326.12.094.214.206.276.328a.774.774 0 0 1-.03.764c-.072.12-.175.227-.302.315Z"></path><path fill="url(#cart_empty_svg__cz)" d="M87.985 171.162c-.257.182-.597.28-.947.274a1.574 1.574 0 0 1-.93-.308 1.021 1.021 0 0 1-.289-.324.786.786 0 0 1-.101-.383c0-.131.034-.262.101-.383s.166-.231.29-.323c.257-.182.598-.279.948-.272.35.007.684.118.928.31.124.092.222.201.29.322a.786.786 0 0 1 .1.383.785.785 0 0 1-.1.382c-.068.121-.166.23-.29.322Z"></path><path fill="url(#cart_empty_svg__cA)" d="M87.999 171.147a1.597 1.597 0 0 1-.939.289c-.35 0-.687-.104-.938-.289a1.049 1.049 0 0 1-.295-.32.794.794 0 0 1-.11-.382.782.782 0 0 1 .096-.384c.065-.122.162-.233.284-.326a1.58 1.58 0 0 1 .938-.292c.352 0 .689.105.939.292.125.091.224.2.293.32.07.121.106.25.109.382a.787.787 0 0 1-.095.384c-.065.121-.16.232-.282.326Z"></path><path fill="url(#cart_empty_svg__cB)" d="M88.017 171.134a1.583 1.583 0 0 1-.934.302 1.61 1.61 0 0 1-.947-.279 1.06 1.06 0 0 1-.3-.316.782.782 0 0 1-.032-.765.998.998 0 0 1 .273-.33c.246-.19.58-.299.931-.303.351-.004.69.096.944.28.128.089.231.196.304.315a.78.78 0 0 1 .035.766.994.994 0 0 1-.274.33Z"></path><path fill="url(#cart_empty_svg__cC)" d="M88.036 171.678a1.33 1.33 0 0 1-1.878.065 1.319 1.319 0 0 1-.364-1.429 1.32 1.32 0 0 1 .265-.444 1.321 1.321 0 0 1 1.874-.07 1.313 1.313 0 0 1 .44.917 1.33 1.33 0 0 1-.337.961Z"></path><path fill="url(#cart_empty_svg__cD)" d="M88.053 171.656a1.324 1.324 0 0 1-1.875.104 1.312 1.312 0 0 1-.457-.908 1.336 1.336 0 0 1 .32-.967 1.33 1.33 0 0 1 1.874-.104 1.324 1.324 0 0 1 .393 1.424 1.328 1.328 0 0 1-.255.451Z"></path><path fill="#000" d="M88.07 171.525c-.232.334-.558.539-.908.571-.35.032-.696-.11-.964-.398a1.624 1.624 0 0 1-.326-.501 2.066 2.066 0 0 1-.095-1.269c.051-.209.135-.403.246-.571.231-.334.557-.54.907-.574.35-.033.696.108.965.395.134.139.245.31.327.503a2.079 2.079 0 0 1 .095 1.272 1.82 1.82 0 0 1-.247.572Z"></path><g fill="#6F5439" opacity="0.5"><path d="M88.105 172.044a1.118 1.118 0 0 1-.385.12h-.12a.546.546 0 0 1-.14 0c-.109.008-.22.008-.329 0a2.18 2.18 0 0 1-.714-.24 1.856 1.856 0 0 1-.62-.554 1.497 1.497 0 0 1-.225-.5 2.215 2.215 0 0 1-.035-.244 1.378 1.378 0 0 1 0-.215 1.378 1.378 0 0 1 .08-.29.99.99 0 0 1 .125-.215c.05-.062.103-.12.16-.175.064-.071.136-.135.215-.189a.79.79 0 0 1 .5-.13c.174.033.343.093.5.179.149.08.259.16.384.24.25.181.51.348.78.5a4.044 4.044 0 0 1-.6.09H87.396a2.94 2.94 0 0 1-.19.225l-.045.055-.045.035a.397.397 0 0 0-.245.249.44.44 0 0 0 0 .31.759.759 0 0 0 .23.32c.293.226.639.373 1.004.429Z"></path><path d="M85.987 169.666a.953.953 0 0 1 .125-.399.621.621 0 0 1 .405-.28l.145-.035h.524c.155 0 .315 0 .5.035.74.089 1.447.364 2.053.799.47.382.961.734 1.474 1.054-.486.08-.982.068-1.464-.035a5.045 5.045 0 0 1-1.958-.784l-.375-.245a2.63 2.63 0 0 0-.415-.234.988.988 0 0 0-.53-.095.906.906 0 0 0-.484.219Z"></path><path d="M89.205 169.502c.018.09.032.182.04.274.017.118.017.238 0 .355-.05.313-.168.611-.345.874a2.042 2.042 0 0 1-.5.54 1.736 1.736 0 0 1-.574.26 1.614 1.614 0 0 1-.944-.14.622.622 0 0 1-.287-.868.666.666 0 0 1 .187-.221.213.213 0 0 0 .19.045.09.09 0 0 0 .06-.045h.165a.564.564 0 0 1 .13 0c.044-.003.09-.003.134 0 .201-.03.399-.08.59-.15.224-.081.436-.194.63-.335.052-.04.102-.084.15-.13l.034-.04.035-.035.065-.065c.085-.102.166-.209.24-.319Z"></path><path d="m87.451 171.825-.145.099-.194.08-.105.035a.415.415 0 0 1-.12.03 1.148 1.148 0 0 1-.275 0 1.094 1.094 0 0 1-1-.659 1.25 1.25 0 0 1-.119-.5 1.418 1.418 0 0 1 0-.254 2.05 2.05 0 0 1 .035-.23c0-.07.035-.12.05-.17.015-.05.04-.1.06-.145.038-.087.083-.17.135-.25.05-.075.1-.135.16-.21.062-.081.132-.157.21-.224a.716.716 0 0 1 .439-.21.889.889 0 0 1 .415.115c.1.062.198.13.29.205.187.148.386.281.594.399-.161.075-.328.135-.5.18l-.14.03h-.174l-.19.27-.05.06a.182.182 0 0 1-.04.045l-.085.065a.667.667 0 0 0-.205.564.59.59 0 0 0 .155.31c.211.218.496.348.8.365Z"></path><path d="M88.716 168.587c.103.293.13.609.075.915a1.498 1.498 0 0 1-.585 1.029.796.796 0 0 1-.235.16c-.082.039-.167.07-.255.095-.162.046-.33.075-.5.084 0-.109.036-.204.05-.284.016-.08.046-.21.06-.305.16-1.059.92-1.134 1.39-1.694Z"></path><path d="M88.45 170.276c.025.067.046.135.06.205.021.081.03.165.025.249-.015.215-.09.42-.215.595a1.301 1.301 0 0 1-.4.34 1.236 1.236 0 0 1-.464.13 1.537 1.537 0 0 1-.824-.17.582.582 0 0 1-.365-.455.36.36 0 0 1 .185-.335.211.211 0 0 0 .077.042.2.2 0 0 0 .088.008.056.056 0 0 0 .045-.025h.404c.168.001.336-.016.5-.05.174-.038.343-.1.5-.184a.47.47 0 0 0 .114-.08l.03-.025h.026l.05-.045c.06-.061.116-.128.164-.2Z"></path></g><path stroke="#292D35" stroke-miterlimit="10" stroke-width="2" d="M64.455 158.494s16.242-26.263 24.996-31.45c8.755-5.187 26.314 4.546 23.961 12.364-3.601 11.954-26.53 31.364-26.53 31.364"></path><path fill="#FFD232" d="M104.742 14.832c-5.422 12.485-3.522 35.729 8.991 41.15 12.512 5.423 30.792-9.036 36.22-21.502a24.644 24.644 0 0 0-31.577-32.248 24.644 24.644 0 0 0-13.607 12.6h-.027Z"></path><path fill="#FFD232" d="M115.135 56.5s2.491 5.861.73 6.054c-1.76.193-2.551-1.588-3.873-.904a1.89 1.89 0 0 1-2.324-.175 1.9 1.9 0 0 1-.474-.682c-.525-1.01-1.993-.438-1.748-1.78.246-1.343 3.29-4.811 5.562-4.246 2.273.564 2.127 1.734 2.127 1.734Z"></path><path fill="#292D35" d="M111.633 54.846s5.03 1.27 4.545 2.193c-.485.924-.764.126-2.193-.226-1.428-.352-4.478-1.894-2.352-1.967Z"></path><path stroke="#292D35" stroke-miterlimit="10" d="M98.894 127.016c-.339.791-.505 2.452.333 2.977a4.465 4.465 0 0 0 2.658.365 18.118 18.118 0 0 0 3.143-.545c1.627-.405 3.01-1.688 4.292-2.731a41.782 41.782 0 0 0 7.256-7.668 50.478 50.478 0 0 0 5.117-8.698 63.814 63.814 0 0 0 3.322-8.931 76.4 76.4 0 0 0 1.98-8.325c.711-4.04 1.548-8.054 1.05-12.174a24.43 24.43 0 0 0-1.927-7.003 26.589 26.589 0 0 0-1.774-3.323c-.704-1.143-1.615-2.551-3.143-2.478a4.829 4.829 0 0 0-1.701.478 17.81 17.81 0 0 0-3.987 2.412c-1.023.817-2.658 1.994-2.658 3.462.04.642.29 1.254.711 1.741a8.392 8.392 0 0 0 6.565 3.27c2.598 0 3.568-2.964 3.987-5.044.665-3.176-.139-6.565-1.282-9.529a27.8 27.8 0 0 0-1.19-2.658c-1.163-2.272-2.91-4.797-5.482-5.588"></path><path fill="#292D35" d="M58.878 192.918a5.547 5.547 0 0 1-1.522 1.93c-.6.474-1.307.794-2.058.93a6.38 6.38 0 0 1-2.486-.054 12.463 12.463 0 0 1-5.32-2.651 6.57 6.57 0 0 1-1.552-1.955 4.759 4.759 0 0 1-.51-2.199c.023-.83.237-1.644.623-2.38a5.35 5.35 0 0 1 1.527-1.928 4.703 4.703 0 0 1 2.07-.923 6.54 6.54 0 0 1 2.497.061 12.5 12.5 0 0 1 5.32 2.65 6.422 6.422 0 0 1 1.525 1.94c.34.68.51 1.433.496 2.195a5.569 5.569 0 0 1-.61 2.384ZM73.086 200.053a5.53 5.53 0 0 1-1.516 1.928c-.599.476-1.306.795-2.058.93a6.619 6.619 0 0 1-2.485-.054 12.618 12.618 0 0 1-5.32-2.65 6.599 6.599 0 0 1-1.553-1.956 4.7 4.7 0 0 1-.512-2.205 5.977 5.977 0 0 1 2.148-4.304 4.844 4.844 0 0 1 2.07-.923 6.703 6.703 0 0 1 2.502.063 12.633 12.633 0 0 1 5.32 2.651 6.583 6.583 0 0 1 1.524 1.939 4.77 4.77 0 0 1 .496 2.195 5.517 5.517 0 0 1-.616 2.386ZM74.578 195.08l-.548-1.485a4.815 4.815 0 0 0-2.834-2.752l-1.977-.704.715-1.994 1.978.704a6.91 6.91 0 0 1 4.08 3.942l.02.011.551 1.498-1.985.78Z"></path><g clip-path="url(#cart_empty_svg__cE)"><path fill="#292D35" d="M116.732 23.479c2.263 1.18 5.038.333 6.197-1.89 1.159-2.224.264-4.983-2-6.163-2.263-1.18-5.038-.334-6.197 1.89-1.159 2.224-.264 4.983 2 6.163ZM136.577 33.823c2.263 1.18 5.038.334 6.197-1.89 1.159-2.224.264-4.983-2-6.163-2.263-1.18-5.038-.334-6.197 1.89-1.159 2.224-.264 4.983 2 6.163Z"></path><path stroke="#292D35" stroke-linecap="round" stroke-linejoin="round" d="M127.008 32.77a6.916 6.916 0 0 0-1.013-1.52 2.385 2.385 0 0 0-1.609-.785 1.404 1.404 0 0 0-.854.253 1.368 1.368 0 0 0-.521.712 1.54 1.54 0 0 0 .296 1.179c.258.334.56.633.898.888-.694-.227-1.492-.422-2.121-.027a1.48 1.48 0 0 0-.531 1.766 2.067 2.067 0 0 0 1.537 1.2 3.096 3.096 0 0 0 1.956-.323"></path><path fill="#FFD232" d="M121.848 18.578c-.102.197-.26.359-.456.465a1.136 1.136 0 0 1-.645.13 1.159 1.159 0 0 1-.99-.788 1.11 1.11 0 0 1 .346-1.196c.172-.141.383-.229.606-.25a1.156 1.156 0 0 1 1.209.787c.092.284.066.59-.07.852ZM141.693 28.922a1.108 1.108 0 0 1-.457.465c-.196.106-.42.152-.644.131a1.156 1.156 0 0 1-.99-.788 1.106 1.106 0 0 1 .952-1.447 1.15 1.15 0 0 1 .648.128c.267.139.468.376.56.66.092.283.067.59-.069.851Z"></path></g><defs><linearGradient id="cart_empty_svg__a" x1="64.151" x2="64.841" y1="156.381" y2="160.344" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#616060"></stop></linearGradient><linearGradient id="cart_empty_svg__b" x1="67.028" x2="67.661" y1="157.335" y2="161.303" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#605F5F"></stop></linearGradient><linearGradient id="cart_empty_svg__c" x1="68.986" x2="69.648" y1="158.344" y2="162.296" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5F5E5F"></stop></linearGradient><linearGradient id="cart_empty_svg__d" x1="71.022" x2="71.614" y1="148.627" y2="152.577" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5F5E5E"></stop></linearGradient><linearGradient id="cart_empty_svg__e" x1="70.257" x2="70.744" y1="159.15" y2="163.125" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5E5D5D"></stop></linearGradient><linearGradient id="cart_empty_svg__f" x1="68.508" x2="68.881" y1="158.293" y2="162.268" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5D5C5C"></stop></linearGradient><linearGradient id="cart_empty_svg__g" x1="63.907" x2="64.147" y1="155.727" y2="159.704" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5C5B5C"></stop></linearGradient><linearGradient id="cart_empty_svg__h" x1="55.958" x2="56.055" y1="150.733" y2="154.709" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5B5B5B"></stop></linearGradient><linearGradient id="cart_empty_svg__i" x1="83.385" x2="83.248" y1="168.422" y2="172.451" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5B5A5A"></stop></linearGradient><linearGradient id="cart_empty_svg__j" x1="64.097" x2="63.676" y1="155.345" y2="159.296" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5A595A"></stop></linearGradient><linearGradient id="cart_empty_svg__k" x1="51.495" x2="50.691" y1="170.705" y2="174.566" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#595859"></stop></linearGradient><linearGradient id="cart_empty_svg__l" x1="84.743" x2="83.534" y1="168.727" y2="172.484" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#585758"></stop></linearGradient><linearGradient id="cart_empty_svg__m" x1="83.623" x2="81.926" y1="157.207" y2="160.686" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#575757"></stop></linearGradient><linearGradient id="cart_empty_svg__n" x1="50.782" x2="48.571" y1="146.655" y2="149.635" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#575657"></stop></linearGradient><linearGradient id="cart_empty_svg__o" x1="20.151" x2="17.483" y1="134.471" y2="136.775" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#565556"></stop></linearGradient><linearGradient id="cart_empty_svg__p" x1="101.711" x2="98.876" y1="177.084" y2="178.862" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#555455"></stop></linearGradient><linearGradient id="cart_empty_svg__q" x1="99.2" x2="95.985" y1="192.876" y2="193.841" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#545455"></stop></linearGradient><linearGradient id="cart_empty_svg__r" x1="31.549" x2="28.264" y1="175.821" y2="175.95" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#535354"></stop></linearGradient><linearGradient id="cart_empty_svg__s" x1="51.381" x2="48.11" y1="150.719" y2="150.213" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#535253"></stop></linearGradient><linearGradient id="cart_empty_svg__t" x1="28.573" x2="25.448" y1="146.942" y2="145.916" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#525152"></stop></linearGradient><linearGradient id="cart_empty_svg__u" x1="87.068" x2="84.011" y1="170.443" y2="169.012" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#515152"></stop></linearGradient><linearGradient id="cart_empty_svg__v" x1="83.541" x2="80.636" y1="168.551" y2="166.838" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#505051"></stop></linearGradient><linearGradient id="cart_empty_svg__w" x1="65.504" x2="62.754" y1="158.999" y2="157.085" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4F4F50"></stop></linearGradient><linearGradient id="cart_empty_svg__x" x1="86.486" x2="83.838" y1="170.538" y2="168.448" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4E4E50"></stop></linearGradient><linearGradient id="cart_empty_svg__y" x1="86.026" x2="83.477" y1="178.073" y2="175.848" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4E4D4F"></stop></linearGradient><linearGradient id="cart_empty_svg__z" x1="51.29" x2="48.86" y1="154.629" y2="152.339" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4D4D4E"></stop></linearGradient><linearGradient id="cart_empty_svg__A" x1="69.617" x2="67.246" y1="161.538" y2="159.16" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4C4C4D"></stop></linearGradient><linearGradient id="cart_empty_svg__B" x1="71.2" x2="68.895" y1="162.549" y2="160.105" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4B4B4D"></stop></linearGradient><linearGradient id="cart_empty_svg__C" x1="55.9" x2="53.666" y1="153.516" y2="151.033" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4A4A4C"></stop></linearGradient><linearGradient id="cart_empty_svg__D" x1="70.346" x2="68.151" y1="159.045" y2="156.507" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4A4A4B"></stop></linearGradient><linearGradient id="cart_empty_svg__E" x1="68.138" x2="65.992" y1="161.109" y2="158.526" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#49494B"></stop></linearGradient><linearGradient id="cart_empty_svg__F" x1="58.756" x2="56.656" y1="152.375" y2="149.774" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__G" x1="68.162" x2="66.019" y1="161.123" y2="158.547" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__H" x1="70.05" x2="67.858" y1="162.134" y2="159.591" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__I" x1="55.514" x2="53.281" y1="153.283" y2="150.799" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__J" x1="71.488" x2="69.183" y1="162.697" y2="160.249" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__K" x1="69.867" x2="67.497" y1="161.658" y2="159.278" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__L" x1="50.104" x2="47.676" y1="154.224" y2="151.936" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__M" x1="87.952" x2="85.4" y1="179.834" y2="177.605" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__N" x1="88.682" x2="86.029" y1="171.812" y2="169.728" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__O" x1="40.406" x2="37.687" y1="153.882" y2="151.997" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__P" x1="82.45" x2="79.548" y1="125.655" y2="123.989" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__Q" x1="90.464" x2="87.4" y1="172.223" y2="170.783" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__R" x1="21.917" x2="18.955" y1="147.253" y2="146.034" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__S" x1="96.786" x2="93.495" y1="131.131" y2="130.553" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__T" x1="40.244" x2="36.976" y1="146.768" y2="146.978" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__U" x1="110.459" x2="107.396" y1="176.891" y2="178.065" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__V" x1="110.109" x2="107.68" y1="176.739" y2="178.642" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__W" x1="38.57" x2="36.828" y1="145.439" y2="147.715" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__X" x1="58.786" x2="57.516" y1="190.207" y2="192.703" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__Y" x1="56.078" x2="55.227" y1="152.889" y2="155.469" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__Z" x1="32.431" x2="31.891" y1="122.002" y2="124.585" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__aa" x1="86.512" x2="86.157" y1="166.064" y2="168.728" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ab" x1="63.798" x2="63.609" y1="156.081" y2="158.732" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ac" x1="90.404" x2="90.344" y1="168.086" y2="170.767" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ad" x1="48.354" x2="48.391" y1="158.905" y2="161.556" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ae" x1="63.395" x2="63.523" y1="156.413" y2="159.07" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__af" x1="68.916" x2="69.117" y1="158.874" y2="161.539" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ag" x1="70.195" x2="70.519" y1="159.716" y2="162.365" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ah" x1="70.252" x2="70.635" y1="159.591" y2="162.217" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ai" x1="69.063" x2="69.509" y1="158.996" y2="161.637" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__aj" x1="56.117" x2="56.605" y1="149.825" y2="152.442" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ak" x1="64.699" x2="65.23" y1="157.04" y2="159.656" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__al" x1="62.221" x2="62.742" y1="154.796" y2="157.427" gradientUnits="userSpaceOnUse"><stop stop-color="#E8E8E8"></stop><stop offset="1" stop-color="#464647"></stop></linearGradient><linearGradient id="cart_empty_svg__am" x1="54.686" x2="55.166" y1="161.748" y2="164.341" gradientUnits="userSpaceOnUse"><stop stop-color="#E1E1E1"></stop><stop offset="1" stop-color="#434345"></stop></linearGradient><linearGradient id="cart_empty_svg__an" x1="68.791" x2="69.272" y1="159.161" y2="161.794" gradientUnits="userSpaceOnUse"><stop stop-color="#D9D9D9"></stop><stop offset="1" stop-color="#414143"></stop></linearGradient><linearGradient id="cart_empty_svg__ao" x1="56.968" x2="57.429" y1="154.023" y2="156.643" gradientUnits="userSpaceOnUse"><stop stop-color="#D1D1D1"></stop><stop offset="1" stop-color="#3F3F40"></stop></linearGradient><linearGradient id="cart_empty_svg__ap" x1="70.823" x2="71.271" y1="159.81" y2="162.444" gradientUnits="userSpaceOnUse"><stop stop-color="#C9C9C9"></stop><stop offset="1" stop-color="#3C3C3E"></stop></linearGradient><linearGradient id="cart_empty_svg__aq" x1="71.425" x2="71.856" y1="160.384" y2="163.026" gradientUnits="userSpaceOnUse"><stop stop-color="#C2C2C2"></stop><stop offset="1" stop-color="#3A3A3C"></stop></linearGradient><linearGradient id="cart_empty_svg__ar" x1="71.879" x2="72.294" y1="160.86" y2="163.506" gradientUnits="userSpaceOnUse"><stop stop-color="#BABABA"></stop><stop offset="1" stop-color="#383839"></stop></linearGradient><linearGradient id="cart_empty_svg__as" x1="78.875" x2="79.283" y1="154.992" y2="157.642" gradientUnits="userSpaceOnUse"><stop stop-color="#B2B2B2"></stop><stop offset="1" stop-color="#353537"></stop></linearGradient><linearGradient id="cart_empty_svg__at" x1="72.474" x2="72.855" y1="160.741" y2="163.39" gradientUnits="userSpaceOnUse"><stop stop-color="#AAA"></stop><stop offset="1" stop-color="#333334"></stop></linearGradient><linearGradient id="cart_empty_svg__au" x1="72.855" x2="73.218" y1="161.084" y2="163.736" gradientUnits="userSpaceOnUse"><stop stop-color="#A3A3A3"></stop><stop offset="1" stop-color="#313132"></stop></linearGradient><linearGradient id="cart_empty_svg__av" x1="70.564" x2="71.02" y1="161.22" y2="163.845" gradientUnits="userSpaceOnUse"><stop stop-color="#9B9B9B"></stop><stop offset="1" stop-color="#2E2E30"></stop></linearGradient><linearGradient id="cart_empty_svg__aw" x1="66.626" x2="67.057" y1="159.566" y2="162.185" gradientUnits="userSpaceOnUse"><stop stop-color="#939393"></stop><stop offset="1" stop-color="#2C2C2D"></stop></linearGradient><linearGradient id="cart_empty_svg__ax" x1="70.42" x2="70.832" y1="161.046" y2="163.681" gradientUnits="userSpaceOnUse"><stop stop-color="#8B8B8B"></stop><stop offset="1" stop-color="#2A2A2B"></stop></linearGradient><linearGradient id="cart_empty_svg__ay" x1="72.3" x2="72.595" y1="161.016" y2="163.679" gradientUnits="userSpaceOnUse"><stop stop-color="#848484"></stop><stop offset="1" stop-color="#282729"></stop></linearGradient><linearGradient id="cart_empty_svg__az" x1="70.037" x2="70.403" y1="160.671" y2="163.32" gradientUnits="userSpaceOnUse"><stop stop-color="#7C7C7C"></stop><stop offset="1" stop-color="#252526"></stop></linearGradient><linearGradient id="cart_empty_svg__aA" x1="67.435" x2="67.795" y1="149.787" y2="152.417" gradientUnits="userSpaceOnUse"><stop stop-color="#747474"></stop><stop offset="1" stop-color="#232324"></stop></linearGradient><linearGradient id="cart_empty_svg__aB" x1="69.309" x2="69.631" y1="160.389" y2="163.041" gradientUnits="userSpaceOnUse"><stop stop-color="#6C6C6C"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__aC" x1="68.991" x2="69.29" y1="159.992" y2="162.643" gradientUnits="userSpaceOnUse"><stop stop-color="#656565"></stop><stop offset="1" stop-color="#1E1E1F"></stop></linearGradient><linearGradient id="cart_empty_svg__aD" x1="70.161" x2="70.367" y1="159.782" y2="162.443" gradientUnits="userSpaceOnUse"><stop stop-color="#5D5D5D"></stop><stop offset="1" stop-color="#1C1C1D"></stop></linearGradient><linearGradient id="cart_empty_svg__aE" x1="62.605" x2="62.857" y1="154.43" y2="157.069" gradientUnits="userSpaceOnUse"><stop stop-color="#555"></stop><stop offset="1" stop-color="#1A1A1A"></stop></linearGradient><linearGradient id="cart_empty_svg__aF" x1="69.072" x2="69.245" y1="159.135" y2="161.802" gradientUnits="userSpaceOnUse"><stop stop-color="#4D4D4D"></stop><stop offset="1" stop-color="#171718"></stop></linearGradient><linearGradient id="cart_empty_svg__aG" x1="68.472" x2="68.627" y1="158.889" y2="161.551" gradientUnits="userSpaceOnUse"><stop stop-color="#464646"></stop><stop offset="1" stop-color="#151515"></stop></linearGradient><linearGradient id="cart_empty_svg__aH" x1="67.969" x2="68.108" y1="158.495" y2="161.163" gradientUnits="userSpaceOnUse"><stop stop-color="#3E3E3E"></stop><stop offset="1" stop-color="#131313"></stop></linearGradient><linearGradient id="cart_empty_svg__aI" x1="67.4" x2="67.52" y1="158.256" y2="160.924" gradientUnits="userSpaceOnUse"><stop stop-color="#363636"></stop><stop offset="1" stop-color="#101011"></stop></linearGradient><linearGradient id="cart_empty_svg__aJ" x1="66.875" x2="66.978" y1="158.023" y2="160.688" gradientUnits="userSpaceOnUse"><stop stop-color="#2E2E2E"></stop><stop offset="1" stop-color="#0E0E0E"></stop></linearGradient><linearGradient id="cart_empty_svg__aK" x1="66.458" x2="66.544" y1="157.698" y2="160.367" gradientUnits="userSpaceOnUse"><stop stop-color="#272727"></stop><stop offset="1" stop-color="#0C0C0C"></stop></linearGradient><linearGradient id="cart_empty_svg__aL" x1="66.056" x2="66.125" y1="157.41" y2="160.08" gradientUnits="userSpaceOnUse"><stop stop-color="#1F1F1F"></stop><stop offset="1" stop-color="#09090A"></stop></linearGradient><linearGradient id="cart_empty_svg__aM" x1="66.005" x2="66.059" y1="156.947" y2="159.61" gradientUnits="userSpaceOnUse"><stop stop-color="#171717"></stop><stop offset="1" stop-color="#070707"></stop></linearGradient><linearGradient id="cart_empty_svg__aN" x1="64.891" x2="64.918" y1="157.823" y2="160.481" gradientUnits="userSpaceOnUse"><stop stop-color="#0F0F0F"></stop><stop offset="1" stop-color="#050505"></stop></linearGradient><linearGradient id="cart_empty_svg__aO" x1="65.834" x2="65.861" y1="156.201" y2="158.857" gradientUnits="userSpaceOnUse"><stop stop-color="#080808"></stop><stop offset="1" stop-color="#020202"></stop></linearGradient><linearGradient id="cart_empty_svg__aP" x1="86.502" x2="87.571" y1="168.378" y2="172.889" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#616060"></stop></linearGradient><linearGradient id="cart_empty_svg__aQ" x1="88.98" x2="89.953" y1="169.502" y2="174.004" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#605F5F"></stop></linearGradient><linearGradient id="cart_empty_svg__aR" x1="90.946" x2="91.609" y1="170.989" y2="174.941" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5F5E5F"></stop></linearGradient><linearGradient id="cart_empty_svg__aS" x1="93.004" x2="93.598" y1="161.177" y2="165.132" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5F5E5E"></stop></linearGradient><linearGradient id="cart_empty_svg__aT" x1="92.234" x2="92.574" y1="171.298" y2="174.626" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5E5D5D"></stop></linearGradient><linearGradient id="cart_empty_svg__aU" x1="90.484" x2="90.743" y1="170.58" y2="173.903" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5D5C5C"></stop></linearGradient><linearGradient id="cart_empty_svg__aV" x1="85.827" x2="85.994" y1="168.42" y2="171.738" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5C5B5C"></stop></linearGradient><linearGradient id="cart_empty_svg__aW" x1="76.362" x2="76.419" y1="164.24" y2="167.555" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5B5B5B"></stop></linearGradient><linearGradient id="cart_empty_svg__aX" x1="109.558" x2="109.479" y1="179.095" y2="182.454" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5B5A5A"></stop></linearGradient><linearGradient id="cart_empty_svg__aY" x1="86.21" x2="85.965" y1="168.1" y2="171.41" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#5A595A"></stop></linearGradient><linearGradient id="cart_empty_svg__aZ" x1="70.942" x2="70.467" y1="181.006" y2="184.293" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#595859"></stop></linearGradient><linearGradient id="cart_empty_svg__ba" x1="111.21" x2="110.473" y1="179.356" y2="182.649" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#585758"></stop></linearGradient><linearGradient id="cart_empty_svg__bb" x1="109.874" x2="108.785" y1="169.665" y2="172.884" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#575757"></stop></linearGradient><linearGradient id="cart_empty_svg__bc" x1="70.067" x2="68.499" y1="160.799" y2="163.841" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#575657"></stop></linearGradient><linearGradient id="cart_empty_svg__bd" x1="32.975" x2="30.791" y1="150.61" y2="153.34" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#565556"></stop></linearGradient><linearGradient id="cart_empty_svg__be" x1="131.726" x2="128.7" y1="189.213" y2="191.49" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#555455"></stop></linearGradient><linearGradient id="cart_empty_svg__bf" x1="128.721" x2="124.993" y1="205.244" y2="206.584" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#545455"></stop></linearGradient><linearGradient id="cart_empty_svg__bg" x1="46.776" x2="42.836" y1="187.937" y2="188.123" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#535354"></stop></linearGradient><linearGradient id="cart_empty_svg__bh" x1="70.81" x2="66.928" y1="162.608" y2="161.888" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#535253"></stop></linearGradient><linearGradient id="cart_empty_svg__bi" x1="43.209" x2="39.613" y1="158.793" y2="157.378" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#525152"></stop></linearGradient><linearGradient id="cart_empty_svg__bj" x1="113.979" x2="110.581" y1="182.496" y2="180.586" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#515152"></stop></linearGradient><linearGradient id="cart_empty_svg__bk" x1="109.743" x2="106.612" y1="180.596" y2="178.378" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#505051"></stop></linearGradient><linearGradient id="cart_empty_svg__bl" x1="87.922" x2="85.036" y1="170.954" y2="168.545" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4F4F50"></stop></linearGradient><linearGradient id="cart_empty_svg__bm" x1="113.307" x2="110.577" y1="182.662" y2="180.085" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4E4E50"></stop></linearGradient><linearGradient id="cart_empty_svg__bn" x1="112.75" x2="110.176" y1="190.214" y2="187.519" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4E4D4F"></stop></linearGradient><linearGradient id="cart_empty_svg__bo" x1="70.691" x2="68.277" y1="166.543" y2="163.813" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4D4D4E"></stop></linearGradient><linearGradient id="cart_empty_svg__bp" x1="92.884" x2="90.553" y1="173.516" y2="170.711" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4C4C4D"></stop></linearGradient><linearGradient id="cart_empty_svg__bq" x1="93.842" x2="91.535" y1="174.537" y2="172.09" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4B4B4D"></stop></linearGradient><linearGradient id="cart_empty_svg__br" x1="78.4" x2="76.165" y1="165.421" y2="162.938" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4A4A4C"></stop></linearGradient><linearGradient id="cart_empty_svg__bs" x1="92.984" x2="90.787" y1="170.983" y2="168.44" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#4A4A4B"></stop></linearGradient><linearGradient id="cart_empty_svg__bt" x1="90.75" x2="88.606" y1="173.078" y2="170.499" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#49494B"></stop></linearGradient><linearGradient id="cart_empty_svg__bu" x1="81.28" x2="79.182" y1="164.287" y2="161.689" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bv" x1="90.777" x2="88.629" y1="173.086" y2="170.506" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bw" x1="92.673" x2="90.48" y1="174.13" y2="171.59" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bx" x1="78.009" x2="75.777" y1="165.185" y2="162.701" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__by" x1="94.122" x2="91.816" y1="174.686" y2="172.24" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bz" x1="92.496" x2="90.123" y1="173.638" y2="171.255" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bA" x1="72.563" x2="70.135" y1="166.134" y2="163.848" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bB" x1="105.734" x2="103.315" y1="192.007" y2="190.318" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bC" x1="106.325" x2="103.866" y1="183.874" y2="182.329" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bD" x1="67.358" x2="64.893" y1="165.792" y2="164.424" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bE" x1="101.296" x2="98.745" y1="137.294" y2="136.123" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bF" x1="107.761" x2="105.139" y1="184.302" y2="183.317" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bG" x1="52.342" x2="49.744" y1="156.647" y2="155.963" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bH" x1="112.879" x2="110.198" y1="136.369" y2="136.068" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bI" x1="67.225" x2="64.605" y1="156.089" y2="156.197" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bJ" x1="123.896" x2="121.246" y1="194.087" y2="194.737" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bK" x1="123.615" x2="121.109" y1="193.899" y2="195.155" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bL" x1="65.87" x2="63.649" y1="154.426" y2="156.285" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bM" x1="82.199" x2="80.285" y1="210.87" y2="213.276" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bN" x1="80.015" x2="78.56" y1="163.825" y2="166.65" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bO" x1="60.933" x2="59.938" y1="124.871" y2="127.916" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bP" x1="104.578" x2="103.902" y1="180.43" y2="183.678" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bQ" x1="86.247" x2="85.881" y1="167.867" y2="171.156" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bR" x1="107.752" x2="107.633" y1="182.986" y2="186.333" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bS" x1="73.78" x2="73.853" y1="171.409" y2="174.721" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bT" x1="85.903" x2="86.152" y1="168.256" y2="171.578" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bU" x1="90.381" x2="90.768" y1="171.369" y2="174.671" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bV" x1="92.166" x2="92.669" y1="172.452" y2="175.735" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bW" x1="92.214" x2="92.806" y1="172.256" y2="175.5" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bX" x1="91.024" x2="91.47" y1="171.643" y2="174.283" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bY" x1="77.969" x2="78.457" y1="162.391" y2="165.004" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__bZ" x1="86.627" x2="87.157" y1="169.669" y2="172.28" gradientUnits="userSpaceOnUse"><stop stop-color="#F0F0F0"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__ca" x1="84.131" x2="84.429" y1="167.913" y2="169.92" gradientUnits="userSpaceOnUse"><stop stop-color="#E8E8E8"></stop><stop offset="1" stop-color="#464647"></stop></linearGradient><linearGradient id="cart_empty_svg__cb" x1="76.493" x2="76.771" y1="173.19" y2="175.18" gradientUnits="userSpaceOnUse"><stop stop-color="#E1E1E1"></stop><stop offset="1" stop-color="#434345"></stop></linearGradient><linearGradient id="cart_empty_svg__cc" x1="90.737" x2="91.01" y1="171.221" y2="173.216" gradientUnits="userSpaceOnUse"><stop stop-color="#D9D9D9"></stop><stop offset="1" stop-color="#414143"></stop></linearGradient><linearGradient id="cart_empty_svg__cd" x1="78.838" x2="79.101" y1="167.331" y2="169.321" gradientUnits="userSpaceOnUse"><stop stop-color="#D1D1D1"></stop><stop offset="1" stop-color="#3F3F40"></stop></linearGradient><linearGradient id="cart_empty_svg__ce" x1="92.812" x2="93.068" y1="171.709" y2="173.714" gradientUnits="userSpaceOnUse"><stop stop-color="#C9C9C9"></stop><stop offset="1" stop-color="#3C3C3E"></stop></linearGradient><linearGradient id="cart_empty_svg__cf" x1="93.416" x2="93.661" y1="172.15" y2="174.15" gradientUnits="userSpaceOnUse"><stop stop-color="#C2C2C2"></stop><stop offset="1" stop-color="#3A3A3C"></stop></linearGradient><linearGradient id="cart_empty_svg__cg" x1="93.881" x2="94.116" y1="172.51" y2="174.516" gradientUnits="userSpaceOnUse"><stop stop-color="#BABABA"></stop><stop offset="1" stop-color="#383839"></stop></linearGradient><linearGradient id="cart_empty_svg__ch" x1="100.986" x2="101.218" y1="168.061" y2="170.067" gradientUnits="userSpaceOnUse"><stop stop-color="#B2B2B2"></stop><stop offset="1" stop-color="#353537"></stop></linearGradient><linearGradient id="cart_empty_svg__ci" x1="94.495" x2="94.711" y1="172.407" y2="174.416" gradientUnits="userSpaceOnUse"><stop stop-color="#AAA"></stop><stop offset="1" stop-color="#333334"></stop></linearGradient><linearGradient id="cart_empty_svg__cj" x1="94.882" x2="95.088" y1="172.685" y2="174.691" gradientUnits="userSpaceOnUse"><stop stop-color="#A3A3A3"></stop><stop offset="1" stop-color="#313132"></stop></linearGradient><linearGradient id="cart_empty_svg__ck" x1="94.862" x2="95.058" y1="172.798" y2="174.802" gradientUnits="userSpaceOnUse"><stop stop-color="#9B9B9B"></stop><stop offset="1" stop-color="#2E2E30"></stop></linearGradient><linearGradient id="cart_empty_svg__cl" x1="89.538" x2="89.723" y1="171.551" y2="173.556" gradientUnits="userSpaceOnUse"><stop stop-color="#939393"></stop><stop offset="1" stop-color="#2C2C2D"></stop></linearGradient><linearGradient id="cart_empty_svg__cm" x1="94.664" x2="94.841" y1="172.66" y2="174.669" gradientUnits="userSpaceOnUse"><stop stop-color="#8B8B8B"></stop><stop offset="1" stop-color="#2A2A2B"></stop></linearGradient><linearGradient id="cart_empty_svg__cn" x1="94.339" x2="94.506" y1="172.638" y2="174.649" gradientUnits="userSpaceOnUse"><stop stop-color="#848484"></stop><stop offset="1" stop-color="#282729"></stop></linearGradient><linearGradient id="cart_empty_svg__co" x1="94.121" x2="94.278" y1="172.378" y2="174.385" gradientUnits="userSpaceOnUse"><stop stop-color="#7C7C7C"></stop><stop offset="1" stop-color="#252526"></stop></linearGradient><linearGradient id="cart_empty_svg__cp" x1="90.639" x2="90.792" y1="164.081" y2="166.078" gradientUnits="userSpaceOnUse"><stop stop-color="#747474"></stop><stop offset="1" stop-color="#232324"></stop></linearGradient><linearGradient id="cart_empty_svg__cq" x1="93.188" x2="93.325" y1="172.171" y2="174.176" gradientUnits="userSpaceOnUse"><stop stop-color="#6C6C6C"></stop><stop offset="1" stop-color="#212121"></stop></linearGradient><linearGradient id="cart_empty_svg__cr" x1="92.765" x2="92.892" y1="171.868" y2="173.875" gradientUnits="userSpaceOnUse"><stop stop-color="#656565"></stop><stop offset="1" stop-color="#1E1E1F"></stop></linearGradient><linearGradient id="cart_empty_svg__cs" x1="92.197" x2="92.314" y1="171.709" y2="173.714" gradientUnits="userSpaceOnUse"><stop stop-color="#5D5D5D"></stop><stop offset="1" stop-color="#1C1C1D"></stop></linearGradient><linearGradient id="cart_empty_svg__ct" x1="84.051" x2="84.159" y1="167.63" y2="169.622" gradientUnits="userSpaceOnUse"><stop stop-color="#555"></stop><stop offset="1" stop-color="#1A1A1A"></stop></linearGradient><linearGradient id="cart_empty_svg__cu" x1="91.094" x2="91.192" y1="171.223" y2="173.227" gradientUnits="userSpaceOnUse"><stop stop-color="#4D4D4D"></stop><stop offset="1" stop-color="#171718"></stop></linearGradient><linearGradient id="cart_empty_svg__cv" x1="90.488" x2="90.575" y1="171.026" y2="173.03" gradientUnits="userSpaceOnUse"><stop stop-color="#464646"></stop><stop offset="1" stop-color="#151515"></stop></linearGradient><linearGradient id="cart_empty_svg__cw" x1="89.985" x2="90.062" y1="170.721" y2="172.724" gradientUnits="userSpaceOnUse"><stop stop-color="#3E3E3E"></stop><stop offset="1" stop-color="#131313"></stop></linearGradient><linearGradient id="cart_empty_svg__cx" x1="89.403" x2="89.471" y1="170.554" y2="172.556" gradientUnits="userSpaceOnUse"><stop stop-color="#363636"></stop><stop offset="1" stop-color="#101011"></stop></linearGradient><linearGradient id="cart_empty_svg__cy" x1="88.869" x2="88.927" y1="170.376" y2="172.376" gradientUnits="userSpaceOnUse"><stop stop-color="#2E2E2E"></stop><stop offset="1" stop-color="#0E0E0E"></stop></linearGradient><linearGradient id="cart_empty_svg__cz" x1="88.445" x2="88.493" y1="170.127" y2="172.129" gradientUnits="userSpaceOnUse"><stop stop-color="#272727"></stop><stop offset="1" stop-color="#0C0C0C"></stop></linearGradient><linearGradient id="cart_empty_svg__cA" x1="88.044" x2="88.082" y1="169.909" y2="171.909" gradientUnits="userSpaceOnUse"><stop stop-color="#1F1F1F"></stop><stop offset="1" stop-color="#09090A"></stop></linearGradient><linearGradient id="cart_empty_svg__cB" x1="88.01" x2="88.04" y1="169.541" y2="171.539" gradientUnits="userSpaceOnUse"><stop stop-color="#171717"></stop><stop offset="1" stop-color="#070707"></stop></linearGradient><linearGradient id="cart_empty_svg__cC" x1="86.813" x2="86.84" y1="170.526" y2="173.185" gradientUnits="userSpaceOnUse"><stop stop-color="#0F0F0F"></stop><stop offset="1" stop-color="#050505"></stop></linearGradient><linearGradient id="cart_empty_svg__cD" x1="87.824" x2="87.851" y1="168.754" y2="171.41" gradientUnits="userSpaceOnUse"><stop stop-color="#080808"></stop><stop offset="1" stop-color="#020202"></stop></linearGradient><clipPath id="cart_empty_svg__cE"><path fill="#fff" d="m116.831 13.29 28.041 14.616-8.521 16.348-28.041-14.617z"></path></clipPath></defs></svg>

                    <h4>Hey, it feels so light!</h4>
                    <p>There is nothing in your bag. Let's add some items.</p>

                    @if (Auth::check())
                        <a href="{{ route('account.wishlist') }}" class="btn btn-outline-primary mt-4 caps-btn">
                            Add Items from Wishlist
                        </a>
                    @else
                        <a href="{{ route('account.login') }}" class="btn btn-outline-primary retirectBack mt-4 caps-btn">
                            Add Items from Wishlist
                        </a>
                    @endif
                </div>
            </div>
        @endif
        
        @include('front.layouts.address_modal') 
@endsection

@section('customJs')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let currentRowId = '';
        let currentType = '';       

        $(document).on('click', '.update-cart-modal', function(){            
            currentRowId = $(this).data('rowid');
            currentType = $(this).data('type');
            let selected = $(this).data('selected');

            let title = '';
            let options = [];

            if(currentType === 'size_id'){
                title = 'Select Size';                
                let productId = $(this).data('productid');

                $.get('/get-product-sizes/' + productId, function(res){
                    let options = res.sizes; 

                    let html = '';
                    options.forEach(function(option){
                        let active = (option.id == selected) ? 'selected' : '';
                        html += `<li><a href="#" class="select-option show-tooltip ${active}" data-value="${option.id}">
                                        ${option.code}
                                    <span class="tooltip" style="bottom:48px;">${option.name}</span>
                                </a></li>`;
                    });

                    $('#modalList').html(html);
                    $('#cartModalTitle').text(title);

                    let modal = new bootstrap.Modal(document.getElementById('commonCartUpdateModal'));
                    modal.show();
                });

                return; // ❗ stop further execution
            }

            if(currentType === 'color_id'){
                title = 'Select Color';
                let productId = $(this).data('productid');

                $.get('/get-product-colors/' + productId, function(res){
                    let options = res.colors;
                    let html = '';
                    options.forEach(function(option){
                        let active = (option.id == selected) ? 'selected' : '';
                        html += `<li><a href="#" class="select-option ${active} show-tooltip" data-value="${option.id}">
                                        <span class="color" style="background-color:${option.code}"></span>
                                        <span class="tooltip" style="bottom:48px;">${option.name}</span>
                                </a></li>`;
                    });

                    $('#modalList').html(html);
                    $('#cartModalTitle').text(title);

                    let modal = new bootstrap.Modal(document.getElementById('commonCartUpdateModal'));
                    modal.show();
                });
                return; // ❗ stop further execution
            }

            if(currentType === 'qty'){
                title = 'Select Quantity';
                options = [1,2,3,4,5,6,7,8,9,10];

                let html = '';
                options.forEach(function(option){
                    let active = (option == selected) ? 'selected' : '';
                    html += `<li><a href="#" class="select-option ${active}" data-value="${option}">${option}</a></li>`;
                });

                $('#modalList').html(html);
                new bootstrap.Modal('#commonCartUpdateModal').show();
            }
            $('#cartModalTitle').text(title);
        });

        $(document).on('click', '.select-option', function(e){
            e.preventDefault();
            let value = $(this).data('value');

            let data = {
                rowId: currentRowId,
                _token: '{{ csrf_token() }}'
            };

            if(currentType === 'qty'){ data.qty = value; }
            if(currentType === 'size_id'){ data.size_id = value; }
            if(currentType === 'color_id'){ data.color_id = value; }

            $.post('{{ route("front.updateCartOption") }}', data, function(res){
                if(res.status){
                    location.reload();
                }
            });
        });  

        $("#payment_cod").click(function(){
            if ($(this).is(":checked") == true){
                $("#cod-form").removeClass('d-none');
                $("#razorpay-form").addClass('d-none');
            }
        });

        $("#payment_razorpay").click(function(){
            if ($(this).is(":checked") == true){
                $("#cod-form").addClass('d-none');
                $("#razorpay-form").removeClass('d-none');
            }
        });              

        $("#orderForm").submit(function(event){
            event.preventDefault();                     

            let paymentMethod = $('input[name="payment_method"]:checked').val();
            $('button[type="submit"]').prop('disabled', true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',

                success:function(response){
                    $('button[type="submit"]').prop('disabled', false);                    

                    if(response.status == false){
                        console.log(response.errors);
                        return;
                    }

                    // ✅ COD → create order immediately
                    if(paymentMethod === 'cod'){
                        if(response.orderId){
                            window.location.href = "{{ url('thanks') }}/" + response.orderId;
                        } else {
                            alert('Order ID missing');
                        }
                    }

                    // ✅ Razorpay → DO NOT use orderId yet
                    if(paymentMethod === 'razorpay'){
                        var options = {
                            "key": response.key,
                            "amount": response.amount,
                            "currency": "INR",
                            "name": "Your Store",
                            "description": "Order Payment",
                            "order_id": response.razorpay_order_id, 

                            // ✅ Only here create order
                            "handler": function (paymentResponse){                                

                                $.ajax({
                                    url: "{{ route('checkout.verify.payment') }}",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        razorpay_payment_id: paymentResponse.razorpay_payment_id,
                                        razorpay_order_id: paymentResponse.razorpay_order_id,
                                        razorpay_signature: paymentResponse.razorpay_signature
                                        // ❌ removed order_id (important)
                                    },

                                    success: function(res){
                                        if(res.status === 'success'){
                                            // ✅ Backend should return orderId AFTER creation
                                            window.location.href = "{{ url('thanks') }}/"+res.orderId;
                                        } else {
                                            alert(res.message || "Payment verification failed");
                                        }
                                    }
                                });
                            },

                            // ✅ Prevent order on modal close / refresh
                            "modal": {
                                "ondismiss": function () {
                                    console.log('Payment cancelled');
                                }
                            },

                            "theme": {
                                "color": "#0d6efd"
                            }
                        };

                        var rzp = new Razorpay(options);
                        rzp.open();
                    }
                },

                error:function(xhr){
                    console.log(xhr.responseText);
                    $('button[type="submit"]').prop('disabled', false);
                }
            });
        });
      
        // $("#orderForm").submit(function(event){
        //     event.preventDefault();                     

        //     let amountText = $('.grand_total_button').text();
        //     let amount = amountText.replace(/[^0-9.]/g, '');
        //     amount = parseFloat(amount) * 100;

        //     let paymentMethod = $('input[name="payment_method"]:checked').val();
        //     $('button[type="submit"]').prop('disabled', true);

        //     $.ajax({
        //         url: '{{ route("front.processCheckout") }}',
        //         type: 'POST',
        //         data: $(this).serialize(),
        //         dataType: 'json',

        //         success:function(response){
        //             $('button[type="submit"]').prop('disabled', false);                    

        //             if(response.status == false){
        //                 console.log(response.errors);
        //                 return;
        //             }

        //             // ✅ COD
        //             if(paymentMethod === 'cod'){
        //                 window.location.href = "{{ url('thanks') }}/"+response.orderId;
        //             }

        //             // ✅ Razorpay
        //             if(paymentMethod === 'razorpay'){
        //                 console.log(response);
        //                 var options = {
        //                     "key": response.key,
        //                     "amount": response.amount,
        //                     "currency": "INR",
        //                     "name": "Your Store",
        //                     "description": "Order Payment",
        //                     "order_id": response.razorpay_order_id, 
                                                        
        //                     "handler": function (paymentResponse){                                
        //                         $.ajax({
        //                             url: "{{ route('checkout.verify.payment') }}",
        //                             type: "POST",
        //                             data: {
        //                                 _token: "{{ csrf_token() }}",
        //                                 razorpay_payment_id: paymentResponse.razorpay_payment_id,
        //                                 razorpay_order_id: paymentResponse.razorpay_order_id,
        //                                 razorpay_signature: paymentResponse.razorpay_signature,
        //                                 order_id: response.orderId
        //                             },
                                    
        //                             success: function(res){
        //                                 if(res.status === 'success'){
        //                                     window.location.href = "{{ url('thanks') }}/"+response.orderId;
        //                                 } else {
        //                                     alert(res.message || "Payment verification failed");
        //                                 }
        //                             }
        //                         });
        //                     },

        //                     "theme": {
        //                         "color": "#0d6efd"
        //                     }
        //                 };

        //                 var rzp = new Razorpay(options);
        //                 rzp.open();
        //             }
        //         },

        //         error:function(xhr){
        //             console.log(xhr.responseText);
        //             $('button[type="submit"]').prop('disabled', false);
        //         }
        //     });
        // });

                        
        function deleteItem(rowId){            
            $.ajax({
                url: '{{ route("front.deleteItem.cart") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rowId: rowId
                },
                dataType: 'json',
                success: function(response){
                    if(response.status){
                        //window.location.href='{{ route("front.cart") }}';
                        showAlert(response.message,'success');
                        location.reload();
                        // var toast = new bootstrap.Toast($('.toast-cart')[0], {
                        //     delay: 2000
                        // });
                        // toast.show();                        
                    } else {
                        //alert(response.message);
                        showAlert(response.message,'error');
                    }
                }
            })            
        }

        function moveToWishlist(rowId){             
            $.ajax({
                url: '{{ route("front.moveToWishlist.cart") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rowId: rowId
                },
                dataType: 'json',
                success: function(response){
                    if(response.status == true){
                        showAlert(response.message,'success');
                    }else{
                        showAlert(response.message,'error');
                    }
                    location.reload();
                    // if(response.status){
                    //     window.location.href='{{ route("front.cart") }}';
                    // } else {
                    //     alert(response.message);
                    // }
                }
            })            
        } 

        $(document).on('change', 'input[name="coupon_id"]', function() {    
            $('.coupon-box').removeClass('active');
            $(this).closest('.coupon-box').addClass('active');
        });       

        $(document).ready(function () {          
            function updateSelectedCount() {
                let count = $('.item-checkbox:checked').length;
                $('#selectedCount').text(count);

                let total = $('.item-checkbox').length;
                let checked = $('.item-checkbox:checked').length;

                $('#selectedCount').text(checked);

                if(checked === 0){
                    $('.price-details').hide();
                } else {
                    $('.price-details').show();
                }
            }                   

            $('.item-checkbox').on('change', function() {
                $('#selectAll').prop(
                    'checked',
                    $('.item-checkbox:checked').length === $('.item-checkbox').length
                );
                updateSelectedCount();
            });

            updateSelectedCount();

            function updateSelectedCount() {
                let count = $('.item-checkbox:checked').length;
                $('#selectedCount').text(count);
            }           

            $('.bulk-action').on('click', function(e) {
                e.preventDefault();

                let selected = $('.item-checkbox:checked');

                if (selected.length === 0) {
                    alert('Select any item to remove from bag.');
                    return;
                }

                $.ajax({
                    url: "{{ route('cart.bulk.action') }}",
                    type: "POST",
                    data: $('#cartForm').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        selected.each(function(){
                            let rowId = $(this).data('rowid');
                            $("#cart-item-" + rowId).fadeOut(300, function(){
                                $(this).remove();
                            });
                            location.reload();
                        });

                        $('#cartCount').text(response.cartCount);
                        showAlert(response.message, 'success');
                    }
                });
            });

            function updateMainCheckbox() {
                let total = $('.item-checkbox').length;
                let checked = $('.item-checkbox:checked').length;

                if (checked === 0) {
                    $('#selectAll')
                        .prop('checked', false)
                        .prop('indeterminate', false);

                } else if (checked === total) {
                    $('#selectAll')
                        .prop('checked', true)
                        .prop('indeterminate', false);

                } else {
                    $('#selectAll')
                        .prop('checked', false)
                        .prop('indeterminate', true);
                }
            }
           
            $('.item-checkbox').on('change', function() {
                updateMainCheckbox();
            });

            updateMainCheckbox();            

            $('input[name="coupon_id"]').on('change', function () {
                let code = $(this).data('code');
                $('#discount_code').val(code);
            });

            @if(session('success'))
                var toast = new bootstrap.Toast(document.getElementById('liveToast'));
                toast.show();
            @endif           
        });

        $(document).ready(function(){
            function updateCartSummary(){
                let mrp_total = 0;
                let price_discount = 0;
                let selectedCount = 0;
                let coupon_discount = parseFloat($('#coupon_discount').val()) || 0;
                let shipping_charge = parseFloat($('#shipping_charge').val()) || 0;

                $('.item-checkbox').on('change', function() {                    
                    if ($(this).is(':checked')) {
                        $(this).parent().parent().parent().addClass('active-card');                        
                    } else {
                        $(this).parent().parent().parent().removeClass('active-card');                        
                    }
                });

                $('.item-checkbox:checked').each(function(){
                    let price = parseFloat($(this).data('price')) || 0;
                    let qty = parseInt($(this).data('qty')) || 1;
                    let discount_percentage = parseFloat($(this).data('discount_percentage')) || 0;
                    let mrp = price * qty;
                    let discount_amount = 0;

                    if(discount_percentage > 0){
                        discount_amount = (price * discount_percentage / 100) * qty;
                    }

                    mrp_total += mrp;
                    price_discount += discount_amount;
                    selectedCount++;
                });

                // subtotal after product discount
                let subtotal = mrp_total - price_discount;

                // apply coupon
                let afterCoupon = subtotal - coupon_discount;

                if(afterCoupon < 0){
                    afterCoupon = 0;
                }

                // shipping logic
                let appliedShipping = 0;

                if(selectedCount > 0){
                    appliedShipping = shipping_charge;
                    $('.priceDetailsBox').removeClass('d-none');
                    $('.strike').removeClass('dell');
                }else{
                    $('.priceDetailsBox').addClass('d-none');
                    $('.strike').addClass('dell');
                }

                let total = afterCoupon + appliedShipping;

                // Update UI
                $('#selectedCount').text(selectedCount);
                $('.selected-items').text(selectedCount);
                $('.mrp_total').text(Math.round(mrp_total));
                $('.price_discount').text(Math.round(price_discount));  
                $('.coupon_discount').text(Math.round(coupon_discount));
                $('.shipping_charge').text(appliedShipping.toFixed(2));    
                $('.grand_total').text(Math.round(total));
                $('.grand_total_button').text(total.toFixed(2));
                $('#grand_total_input').val($('.grand_total').text().trim());                

                // Disable checkout if nothing selected
                $('.placeOrderBtn').prop('disabled', selectedCount === 0);
            }

            $(document).on('change', '.item-checkbox', function(){
                let rowId = $(this).data('rowid');
                let checked = $(this).is(':checked');

                if(!this.checked){
                    $('#selectAll').prop('checked', false);
                }

                // store selected item in session
                $.ajax({
                    url: "/cart/select-item",
                    method: "POST",
                    data:{
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        rowId: rowId,
                        checked: checked
                    }
                });

                updateCartSummary();
            });

            $('#selectAll').on('change', function(){
                let checked = this.checked;
                $('.item-checkbox').each(function(){
                    $(this).prop('checked', checked);
                    let rowId = $(this).data('rowid');
                    $.ajax({
                        url: "/cart/select-item",
                        method: "POST",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            rowId: rowId,
                            checked: checked
                        }
                    });
                });

                updateCartSummary();
            });
            
            updateCartSummary();
        });

        $(document).ready(function(){
            $('#checkoutForm').on('submit', function(e){
                if($('.item-checkbox:checked').length === 0){
                    e.preventDefault();
                    alert('Please select at least one item');
                }
            });

            // $('.item-checkbox').change(function() {
            //     $('#cartForm').submit();
            // });
        });


        $(document).on('click', '.remove_coupon', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('front.removeCoupon') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('.coupon_discount').text('0');
                        $('#coupon_discount').val(0);
                        $('.compare-discount').hide();
                        location.reload(); 
                        showAlert(response.message, 'success');
                    }
                }
            });
        });
    </script>
@endsection