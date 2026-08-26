@extends('front.layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="container">        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
            @include('front.account.common.sidebar')              
                <div class="col-md-9 col-12 px-md-0">
                <div class="details-accounts">
                    <div class="row">            
                        <div class="col-8">
                            <h3>All Orders</h3>
                            <p>From anytime</p>
                        </div>
                        <div class="col-4">                            
                            <div class="float-end">
                                @if(request()->has('status') || request()->has('time'))
                                    <a href="{{ route('account.orders') }}" class="btn btn-outline-dark">Clear Filter</a>
                                @endif                                
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterOrdersModal">                                    
                                    Filter
                                </button>
                            </div>
                            
                            <div class="modal fade" id="filterOrdersModal" tabindex="-1" aria-labelledby="filterOrdersModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterOrdersModalLabel">Filter Orders</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="GET" action="{{ route('account.orders') }}">
                                        <div class="modal-body">
                                            <div class="modal-scroll">                                                
                                                <h6 class="mb-2">Status</h6>
                                                @foreach($statuses as $value => $label)
                                                    <label class="custom-radio">
                                                        <input type="radio" name="status" value="{{ $value }}"
                                                        {{ request('status','') == $value ? 'checked' : '' }}>
                                                        <span class="radio-mark"></span>
                                                        {{ $label }}
                                                    </label>
                                                @endforeach                                                                                        
                                            
                                                <h6 class="mb-2 mt-3">Time</h6>
                                                @foreach($time as $value => $label)
                                                    <label class="custom-radio">
                                                        <input type="radio" name="time" value="{{ $value }}"
                                                        {{ request('status','') == $value ? 'checked' : '' }}>
                                                        <span class="radio-mark"></span>
                                                        {{ $label }}
                                                    </label>
                                                @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div>
                                                    <a href="{{ route('account.orders') }}" class="btn btn-outline-dark">Clear Filter</a>                                                    
                                                </div>
                                                <div>                                                
                                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-history">
                        @if ($orders->isNotEmpty())
                            @foreach ($orders as $order)                                                                
                                @php
                                    $status = $order->latestStatus->status ?? 'Confirmed';
                                    $badgeClasses = [
                                        'Confirmed'         => 'confirmed',
                                        'Packed'            => 'packed',
                                        'Shipped'           => 'shipped',
                                        'Out for Delivery'  => 'out_delivery',
                                        'Delivered'         => 'delivered',
                                        'Cancelled'         => 'cancelled',
                                        'Returned'          => 'returned',
                                        'Exchanged'         => 'exchanged'
                                    ];
                                @endphp

                                <div class="individual {{ $badgeClasses[$status] ?? 'confirmed' }}">
                                    <div class="status">
                                        <div class="icon">
                                            @if(ucfirst($status) == 'Confirmed')
                                                <span class="sprites confirmed-icon"></span>
                                            @elseif(ucfirst($status) == 'Packed')
                                                <span class="sprites packed-icon"></span>
                                            @elseif(ucfirst($status) == 'Shipped')
                                                <span class="sprites shipped-icon"></span>
                                            @elseif(ucfirst($status) == 'Out for Delivery')
                                                <span class="sprites shipped-icon"></span>
                                            @elseif(ucfirst($status) == 'Cancelled')
                                                <span class="sprites cancelled-icon"></span>
                                            @elseif(ucfirst($status) == 'Returned')
                                                <span class="sprites returned-icon"></span>
                                            @elseif(ucfirst($status) == 'Exchanged')
                                                <span class="sprites exchanged-icon"></span>
                                            @else
                                                @if(ucfirst($status) == 'Delivered')
                                                    <span class="sprites delivered-icon"></span>
                                                    <div class="delivery-tick">                                                        
                                                        <span class="sprites green-tick-icon"></span>                                                        
                                                    </div>
                                                @endif                                                
                                            @endif
                                        </div>
                                        <div class="name">
                                            <p><b>{{ ucfirst(str_replace('_',' ',$status)) }}</b></p>

                                            <p class="date">
                                                on {{ \Carbon\Carbon::parse($order->latestStatus->date)->format('D, d M Y, g:i A') }}

                                                @if(ucfirst($status) == 'Shipped')
                                                    Arriving by
                                                @elseif (ucfirst($status) == 'Cancelled')       
                                                    As per your request
                                                @else                                                
                                                    
                                                @endif                                                
                                            </p>
                                        </div>
                                    </div>

                                    <div class="product-details">
                                        @foreach($order->items as $item)
                                            <a href="{{ route('account.orderDetail',$order->id) }}" class="product-details-link">  
                                                @include('front.account.orders.product_card')
                                            </a>                                            
                                            @include('front.account.orders.ratings_modal') 
                                        @endforeach
                                        
                                        @if(ucfirst($status) == 'Delivered')
                                            <div class="rating-delivered">                                               
                                                <div class="rating-icon">
                                                    <span class="sprites rating-ico"></span>
                                                </div>
                                                <div class="rating-rateBox">
                                                    @php
                                                        $userReview = $userReviews[$item->product->id] ?? null;
                                                    @endphp
                                                    @if($userReview)
                                                        <p>       
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $userReview->rating)
                                                                    <span class="star star_active"></span>                                       
                                                                @endif
                                                            @endfor                                       
                                                        </p>          
                                                        <p class="tiny-font">{{ $userReview->review }}</p>
                                                    @else                                                
                                                        <p>Rate & Review</p>                                                    
                                                        <div class="myRating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="star open-modal"
                                                                    data-value="{{ $i }}" data-product="{{ $item->product->id }}" data-bs-toggle="modal"
                                                                    data-bs-target="#ratingsModal_{{ $item->product->id }}">
                                                                    <span class="star"></span>
                                                                </i>
                                                            @endfor                                                        
                                                        </div> 
                                                    @endif
                                                </div>                                              
                                            </div>
                                        @endif                                        
                                        
                                        @if(ucfirst($status) == 'Confirmed' || ucfirst($status) == 'Shipped')
                                            <div class="track-button">
                                                <a href="#" class="btn btn-outline-dark w-50" data-bs-toggle="modal" data-bs-target="#cancelOrder_{{ $order->id }}">Cancel</a>
                                                {{-- <a href="#" class="btn btn-outline-dark w-50 track-order-btn" data-order-id="{{ $order->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#orderTrackingModal">Track</a> --}}

                                                <button 
                                                    class="btn btn-outline-dark w-50 track-order-btn"
                                                    data-order-id="{{ $order->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#orderTrackingModal">
                                                    Track
                                                </button>
                                            </div>
                                        @endif                                                                                
                                    </div>

                                    <div class="modal fade" id="cancelOrder_{{ $order->id }}" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="cancelOrderLabel">Cancel Order</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form method="POST" action="{{ route('account.order.cancel', $order->id) }}">
                                                    @csrf
                                                    <div class="modal-body">    
                                                        <p class="mb-1"><b>Reason for Cancellation</b></p>
                                                        <p class="tiny-font">Please tell us correct reason for cancellation. This information is only used to improve our service</p>
                                                        <hr />
                                                        <div class="reason-group mb-3 mt-3">
                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Incorrect size ordered" required>
                                                                <span class="radio-mark"></span>
                                                                Incorrect size ordered
                                                            </label>

                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Product not required anymore" required>
                                                                <span class="radio-mark"></span>
                                                                Product not required anymore
                                                            </label>

                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Cash issue" required>
                                                                <span class="radio-mark"></span>
                                                                Cash issue
                                                            </label>

                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Ordered by mistake">
                                                                <span class="radio-mark"></span>
                                                                Ordered by mistake
                                                            </label>

                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Wants to change style/color">
                                                                <span class="radio-mark"></span>
                                                                Wants to change style/color
                                                            </label>

                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Delayed Delivery Cancellation">
                                                                <span class="radio-mark"></span>
                                                                Delayed Delivery Cancellation
                                                            </label>
                                                        
                                                            <label class="custom-radio">
                                                                <input type="radio" name="cancel_reason" value="Duplicate order">
                                                                <span class="radio-mark"></span>
                                                                Duplicate order
                                                            </label>
                                                        </div>                                                                    
                                                        <textarea name="cancel_comments" class="form-control" placeholder="Additional comments" rows="3"></textarea>                        
                                                    </div>

                                                    <div class="modal-footer">                    
                                                        <div>
                                                            <p class="mt-2">Refund Details</p>
                                                        </div>
                                                        <button type="submit" class="btn btn-danger mt-3">Cancel Order</button>                    
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="orderTrackingModal" tabindex="-1">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Order Tracking</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <ul class="track-placed-order" id="trackingTimeline">
                                                        <li>Loading...</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @else                                
                                <div class="individual mb-0 text-center py-5">
                                    <span class="sprites empty-order"></span>
                                    <h5 class="mt-2 mb-2">Orders not found</h5>
                                    <a href="{{ route('front.shop') }}" class="btn btn-primary">Shop Now</a>
                                </div>
                        @endif                                                
                    </div>

                    @if ($orders->total() > 0)
                        <div class="pagination">
                            <ul>
                                @if (!$orders->onFirstPage())
                                    <li><a href="{{ $orders->previousPageUrl() }}">&lt; Previous</a></li>
                                @endif

                                <li>Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }}</li>

                                @if ($orders->hasMorePages())
                                    <li><a href="{{ $orders->nextPageUrl() }}">Next &gt;</a></li>
                                @endif
                            </ul>
                        </div>
                    @endif                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection

@section('customJs')
<script>
    $(document).on('click', '.open-review-modal', function () {        
        let orderId = $(this).data('order-id');

        $.ajax({
            url: '/get-order-items/' + orderId,
            type: 'GET',
            success: function (res) {
                $('#reviewModalContent').html(res);
                $('#commonReviewModal').modal('show');
            }
        });
    });
</script>
@endsection