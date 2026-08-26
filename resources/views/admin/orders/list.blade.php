@extends('admin.layouts.app')

@section('content')

@include('admin.message')

<div class="card">
    <div class="card-body">  
        <div class="page-title">
            <h4>Orders</h4> 
            <span class="counts">{{ $totalOrders }}</span>
        </div>                                  
        <div class="tab-content mt-2">
            <div class="table-responsive browser_users">
                <table class="table table-text mb-0">
                    <thead class="table-light">
                        <tr>                            
                            <th class="border-top-0" width="140">Photo</th>   
                            <th class="border-top-0 text-end" width="130">Size</th>
                            <th class="border-top-0 text-end" width="130">Color</th>
                            <th class="border-top-0 text-end" width="150">Grand Total</th>
                            <th class="border-top-0 text-end" width="140">Status</th> 
                        </tr>
                    </thead>
                    <tbody >
                        @foreach($orders as $key => $order)                          
                            <tr>                               
                                <td>                                    
                                    <div class="product-row">
                                        @foreach($order->items as $item)
                                           
                                        @endforeach

                                        @php
                                            $items = $order->items;
                                            $firstItem = $items->first();
                                            $remainingCount = $items->count() - 1;

                                            $productImage = $item->product->images->first();
                                            $image = null;
                                            if (!empty($item->product_variant_id)) {
                                                $image = $item->variant->variant_image ?? null;
                                            }
                                            if (!$image) {
                                                $image = $productImage ?? null;
                                            }
                                        @endphp

                                    @if($firstItem)
                                        <a href="{{ route('front.product', [
                                                    $item->product->category->category_slug,
                                                    $item->product->subCategory->sub_category_slug,
                                                    $item->product->subSubCategory->sub_sub_category_slug,
                                                    'slug' => $item->product->slug
                                                ]) }}" target="_blank" class="">
                                                
                                            @if($image && !empty($image->image))
                                                <img src="{{ asset('uploads/product/small/'.$image->image) }}" height="90" class="me-3 align-self-center rounded" />
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="90" class="me-3 align-self-center rounded" />
                                            @endif
                                        </a>                                                   

                                        <div class="flex-grow-1 text-truncate">
                                            <h5 class="product-title">
                                                <a href="{{ route('orders.detail',$order->id) }}">{{ $firstItem->product->title }}</a>
                                                @if($remainingCount > 0)
                                                    <span class="text-muted tiny-font"><b>+{{ $remainingCount }}</b></span>
                                                @endif
                                            </h5>

                                            <div class="small-fonts">                                                    
                                                <p class="mb-0 text-muted">
                                                    {{ \Carbon\Carbon::parse(optional($order->items->first())->created_at)->format('d M, Y') }}<br />
                                                    <a href="{{ route('orders.detail',$order->id) }}" >{{ $order->id }}</a>
                                                </p>
                                            </div>
                                        </div>
                                        @endif                                        
                                    </div>
                                </td>       
                                <td class="text-end">
                                    @php
                                        $uniqueSizes = $order->items->pluck('size')->filter()->unique('id')->values();
                                    @endphp

                                    @if($uniqueSizes->count())
                                        <p class="mt-2">{{ $uniqueSizes->first()->name }}</p>

                                        @if($uniqueSizes->count() > 1)
                                            <span class="text-muted">
                                                +{{ $uniqueSizes->count() - 1 }}
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif                                    
                                </td>

                                <td class="text-end">
                                    @php
                                        $uniqueColors = $order->items->pluck('color')->filter()->unique('id')->values();
                                    @endphp

                                    @if($uniqueColors->count())
                                        <p class="mt-2">{{ $uniqueColors->first()->name }}</p>

                                        @if($uniqueColors->count() > 1)
                                            <span class="text-muted">
                                                +{{ $uniqueColors->count() - 1 }}
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif                                    
                                </td>
                                <td class="text-end">
                                    <h5 class="mt-2 mb-0">₹{{ round($order->grandtotal) }}</h5>     
                                    <p class="tiny-font text-muted mt-0">
                                        
                                        {{ optional($order->items->first())->payment_method == 'cod' ? 'COD' : 'Razorpay' }}                                        
                                    </p>
                                </td>                                 
                                <td class="text-end">                                                               
                                    <a href="#" class="track-order-btn" data-order-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#orderTrackingModal">
                                        @php
                                            $status = $order->latestStatus->status ?? 'confirmed';
                                            $badgeClasses = [
                                                'Confirmed'         => 'bg-secondary',
                                                'Shipped'           => 'bg-primary',
                                                'Out for Delivery'  => 'bg-warning',
                                                'Delivered'         => 'bg-success',
                                                'Cancelled'         => 'bg-danger'
                                            ];
                                        @endphp
                                        
                                        <span class="badge mt-3 {{ $badgeClasses[$status] ?? 'bg-dark' }}">
                                            {{ ucfirst(str_replace('_',' ',$status)) }}
                                        </span>
                                        
                                        @if($order->latestStatus->cancel_comments)
                                            <p class="tiny-font text-muted">{{ $order->latestStatus->cancel_comments }}</p>
                                        @endif 
                                    </a>                                                       
                                </td>                       
                            </tr>                    
                        @endforeach
                    </tbody>
                </table>  

                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderTrackingModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Status</h5>
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
@endsection


@section('customJs')
<script>
    $(document).ready(function(){        
        $('.track-order-btn').click(function(){            
            let orderId = $(this).data('order-id');

            let url = "{{ route('orders.order.tracking', ':id') }}";
            url = url.replace(':id', orderId);

            $.ajax({
                url: url,
                type: "GET",
                success: function(response){
                    let html = '';
                    if(response.length === 0){
                        html = '<li>No tracking available</li>';
                    }else{
                        response.forEach(function(status){
                            html += `
                                <li class="active">
                                    <svg fill="#cccccc" width="22px" height="22px" viewBox="0 0 24 24">
                                        <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm5.676,8.237-6,5.5a1,1,0,0,1-1.383-.03l-3-3a1,1,0,1,1,1.414-1.414l2.323,2.323,5.294-4.853a1,1,0,1,1,1.352,1.474Z"/>
                                    </svg>
                                    <p><b>${status.status.replaceAll('_',' ')}</b><br />
                                    <span class="text-muted tiny-font">on ${status.date}</span>
                                    </p>
                                </li>
                            `;
                        });
                    }
                    $('#trackingTimeline').html(html);
                }
            });
        });
    });
</script>
@endsection