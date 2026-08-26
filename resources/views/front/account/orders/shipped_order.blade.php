<div class="product-details">
    @foreach($order->items as $item)  
        <a href="#" class="product-details-link" data-bs-toggle="modal" data-bs-target="#shippedOrder_{{ $order->id }}" >  
            @include('front.account.orders.product_card')
        </a>
    @endforeach
</div>
                                      
<div class="modal fade" id="shippedOrder_{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shipped Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @foreach($order->items as $item)
                    @php
                        $productImage = getProductImage($item->product_id)
                    @endphp

                    <div class="align-items-center shipped_stage">
                        <div class="image">
                            @if (!empty($productImage->image))
                                <img class="product" src="{{ asset('uploads/product/small/'.$productImage->image) }}" >
                            @else
                                <img class="product" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                            @endif 
                        </div>
                        <div class="title">
                            <b>{{ $item->product->title }}</b>

                            <p class="tiny-font text-muted">
                                <strong>Order Placed:</strong>
                                {{ $order->created_at->format('d M Y') }}
                            </p>

                            <p class="tiny-font text-success">
                                <strong>Estimated Delivery:</strong>
                                {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('d M Y') }}
                            </p>                
                        </div>
                    </div>
                @endforeach

                @php
                    $stage = $order->delivery_stage;  
                    $steps = ['placed','packed','shipped','out_for_delivery','delivered'];
                    $stage = $order->delivery_status;   // 👈 from orders table
                @endphp

                <ul class="track-placed-order">
                    @foreach($steps as $step)
                        @php
                            $isActive = array_search($stage, $steps) >= array_search($step, $steps);
                        @endphp

                        <li class="step {{ $isActive ? 'active' : '' }}">
                            <svg fill="#cccccc" width="22px" height="22px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm5.676,8.237-6,5.5a1,1,0,0,1-1.383-.03l-3-3a1,1,0,1,1,1.414-1.414l2.323,2.323,5.294-4.853a1,1,0,1,1,1.352,1.474Z"/></svg>                            
                            <p><b>{{ ucwords(str_replace('_',' ',$step)) }}</b> on Thu, 5 Mar</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>