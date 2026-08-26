@extends('admin.layouts.app')

@section('content')
    
    <div class="card mb-0">
        <div class="card-body pb-0">
            <div class="row mb-2">
                <div class="col-sm-10 col-12">
                    <h3>Order: #{{ $order->id }}</h3>                    
                </div>
                <div class="col-sm-2 col-12">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary float-end">Back to Order</a>
                </div>
            </div>
        </div>
    </div>
   
    <div class="card">
        <div class="card-body py-0">                
            @include('admin.message')
            <div class="row">                
                <div class="col-md-9 col-12">                       
                    <div class="row">                
                        <div class="col-md-8 col-12">
                            <div class="card border mb-2">
                                <div class="card-body">                                                                        
                                    <h4 class="mb-1 mt-0">{{ $order->name}}</h4>
                                    <p class="mb-0 mt-1">{{ $order->address }}</p>
                                    <p class="mb-0 mt-0">{{ $order->locality }}, {{ $order->city }},</p>
                                    <p class="mb-0 mt-0">{{ $order->stateName }}-{{ $order->zip }}.</p>
                                    <p class="mb-0 mt-2"><b>Mobile: {{ $order->mobile }}</b></p>                                    
                                </div>
                            </div>  
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card border mb-2">
                                <div class="card-body">
                                    <div class="flex-details">
                                        <p class="label">Payment Mode</p>
                                        <p class="right">: {{ $order->payment_method == 'cod' ? 'COD' : 'Razorpay' }}</p>
                                    </div>

                                    <div class="flex-details">
                                        <p class="label">Total</p>
                                        <p class="right">: <b>₹{{ number_format($order->grandtotal,2) }}</b></p>
                                    </div>

                                    <div class="flex-details">
                                        <p class="label">Shipped Date</p>
                                        <p class="right">:                                        
                                            @if (!empty($order->shipped_date))
                                                {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y')}}
                                            @else
                                                Pending
                                            @endif                                        
                                        </p>                           
                                    </div>
                                    <div class="flex-details">
                                        <p class="label">Courier</p>                                        
                                        <p class="right">: {{ $order->latestStatus->courier ?? '-' }}</p>
                                    </div>
                                    <div class="flex-details">
                                        <p class="label">Status</p>                                        
                                        <p class="right">:
                                            @php
                                                $status = $order->latestStatus->status ?? 'confirmed';
                                                $badgeClasses = [
                                                    'Confirmed'         => 'bg-secondary',
                                                    'Shipped'           => 'bg-primary',
                                                    'Out for Delivery'  => 'bg-warning',
                                                    'Delivered'         => 'bg-success',
                                                    'Cancelled'         => 'bg-danger',
                                                    'Exchanged'         => 'bg-danger',
                                                    'Returned'          => 'bg-danger'
                                                ];
                                            @endphp
                                            <span class="badge {{ $badgeClasses[$status] ?? 'bg-dark' }}">
                                                {{ ucfirst(str_replace('_',' ',$status)) }}
                                            </span>                                           
                                        </p>                                                                                
                                    </div>                                                                 
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border">
                        <div class="card-body"> 
                            <table class="table">
                                <thead class="table-light">  
                                    <tr>
                                        <th class="border-top-0">Products details</th>                                
                                        <th class="border-top-0 text-end" width="50">Price</th>
                                        <th class="border-top-0 text-end" width="100">Qty</th>
                                        <th class="border-top-0 text-end" width="100">Amount</th>
                                    </tr>                                                    
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">        
                                                    @php
                                                        $productImage = $item->product->images->first();
                                                        $image = null;
                                                        if (!empty($item->product_variant_id)) {
                                                            $image = $item->variant->variant_image ?? null;
                                                        }
                                                        if (!$image) {
                                                            $image = $productImage ?? null;
                                                        }
                                                    @endphp  

                                                    <a href="{{ route('front.product', [
                                                            $item->product->category->category_slug,
                                                            $item->product->subCategory->sub_category_slug,
                                                            $item->product->subSubCategory->sub_sub_category_slug,
                                                            'slug' => $item->product->slug
                                                        ]) }}" target="_blank">
                                                        @if($image)
                                                            <img src="{{ asset('uploads/product/small/'.$image->image) }}" height="100" class="me-3 align-self-center rounded">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" width="60">
                                                        @endif
                                                    </a>       
                                                    
                                                    <div class="flex-grow-1 text-truncate">
                                                        <h5 class="product-title">
                                                            <a href="{{ route('front.product', [
                                                                        $item->product->category->category_slug,
                                                                        $item->product->subCategory->sub_category_slug,
                                                                        $item->product->subSubCategory->sub_sub_category_slug,
                                                                        'slug' => $item->product->slug
                                                                    ]) }}" target="_blank">{{ Str::limit($item->product->title, 75, '...') }}</a>
                                                        </h5>
                                                        <p class="text-muted tiny-font">{{ Str::limit($item->product->short_description, 75, '...') }}</p>
                                                        
                                                        @php
                                                            $uniqueSizes = $order->items->pluck('size')->filter()->unique('id');
                                                            $uniqueColors = $order->items->pluck('color')->filter()->unique('id');
                                                        @endphp

                                                        <p class="mb-0 text-muted tiny-font show-tooltip">
                                                            @if($item->size)
                                                                Size: {{ $item->size->name ?? '-' }} 
                                                                <span class="tooltip" style="bottom: 0; left:55px;">
                                                                    ID: {{ $item->size->id }}
                                                                </span>
                                                            @endif
                                                        </p>
                                                        <p class="mb-0 text-muted tiny-font show-tooltip">
                                                            @if($item->color)
                                                                Color: {{ $item->color->name ?? '-' }}
                                                                <span class="tooltip" style="bottom: 0; left:55px;">
                                                                    ID: {{ $item->color->id }}
                                                                </span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                @if($item->discounted_price)
                                                    <p class="mt-1">₹{{ round($item->discounted_price) }}</p>
                                                    <p class="text-muted"><del>₹{{ round($item->price) }}</del></p>
                                                @else
                                                    <p>₹{{ round($item->price) }}</p>
                                                @endif
                                            </td>
                                            <td class="text-end">x {{ $item->qty }}</td>
                                            <td class="text-end">                                                
                                                @if($item->discounted_price)
                                                    <p class="mt-1">₹{{ round($item->qty*$item->discounted_price) }}</p>
                                                @else
                                                    <p class="mt-1">₹{{ round($item->qty*$item->price) }}</p>
                                                @endif                                                
                                            </td>                                            
                                        </tr>
                                    @endforeach
                                        <tr>                                            
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">Total:</td>
                                            <td class="text-end"><b>₹{{ $item->subtotal }}</b></td>
                                        </tr>                                        
                                        <tr>                                            
                                            <td></td>
                                            <td></td>
                                            <td class="text-end"><p style="color: green;">Discount <b>{{ (!empty($item->coupon_code)) ? '('.$item->coupon_code.')' : '' }}</b>:</p></td>
                                            <td class="text-end"><p style="color: green;">₹{{ round($item->discount) }}</p></td>
                                        </tr>
                                        <tr>                                            
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">Platform:</td>
                                            <td class="text-end">₹{{ round($item->shipping) }}</td>
                                        </tr>
                                        <tr>                                            
                                            <td></td>
                                            <td></td>
                                            <td class="text-end"><b>Grand Total:</b></td>
                                            <td class="text-end"><b>₹{{ round($item->grandtotal) }}</b></td>
                                        </tr>
                                </tbody>
                            </table>                                             
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-12">  
                    <div class="card border">
                        <div class="card-body">                            
                            <form action="{{ route('order.changeTrackStatus',$order->id) }}" method="post">
                                @csrf

                                <h5 class="mb-2">Order Status</h4>
                                    
                                <input type="hidden" name="order_id" value="{{ $order->id }}" />

                                <div class="form-group">                                    
                                    <select name="status" id="delivery_status" class="form-select">
                                        <option value="Placed" {{ ($latestStatus && $latestStatus->status == 'Placed') ? 'selected' : '' }}>Placed</option>
                                        <option value="Packed" {{ ($latestStatus && $latestStatus->status == 'Packed') ? 'selected' : '' }}>Packed</option>
                                        <option value="Shipped" {{ ($latestStatus && $latestStatus->status == 'Shipped') ? 'selected' : '' }}>Shipped</option>
                                        <option value="Out for Delivery" {{ ($latestStatus && $latestStatus->status == 'Out for Delivery') ? 'selected' : '' }}>Out for Delivery</option>
                                        <option value="Delivered" {{ ($latestStatus && $latestStatus->status == 'Delivered') ? 'selected' : '' }}>Delivered</option>
                                        <option value="Cancelled" {{ ($latestStatus && $latestStatus->status == 'Cancelled') ? 'selected' : '' }}>Cancelled</option>
                                        <option value="Returned" {{ ($latestStatus && $latestStatus->status == 'Returned') ? 'selected' : '' }}>Returned</option>
                                        <option value="Exchanged" {{ ($latestStatus && $latestStatus->status == 'Exchanged') ? 'selected' : '' }}>Exchanged</option>
                                    </select>                            
                                </div>

                                @if($latestStatus->cancel_comments)
                                    <p class="mb-0">Cancel reason:</p>
                                    <p class="mt-0">{{ $latestStatus->cancel_comments }}</p>
                                @endif                                

                                <div class="mb-1">
                                    <div class="form-group">
                                        <input placeholder="Date" autocomplete="off" value="{{ $latestStatus->date ?? '' }}" type="datetime-local" name="status_date" id="status_date" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="tracking_number" value="{{ $latestStatus->tracking_number ?? '' }}" class="form-control" placeholder="Tracking number" />
                                </div> 
                                <div class="form-group">
                                    <select name="courier" class="form-select">
                                        <option value="Shadofax" {{ ($latestStatus && $latestStatus->courier == 'Shadofax') ? 'selected' : '' }}>Shadofax</option>
                                        <option value="Delivery" {{ ($latestStatus && $latestStatus->courier == 'Delivery') ? 'selected' : '' }}>Delivery</option>                                        
                                    </select>                            
                                </div>
                                <div class="form-group">
                                    <textarea name="note" value="" class="form-control" placeholder="Note" cols="3" >{{ $latestStatus->note ?? '' }}</textarea>                                    
                                </div> 
                                <div class="mt-1">
                                    <button class="btn btn-primary caps-btn">Update</button>
                                </div>                        
                            </form>

                            <hr />
                            
                            <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                                <h5 class="mb-2">Send Inovice to Customer</h5>
                                <select name="userType" id="userType" class="form-select">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>                            
                                <div class="mt-2">
                                    <button class="btn btn-primary caps-btn">Send Invoice</button>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </section>
@endsection

@section('customJs')
    <script>
        $("#sendInvoiceEmail").submit(function(event){
            event.preventDefault();
            var element = $(this);

            if (confirm("Are you sure you want to send email?")){
                $.ajax({
                    url: '{{ route("orders.sendInvoiceEmail",$order->id) }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("orders.detail",$order->id ) }}';
                    }
                });
            }
        });
    </script>
@endsection
