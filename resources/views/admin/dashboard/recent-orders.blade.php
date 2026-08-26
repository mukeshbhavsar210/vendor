@foreach($orders as $order)
    <tr>
        <td class="px-0">
            <div class="d-flex align-items-center">
                 @if (!empty($order->user->image))
                    <img src="{{ asset('uploads/user/'.$order->user->image) }} " alt="user"  height="36" class="me-2 align-self-center rounded" />
                @else
                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="90" class="me-3 align-self-center rounded" />
                @endif
                
                <div class="flex-grow-1 text-truncate"> 
                    <h6 class="m-0 text-truncate">{{ $order->user->name ?? 'Guest' }}</h6>
                    <a href="#" class="font-12 text-muted text-decoration-underline">#{{ $order->id }}</a>                                                                                           
                </div>
            </div>
        </td>   
        <td class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">₹{{ number_format($order->grandtotal, 2) }}</span></td>
    </tr>
@endforeach
