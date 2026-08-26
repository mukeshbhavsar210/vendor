<div class="modal fade" id="deliveryAddress" tabindex="-1" aria-labelledby="deliveryAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deliveryAddressLabel">Select Delivery address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form method="POST" action="{{ route('address.default') }}">
                    @csrf

                    @php
                        $defaultAddressId = old(
                            'address_id',
                            optional($address->firstWhere('default_address', 1))->id
                        );
                    @endphp

                    @foreach($delivery_address as $value)
                        <div class="default-card {{ $value->default_address == 1 ? 'delivery-address' : '' }}">                            
                            <label class="delivery-address-card">
                                <div class="card-body">           
                                    <label class="custom-radio">                                                                                                                    
                                        <input type="radio" name="address_id" value="{{ $value->id }}" class="address-radio" {{ $defaultAddressId == $value->id ? 'checked' : '' }} >
                                        <span class="radio-mark"></span>                                    
                                    </label>

                                    <div class="address-content">
                                        <div class="left">                                                    
                                            <p>{{ $value->default_address ? 'Default' : 'Other' }} Address</p>
                                            <h6>{{ $value->name }} - {{ $value->mobile }}</h6>
                                            <p class="text-muted mb-0">{{ Str::limit($value->address, 50, '...') }}</p>

                                            <div class="d-none control-btn">
                                                <p class="text-muted mb-0">{{ $value->locality }}, {{ $value->city }} - {{ $value->zip }}, 
                                                    {{ $value->state->name ?? '' }}.
                                                </p>                                                
                                                
                                                <div class="flex-end">
                                                    <ul class="flex mt-3">
                                                        <li><button type="submit" name="action" value="default" class="btn btn-primary btn-sm caps-btn">Deliver Here</button></li>                                                                        
                                                        <li><button type="submit" name="action" value="delete" class="btn-noback delete-icon-new">
                                                            <span class="sprites"></span>
                                                        </button></li>
                                                        {{-- <li>
                                                            <button type="submit"
                                                                class="btn btn-outline-dark caps-btn btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editAddressModal"                                                
                                                                data-id="{{ $value->id }}"
                                                                data-name="{{ $value->name }}"
                                                                data-mobile="{{ $value->mobile }}"
                                                                data-address="{{ $value->address }}"
                                                                data-state="{{ $value->state_id }}">
                                                                Edit
                                                        </button>
                                                        </li>  --}}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="badge-right">{{ $value->address_type }}</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </form>

                @php
                    $types = $delivery_address->pluck('address_type')->toArray();
                @endphp

                @if(!(in_array('Home', $types) && in_array('Office', $types)))
                    <a href="#" class="btn btn-outline-dark" style="margin-left: 35px" data-bs-toggle="modal" data-bs-target="#createAddressModal">
                        + Add New Address
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="commonCartUpdateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalTitle">Sizes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled" id="modalList"></ul>                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="discount" tabindex="-1" aria-labelledby="discountLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('coupon.apply') }}" method="POST" id="couponForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="discountLabel">Apply Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <div class="scroll-body">
                        @foreach($coupons as $coupon)
                            <div class="coupon-box {{ old('coupon_discount.id', session('coupon_discount.id')) == $coupon->id ? 'active' : '' }}">
                                <label>
                                    <div class="left">
                                        <label class="custom-radio">
                                            <input type="radio" name="coupon_id" value="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                            {{ old('coupon_discount.id', session('coupon_discount.id')) == $coupon->id ? 'checked' : '' }} >
                                            <span class="radio-mark"></span>
                                        </label>
                                    </div>

                                    <div class="right">
                                        <div class="code-details">
                                            <div class="code">{{ $coupon->code }}</div>
                                        </div>

                                        <p class="title">{{ $coupon->name }}</p>
                                        <p class="text-muted">
                                            @if($coupon->type == 'percent')
                                                {{ $coupon->discount_amount }}% off
                                            @else
                                                ₹{{ $coupon->discount_amount }} off
                                            @endif              
                                            on minimum purchase of ₹{{ $coupon->min_amount }}.                                          
                                        </p>
                                        <p class="text-muted">                                                        
                                            Expire on:
                                            {{ \Carbon\Carbon::parse($coupon->expires_at)->format('jS F Y | h:i A') }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer-extra">                                
                    <div class="max-savings">
                        <p>Maximum savings:</p> 
                        {{-- @if($store_discount)
                            <p class="discount-text">₹{{ round($coupon_discount) }}</p> 
                        @endif --}}
                    </div>
                    <div>
                        <button class="btn btn-primary btn-big" type="submit" data-bs-dismiss="modal">Apply</button>
                    </div>                                
                </div>                                    
            </form>
        </div>
    </div>
</div>