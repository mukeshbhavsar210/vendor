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
        
            <div class="accordion" id="categoryAccordion">
                @php
                    use Illuminate\Support\Str;
                @endphp

                @if ($services->isNotEmpty())
                    @php
                        $groupedServices = $services->groupBy('category_id');
                    @endphp
                    
                    @foreach($groupedServices as $categoryId => $categoryServices)
                        @php
                            $category = $categoryServices->first()->category;
                            $accordionId = 'cat' . $categoryId;
                        @endphp

                        <div class="accordion-item">
                            <div class="accordion-header" id="cat{{ $categoryId }}" >
                                <div class="accordion-button collapsed p-2" data-bs-toggle="collapse" data-bs-target="#catCollapse{{ $accordionId }}">
                                    <div class="category-card">
                                        <div class="icon-head">
                                            <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->category_name }}" class="thumb" >
                                            <h5>{{ $category?->category_name ?? 'No Category' }}
                                                - {{ $categoryServices->count() }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>                                
                            </div>

                        <div id="catCollapse{{ $accordionId }}" class="accordion-collapse collapse" data-bs-parent="#categoryAccordion">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-top-0" width="60">ID</th>
                                            <th class="border-top-0">All Services</th>
                                            <th class="border-top-0" width="180">Category</th>                            
                                            <th class="border-top-0 text-end" width="150">Price</th>
                                            <th class="border-top-0 text-end" width="100">Status</th>
                                            <th class="border-top-0 text-end" width="100">Action</th>
                                        </tr>
                                    </thead>  
                                    <tbody>
                                        @foreach($categoryServices as $service)
                                            @php
                                                $serviceImage = $service->service_images->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $service->id }}</td>
                                                <td>
                                                    <a href="{{ route('services.edit', $service->id) }}" class="h5">
                                                        {{ Str::limit($service->title, 100, '...') }}
                                                    </a>
                                                    <p class="text-muted tiny-font">
                                                        {{ Str::limit($service->short_description, 50, '...') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="tiny-font">
                                                        {{ $service->category?->category_name ?? 'No Category' }}
                                                    </p>
                                                    <p class="text-muted tiny-font">
                                                        {{ $service->subCategory?->sub_category_name ?? 'No Sub Category' }}
                                                    </p>
                                                </td>

                                                <td class="text-end">
                                                    <div class="price">
                                                        @if($service->discount_percent > 0)
                                                            ₹{{ round($service->discount_price) }}
                                                            <p class="text-muted tiny-font">
                                                                MRP
                                                                <del>₹{{ $service->price }}</del>
                                                                <br>
                                                                <span class="discount">
                                                                    {{ $service->discount_percent }}% OFF
                                                                </span>
                                                            </p>
                                                        @else
                                                            ₹{{ number_format($service->price, 2) }}
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                <td class="text-end">
                                                    @if ($service->status == 'approved')
                                                        <span class="sprites green-tick-icon"></span>
                                                    @else
                                                        <span class="sprites red-tick-icon"></span>
                                                    @endif
                                                </td>

                                                <td class="text-end">
                                                    <div class="pull-right">
                                                        <div class="flex">
                                                            <a href="{{ route('services.edit', $service->id) }}" class="edit-icon">
                                                                <span class="sprites"></span>
                                                            </a>

                                                            <a href="#" onclick="deleteService({{ $service->id }})" class="delete-icon">
                                                                <span class="sprites"></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach                    
                @else
                    <div class="text-center py-4">Records not found</div>
                @endif
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