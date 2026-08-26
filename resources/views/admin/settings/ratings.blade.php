@extends('admin.layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
        <div class="page-title">
            <h4>Ratings</h4>
            <span class="counts">{{ $total }}</span>
        </div>        

            <div class="table-responsive mt-3">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0">Product</th>
                            <th class="border-top-0 text-end" width="10%">User</th>
                            <th class="border-top-0 text-end" width="10%">Status</th>
                            <th class="border-top-0 text-end" width="7%">Action</th>
                            <th class="border-top-0 text-end" width="5%">Delete</th>
                        </tr>
                    </thead>                     
                    <tbody>
                        @if ($reviews->isNotEmpty())
                            @foreach ($reviews as $key => $review)                                                                   
                                <tr>
                                    <td>
                                        @php
                                            $productImage = optional($review->product)->product_images->first();
                                            $userImage = $review->user->image ?? null;
                                        @endphp

                                        <div class="product-row">                                            
                                            <a href="{{ $review->product->url }}" target="_blank">                                                
                                                @if (!empty($productImage?->image))
                                                    <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="100" class="me-3 align-self-center rounded">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="100" class="me-3 align-self-center rounded">
                                                @endif

                                                <p class="variant-counts">{{ $review->id }}</p>
                                            </a>

                                            <div class="flex-grow-1 text-truncate">
                                                <h5 class="product-title">
                                                    <a href="{{ $review->product->url }}" target="_blank" class="show-tooltip"> 
                                                        {{ Str::limit($review->product->title, 70, '...') }}
                                                    </a>
                                                </h5>                                                
                                                <div class="flex-star mt-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <span class="sprites star-active"></span>                                                        
                                                        @else
                                                            <span class="sprites star"></span>                                                        
                                                        @endif
                                                    @endfor                                                    
                                                </div>
                                                <p>{{ $review->review }}</p>
                                                {{-- <b>{{ $review->rating }}</b> --}}                                                
                                            </div>
                                        </div>                                       
                                    </td>                                    
                                    <td class="text-end">
                                        <div class="pull-right show-tooltip">
                                            <img src="{{ $review->user && $review->user->image 
                                                ? asset('uploads/user/'.$review->user->image) 
                                                : asset('admin-assets/img/default-user.png') }}" 
                                            width="50" height="50" class="rounded-circle" >

                                            <p class="tooltip" style="bottom: 13px; left:55px;">{{ $review->user->name }} ({{ $review->user->id }})</p>
                                        </div>
                                    </td>                                    
                                    <td class="text-end">
                                        @if($review->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif                                     
                                    </td>
                                    <td class="text-end">
                                        @if($review->status == 0)
                                            <a href="{{ route('review.approve', $review->id) }}" class="btn btn-sm btn-outline-primary">Approve</a>
                                        @else
                                            <a href="{{ route('review.reject', $review->id) }}" class="btn btn-sm btn-outline-danger">Reject</a>
                                        @endif
                                    </td>     
                                    <td class="text-end">
                                        <a href="javascript:0" onclick="deleteReview( {{ $review->id }} )" class="delete-icon pull-right">
                                            <span class="sprites"></span>
                                        </a>                                        
                                    </td>                                
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">Records not found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</section>
@endsection


@section('customJs')
<script>
    function deleteReview(id){
        var url = '{{ route("review.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                success: function(response){
                    if(response["status"]){
                        window.location.href="{{ route('review.index') }}"
                    } else {
                        window.location.href="{{ route('review.index') }}"
                    }
                }
            });
        }
    }
</script>
@endsection