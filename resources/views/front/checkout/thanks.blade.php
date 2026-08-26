@extends('front.layouts.app')

@section('title', 'Order Confirmed')

@section('content')    

<div class="container">
    <div class="placed-order-summary">
        <div class="wrapper-inside">
        <div class="confirmation-card">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" class="tick"><path fill-rule="nonzero" d="M7.59 0l1.195 1.72 1.72-1.104.465 2.06 2.005-.34-.325 2.103 1.964.488-1.053 1.805L15.2 7.985l-1.64 1.253 1.053 1.806-1.964.488.325 2.102-2.005-.34-.465 2.06-1.72-1.104-1.194 1.7-1.194-1.72-1.721 1.103-.466-2.038-2.003.34.323-2.103-1.963-.488 1.052-1.806L0 7.985l1.64-1.253L.566 4.927 2.53 4.44l-.323-2.102 2.003.339.466-2.06 1.72 1.104L7.591 0zm1.768 6.12L6.687 9.007a.045.045 0 0 1-.067 0L5.64 7.955a.358.358 0 0 0-.53 0 .417.417 0 0 0 0 .564l1.283 1.37c.07.075.165.112.265.112h.002c.1 0 .195-.039.265-.115l2.97-3.208a.417.417 0 0 0-.007-.563.358.358 0 0 0-.529.006z"></path></svg>
            <h2 class="title">Order Confirmed</h2>
            <p>Your order is confirmed. You will receive an order confirmation <br />
            email/SMS shortly with the expected delivery date for your items.</p>
        </div>
            
        {{-- Order ID: {{ $id }} --}}

        <div class="delivery-address">
            <p>Delivering to:</p>
            @if($order->address)
                <p class="mt-2"><b>{{ $order->address->name }} | {{ $order->address->mobile }}</b></p>
                <p class="small-font">{{ $order->address->address }},<br />
                {{ $order->address->locality }}, {{ $order->address->city }}, {{ $order->address->state->name ?? '' }} - {{ $order->address->zip }}</p>
            @endif

            <a href="{{ route('account.orderDetail', $order->id) }}" class="btn btn-outline-primary mt-3">Order Details</a>
            <hr />
            <p>You can Track/View/Modify order from orders page.</p>
        </div>

        <div class="btn-action">
            <a href="{{ route('front.home') }}" class="btn btn-outline-primary">Continue Shopping <span id="countdown">10</span> seconds</a>
            <a href="{{ route('account.orderDetail', $order->id) }}" class="btn btn-primary">View Order</a>
        </div>
    </div>

            {{-- @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        @php
                            $variantImage = null;

                            if(!empty($item->variant_id)) {
                                $variant = $item->product->variants
                                            ->where('id', $item->variant_id)
                                            ->first();

                                $variantImage = $variant?->image;
                            }
                            $defaultImage = optional($item->product->images->first())->image;
                            $imageToShow = $variantImage ?? $defaultImage;
                        @endphp

                        @if($imageToShow)
                            <img src="{{ asset('uploads/product/small/'.$imageToShow) }}"
                                width="80"
                                class="img-fluid rounded">
                        @endif                                                
                    </td>
                    <td>{{ $item->name }}</td>
                    @if($item->variant_id)
                        <p>
                            <strong>Variant ID:</strong> {{ $item->variant_id }}
                        </p>
                    @endif

                    @if($item->variant)
                        <p>
                            <strong>Variant Name:</strong> {{ $item->variant->name }}
                        </p>
                    @endif
                    <td>
                        @if($item->color)
                            <small class="text-muted">{{ $item->color }}</small><br>
                        @endif
                    </td>
                    <td>
                        @if($item->size)
                            <small class="text-muted">{{ $item->size }}</small><br>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $item->qty }} </small>                    
                    </td>
                    <td>
                        <strong>₹{{ number_format($item->total,2) }}</strong>
                    </td>                            
                </tr>
            @endforeach     --}}
       
        {{-- <div class="totals text-end">
            <p>Subtotal: ₹{{ number_format($order->subtotal,2) }}</p>
            <p>Shipping: ₹{{ number_format($order->shipping,2) }}</p>
            @if($order->discount > 0)
                <p>Discount: -₹{{ number_format($order->discount,2) }}</p>
            @endif
            <h5>Grand Total: ₹{{ number_format($order->grandtotal,2) }}</h5>
        </div> --}}
          
        
    </div> 
</div>   
@endsection

@section('customJs')
<script>
    let seconds = 10;

    let timer = setInterval(function(){
        seconds--;
        document.getElementById("countdown").innerText = seconds;

        if(seconds <= 0){
            clearInterval(timer);
            window.location.href = "{{ route('front.home') }}";
        }
    },1000);
</script>
@endsection