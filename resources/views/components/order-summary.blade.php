<div class="cart-summery">                    
    @if(session()->has('coupon_discount'))
        <div class="part">
            <h5>Coupon</h5>
            <div class="repeate-row">
                <div class="left">
                    <div class="flex">
                        <div>
                            <svg height="45px" width="45px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
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
                            <b>1 Coupon applied ({{ $couponCode }})</b>
                            <p class="compare-discount tiny-font">You saved additional - ₹{{ $couponDiscount }}</p>                                                    
                        </div>
                    </div>
                </div>
                <div class="right">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#discount" class="btn btn-outline-danger btn-sm">Edit</a>                                            
                </div>
            </div>
        </div>
    @endif
    
    <div class="part">
        <h6>Delivery Estimates</h6>
        @foreach (Cart::content() as $item)
            <div class="repeate-row">
                @if($buttonType === 'checkout')
                    {{ $item->name }}
                @elseif($buttonType === 'pay')
                    <input type="checkbox"
                        class="item-checkbox d-none"
                        data-price="{{ $item->price }}"
                        data-qty="{{ $item->qty }}"
                        data-discount_percentage="{{ $item->options->discount_percent }}" checked
                    >
                    {{ $item->name }}
                @endif
                <p class="tiny-font">Delivery between</p>
            </div>                        
        @endforeach        
    </div>

    <div class="part">                            
        <h5>Price Details (<span class="selected-items">0</span> <span>items</span>)</h5>
        
        @if (Cart::count() > 0)

            <div class="repeate-row">
                <div class="left">Total MRP</div>
                <div class="right">₹<span class="mrp_total">0.00</span></div>
            </div>

            <div class="repeate-row priceDetailsBox">
                <div class="left">Discount on MRP</div>
                <div class="right">
                    <span class="compare-discount">- ₹<span class="price_discount">0.00</span></span>
                </div>
            </div>

            @if($couponDiscount)
                <div class="repeate-row priceDetailsBox">
                    <div class="left">
                        <div class="flex">
                            Coupon Discount
                            <form action="{{ route('front.removeCoupon') }}" method="POST">
                                @csrf
                                <button type="submit" class="remove_coupon">
                                    <svg fill="#ff0000" width="11px" height="11px" viewBox="-3.5 0 19 19">
                                        <path d="M11.383 13.644A1.03 1.03 0 0 1 9.928 15.1L6 11.172 2.072 15.1a1.03 1.03 0 1 1-1.455-1.456l3.928-3.928L.617 5.79a1.03 1.03 0 1 1 1.455-1.456L6 8.261l3.928-3.928a1.03 1.03 0 0 1 1.455 1.456L7.455 9.716z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="right">
                        <input type="hidden" id="coupon_discount" value="{{ $couponDiscount }}">
                        <span class="compare-discount">- ₹<span class="coupon_discount">{{ $couponDiscount }}</span></span>
                    </div>
                </div>
            @else
                <div class="repeate-row">
                    <div class="left">Coupon Discount</div>
                    <div class="right">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#discount">Apply Discount</a>
                    </div>
                </div>
            @endif

            @auth
                <div class="repeate-row priceDetailsBox">
                    <div class="left">Platform Fee</div>
                    <input type="hidden" id="shipping_charge" value="{{ $shippingCharge }}">
                    <div class="right">₹{{ number_format($shippingCharge,2) }}</div>
                </div>
            @endauth

            <div class="repeate-row total-amount">
                <div class="left">Total Amount</div>
                <div class="right">₹ <span class="total_amount">0.00</span></div>
            </div>
        @endif

    <div class="order-btn mt-3">
        @if($buttonType === 'checkout')
            <a href="{{ route('front.checkout') }}" class="btn btn-primary w-100 placeOrderBtn" disabled>
                Checkout
            </a>            
        @elseif($buttonType === 'pay')
            <div class="btn-group w-100 mb-3" role="group">
                <input type="radio" class="btn-check" name="payment_method" id="payment_cod" value="cod" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="payment_cod">COD</label>

                <input type="radio" class="btn-check" name="payment_method" id="payment_razorpay" value="razorpay" autocomplete="off">
                <label class="btn btn-outline-primary" for="payment_razorpay">RazorPay</label>
            </div>

            <div class="mt-2">
                <button id="cod-form" class="btn-primary btn btn-block w-100" type="submit">Pay on COD</button>
                <button id="razorpay-form" class="btn-primary btn btn-block w-100 d-none" type="submit">Pay ₹<span class="total_amount">0.00</span></button>
            </div>
        @endif
    </div>
    </div>
</div>