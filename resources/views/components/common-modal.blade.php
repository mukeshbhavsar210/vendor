@props([
    'modalId' => '',
    'title' => '',
    'button' => '',
    'size' => '',
    'modalName' => null,
    'types' => [],
    'defaultAddressId' => null,
    'delivery_address' => null,
    'addresses' => collect(),

    'address' => null,    
    'homeExists' => false,
    'title' => '',
    'buttonText' => '',
    'modalId' => '',
    'action' => '',
    'modal' => null,   
    'method' => 'POST'
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom {{ $size }}">
        <div class="modal-content">
            <div class="modal-header">                    
                <h4 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h4>                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            @if ($modalName == 'slot-modal')                
                <form id="bookingForm">
                    <div class="slot-booking">
                        <div class="card-details" data-target="#collapseOne">                                                            
                            <label class="radio-label">                            
                                <div class="label">
                                    <div class="green">
                                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg"><path d="M15.29 2.096a.5.5 0 00-.859-.433l-9.8 10.714a.5.5 0 00.19.804l5.207 1.993-1.319 6.73a.5.5 0 00.86.433l9.8-10.714a.5.5 0 00-.19-.804l-5.207-1.993 1.319-6.73z" fill="#FFFFFF"></path></svg>
                                        <p>Instant</p>
                                    </div>
                                    <p><b>In 44 mins</b></p>
                                </div>                            
                                <input type="radio" name="booking_type" class="card-radio mt-1" value="instant" checked>
                            </label>
                        </div>

                        <div class="card-details" data-target="#collapseTwo">                                                            
                            <label class="radio-label">
                                <div class="label">
                                    <div>
                                        <h6>Schedule for later</h6>
                                        <small>Select your preferred day & time</small>
                                    </div>
                                </div>
                                <input type="radio" name="booking_type" class="card-radio mt-2" value="scheduled">
                            </label>

                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#serviceAccordion">
                                <div class="details">
                                    @php
                                        $dates = collect();
                                        for ($i = 0; $i < 5; $i++) {
                                            $date = now()->addDays($i);
                                            $dates->push([
                                                'value' => $date->format('Y-m-d'),
                                                'day'   => $i == 0 ? 'Today' : $date->format('D'),
                                                'date'  => $date->format('d'),
                                            ]);
                                        }

                                        $startTime = now()->copy()->startOfHour()->addHour();
                                        $timeSlots = [];

                                        for ($i = 0; $i < 15; $i++) {
                                            $time = $startTime->copy()->addHours($i);
                                            $timeSlots[$time->format('H:i')] = $time->format('g:i A');
                                        }
                                    @endphp
                                
                                    <div class="wrapper">
                                        <div class="details">
                                            @foreach($dates as $date)
                                                <label class="date common">
                                                    <input type="radio" name="date" value="{{ $date['value'] }}"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    <span>
                                                        <small>{{ $date['day'] }}</small>
                                                        <p><b>{{ $date['date'] }}</b></p>
                                                    </span>
                                                </label>
                                            @endforeach

                                            <div id="online-payment-message" class="payment-message" style="display:none;">
                                                <svg width="100%" height="100%" viewBox="0 0 24 24" fill="#545454" xmlns="http://www.w3.org/2000/svg"><path d="M15 16h4v-2h-4v2zM13 16h-2v-2h2v2z" fill="#545454"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M2.77 4C1.781 4 1 4.806 1 5.778v12.444C1 19.194 1.782 20 2.77 20h18.46c.988 0 1.77-.806 1.77-1.778V5.778C23 4.806 22.218 4 21.23 4H2.77zM3 8V6h18v2H3zm0 2v8h18v-8H3z" fill="#545454"></path></svg>
                                                <p>Online payment only for selected date</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wrapper">
                                        <h5>Select start time of service</h5>
                                        <div class="details">
                                            <div class="scroll-content">
                                                @foreach($timeSlots as $value => $label)
                                                    <label class="time common">
                                                        <input type="radio" name="time" value="{{ $value }}" 
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>                                                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-btn" >
                        <div class="alert d-none"></div>
                        <button type="button" id="updateBooking" class="btn btn-big btn-primary" data-bs-dismiss="modal" aria-label="Close">
                            {{ $button }}
                        </button>
                    </div>
                </form>

            @elseif ($modalName == 'select-address-modal')
                <form method="POST" action="{{ route('address.default') }}">
                    @csrf

                    <div class="modal-body">                                           
                        @foreach($addresses as $value)
                            <div class="default-card">                            
                                <label class="delivery-address-card">
                                    <div class="card-body">           
                                        <label class="custom-radio">                                                                                                                    
                                            <input type="radio" name="address_id" value="{{ $value->id }}" class="address-radio" {{ $defaultAddressId == $value->id ? 'checked' : '' }} >
                                            <span class="radio-mark"></span>                                    
                                        </label>

                                        <div class="address-content">
                                            <div class="cmn-wrapper">
                                                <p><b>{{ $value->address_type }}</b></p>
                                                {{-- <p>{{ $value->default_address ? 'Default' : 'Other' }} Address</p> --}}
                                                {{-- <h6>{{ $value->name }} - {{ $value->mobile }}</h6> --}}
                                                <p class="text-muted mb-0">{{ $value->address }}, {{ $value->locality }}, <br />{{ $value->city }}-{{ $value->zip }}, {{ $value->state->name ?? '' }}.</p>
                                            </div>

                                            <div class="action-menu">
                                                <button type="button" class="action-toggle">⋮</button>
                                                <div class="action-dropdown">
                                                    <a href="#"
                                                        class="edit-action"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editAddressModal"
                                                        data-id="{{ $value->id }}"
                                                        data-name="{{ $value->name }}"
                                                        data-mobile="{{ $value->mobile }}"
                                                        data-address="{{ $value->address }}"
                                                        data-state="{{ $value->state_id }}">
                                                        Edit
                                                    </a>
                                                    <a href="#" name="action" value="delete" class="delete-action">Delete</a>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach

                        <a href="#" class="btn-link mt-3" data-bs-toggle="modal" data-bs-target="#createAddressModal">
                            + Add another address
                        </a> 
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="action" value="default" class="btn btn-big btn-primary">{{ $button }}</button>
                    </div>
                </form>            
            
            @elseif ($modalName == 'create-address-modal')                

            @elseif ($modalName == 'login-modal')                
                <div class="modal-body" id="authModalBody">
                    <div class="login-form">
                        <p>Join us now to be a part of {{ config('app.name') }} family.</p>

                        <form action="{{ route('account.authenticate') }}" method="post" class="mt-4" >
                            @csrf
                            
                            <input type="hidden" name="redirect" value="{{ url()->full() }}">

                            <div class="form-group">
                                <input type="text" class="form-control floating-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                <label class="floating-label">Email</label>
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control floating-input @error('password') is-invalid @enderror" name="password" >
                                <label class="floating-label">Password</label>
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>                            
                            <div class="flex-end">                                      
                                <p class="mt-2">Don't have an account? <a href="#" class="open-signup"><b>Sign up</b></a></p>                                
                                <button type="submit" class="btn btn-primary">{{ $button }}</button>
                            </div>
                        </form>
                        
                        <div class="social-btns">
                            <p class="or">OR</p>
                            <div class="flex">                            
                                <a href="{{ url('auth/google') }}" class="btn btn-outline-dark w-50">                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-google_svg__a)"><path fill="#4285F4" d="M15.844 8.184c0-.544-.044-1.09-.138-1.625H8.16v3.08h4.321a3.703 3.703 0 0 1-1.6 2.431v2h2.579c1.514-1.394 2.384-3.452 2.384-5.886Z"></path><path fill="#34A853" d="M8.16 16c2.158 0 3.977-.708 5.303-1.93l-2.578-2c-.717.488-1.643.765-2.722.765-2.087 0-3.857-1.409-4.492-3.302h-2.66v2.061A8.001 8.001 0 0 0 8.16 16Z"></path><path fill="#FBBC04" d="M3.668 9.534a4.792 4.792 0 0 1 0-3.063V4.41H1.011a8.007 8.007 0 0 0 0 7.184l2.657-2.06Z"></path><path fill="#EA4335" d="M8.16 3.166a4.347 4.347 0 0 1 3.069 1.2l2.284-2.284A7.689 7.689 0 0 0 8.16 0 7.998 7.998 0 0 0 1.011 4.41l2.657 2.06C4.3 4.575 6.073 3.167 8.16 3.167Z"></path></g><defs><clipPath id="login-google_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                    Google
                                </a>                        
                                <a href="{{ url('auth/facebook') }}" class="btn btn-outline-dark w-50">                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-facebook_svg__a)"><path fill="#1877F2" d="M16 8a8 8 0 1 0-9.25 7.903v-5.59H4.719V8H6.75V6.237c0-2.005 1.194-3.112 3.022-3.112.875 0 1.79.156 1.79.156V5.25h-1.008c-.994 0-1.304.617-1.304 1.25V8h2.219l-.355 2.313H9.25v5.59A8.002 8.002 0 0 0 16 8Z"></path><path fill="#fff" d="M11.114 10.313 11.47 8H9.25V6.5c0-.633.31-1.25 1.304-1.25h1.008V3.281s-.915-.156-1.79-.156c-1.828 0-3.022 1.107-3.022 3.112V8H4.719v2.313H6.75v5.59c.828.13 1.672.13 2.5 0v-5.59h1.864Z"></path></g><defs><clipPath id="login-facebook_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                    Facebook
                                </a>                                             
                            </div>          
                        </div>

                        <p class="mt-3 tiny-font">By creating an account or logging in, you agree with {{ config('app.name') }} T&C and Privacy Policy</p>
                    </div>

                    <div class="signup-form">                        
                        <form action="{{ route('account.processRegister') }}" method="POST" name="registrationForm" id="registrationForm" class="mt-3">
                            @csrf

                            <div class="registration-message alert d-none"></div>
                            
                            <div class="form-group">
                                <input type="text" class="form-control floating-input" id="name" name="name">
                                <label class="floating-label">Name</label>
                                <p></p>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control floating-input" id="email" name="email">
                                <label class="floating-label">Email</label>
                                <p></p>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control floating-input" id="mobile" name="mobile">
                                <label class="floating-label">Mobile</label>
                                <p></p>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control floating-input" id="password" name="password">
                                        <label class="floating-label">Password</label>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control floating-input" id="password_confirmation" name="password_confirmation">
                                        <label class="floating-label">Confirm Password</label>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-end">                                                                
                                <p class="mt-2">Already have an account? <a href="" class="open-login"><b>Login</b></a></p>
                                <button type="submit" class="btn btn-primary">Register Account</button>
                            </div>                
                        </form> 
                    </div>
                </div>            

            @elseif ($modalName == 'discount-modal')
                <div class="modal fade" id="discount" tabindex="-1" aria-labelledby="discountLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-custom">
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

            @else
                
                
            @endif
        </div>
    </div>
</div>