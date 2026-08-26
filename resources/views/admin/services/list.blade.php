@extends('admin.layouts.app')

@section('content')

@include('admin.message')
    <div class="card mb-0">
        <div class="card-body pb-0">
            <div class="row">
                <div class="row">
                    <div class="col-sm-8 col-12">
                        <div class="page-title">
                            <h4>Services</h4>
                            <span class="counts">{{ $services->total() }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 float-end">
                        <div class="flexContainer">
                            <form action="" method="get" >
                                <div class="d-flex">
                                    <div class="card-title mr-3">
                                        <a href="javascript:0" onclick="window.location.href='{{ route('services.index') }}'" class="refresh-icon" >
                                            <span class="sprites"></span>                                            
                                        </button>
                                    </div>
                
                                    <div class="card-tools">
                                        <div class="input-group input-group searchMain" >
                                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
                
                                            <div class="input-group-append">
                                                <button type="submit" class="btn">
                                                    <i class="iconoir-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route('services.create') }}" class="btn btn-primary ">Create</a>
                        </div>
                    </div>
                </div>                        
            </div>
        
            <div class="table-responsive mt-1">
                @php
                    use Illuminate\Support\Str;
                @endphp

                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0">Service</th>                            
                            <th class="border-top-0 text-end" width="150">Price</th>                            
                            <th class="border-top-0 text-end" width="100">Status</th>
                            <th class="border-top-0 text-end" width="100">Action</th>
                        </tr>
                    </thead>                     
                    <tbody id="productAccordion">
                        @if ($services->isNotEmpty())
                            @foreach($services as $key => $service)
                                @php
                                    $serviceImage = $service->service_images->first();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="product-row">
                                            <a href="{{ route('services.edit', $service->id) }}" class="show-tooltip">
                                                @if (!empty($serviceImage->image))
                                                    <img src="{{ asset('uploads/services/thumb/'.$serviceImage->image) }}" height="110" class="me-3 align-self-center rounded" >
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="110" class="me-3 align-self-center rounded" />
                                                @endif
                                                <span class="tooltip" style="bottom: 0; left:100px;">{{ $service->category?->category_name ?? 'No Category' }}</span>
                                            </a>
                                            <div class="flex-grow-1 text-truncate">
                                                <h5 class="product-title">
                                                    <a href="{{ route('services.edit', $service->id) }}">
                                                        {{ Str::limit($service->name, 70, '...') }}                                                                                                              
                                                    </a>                                                    
                                                </h5>
                                                <div class="small-fonts">                                                    
                                                    <p class="mb-0 text-muted">
                                                        <span class=""><b>{{ $service->id }}</b> / </span>
                                                    </p>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </td> 
                                                                                                                                      
                                    <td class="text-end"> 
                                        <div class="price">
                                            @if($service->discount_percent > 0)
                                                <h5 class="mb-1">₹{{ round($service->discount_price) }}</h5>
                                                <p class="text-muted tiny-font">
                                                    MRP <del>₹{{ $service->price }}</del><br />
                                                    <span class="discount">{{ $service->discount_percent }}% OFF</span>                                                    
                                                </p>
                                            @else
                                                <h5 class="mb-0">₹{{ number_format($service->price, 2) }}</h5>
                                            @endif
                                        </div>
                                    </td> 
                                             
                                    <td class="text-end">
                                        <div class="pull-right">
                                            @if ($service->status == 1)  
                                                <span class="sprites green-tick-icon"></span>
                                            @else
                                                <span class="sprites red-tick-icon"></span>
                                            @endif
                                        </div>
                                    </td>                  
                                    <td class="text-end">
                                        <div class="pull-right">
                                            <div class="flex">
                                                <a href="{{ route('services.edit', $service->id ) }}" class="edit-icon">
                                                    <span class="sprites"></span>
                                                </a>
                                                <a href="#" onclick="deleteService( {{ $service->id }} )" class="delete-icon" >
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </td> 
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Records not found</td>
                                </tr>
                            @endif
                    </tbody>
                </table>
            </div>
        </div>        
        <div class="card-body pb-0 clearfix">
            {{ $services->links() }}
        </div>          
    </div>    
@endsection

@section('customJs')
<script>
    function deleteService(id){
        var url = '{{ route("services.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                success: function(response){
                    if(response["status"]){
                        window.location.href="{{ route('services.index') }}"
                    } else {
                        window.location.href="{{ route('services.index') }}"
                    }
                }
            });
        }
    }
</script>
@endsection