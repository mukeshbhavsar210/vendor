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
                            <span class="counts">{{ $total }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 ">
                        <div class="flexContainer float-end">
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

                            <a class="btn btn-primary" href="#" onclick="createService()" data-bs-toggle="modal" data-bs-target="#serviceModal" >Create</a>                            
                        </div>
                    </div>
                </div>                        
            </div>

            @php
                use Illuminate\Support\Str; 
                $user = auth('admin')->user();
            @endphp

            @if ($user?->role === 'admin')        
                <div class="accordion" id="categoryAccordion">
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
                                    <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#catCollapse{{ $accordionId }}">
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

                            <div id="catCollapse{{ $accordionId }}" class="accordion-collapse collapse p-3" data-bs-parent="#categoryAccordion">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-top-0" width="30">Image</th>
                                                <th class="border-top-0">Services</th>
                                                <th class="border-top-0" width="250">Service Images</th>
                                                <th class="border-top-0" width="180">Vendor</th>                                                
                                                <th class="border-top-0" width="80">Action</th>
                                            </tr>
                                        </thead>  
                                        <tbody>
                                            @foreach($categoryServices as $service)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('services.edit', $service->id) }}" class="tick-overlap">
                                                            <div class="tick">
                                                                @if ($service->status == 'approved')
                                                                    <span class="sprites green-tick-icon"></span>
                                                                @else
                                                                    <span class="sprites red-tick-icon"></span>
                                                                @endif
                                                            </div>
                                                            @if (isset($service->subCategory->image))
                                                                <img class="me-3 align-self-center rounded" height="80" src="{{ asset('uploads/subcategory/' . $service->subCategory->image) }}" alt="{{ $service->subCategory->sub_category_name }}" />
                                                            @endif
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h5 class="m-0">{{ Str::limit($service->subCategory?->sub_category_name, 100, '...') }}</h5>                                                               
                                                                
                                                                <p class="text-muted tiny-font">
                                                                    {{ Str::limit($service->short_description, 60, '...') }}
                                                                </p>
                                                                <div class="price">
                                                                    @php
                                                                        $price = $service->subCategory?->price ?? $service->price;
                                                                        $discount = $service->subCategory?->discounts?->first();
                                                                        $discountPercent = $discount?->discountPercentage?->percentage ?? 0;
                                                                        $discountPrice = $price - ($price * $discountPercent / 100);
                                                                    @endphp

                                                                    @if($discountPercent > 0)
                                                                        <p>
                                                                            ₹{{ round($discountPrice) }}
                                                                            <del class="text-muted tiny-font">₹{{ number_format($price, 2) }}</del>                                                                            
                                                                            <span class="discount text-muted tiny-font">{{ $discountPercent }}% OFF</span>
                                                                        </p>
                                                                    @else
                                                                        ₹{{ number_format($price, 2) }}
                                                                    @endif
                                                                </div>
                                                                <a href="{{ route('services.edit', $service->id) }}" class="fs-12 text-primary">ID: {{ $service->id }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="img-group d-flex">
                                                            @foreach($service->serviceImages as $serviceImage)
                                                                <a class="user-avatar position-relative d-inline-block" href="#">
                                                                    <img src="{{ asset('uploads/services/small/' . $serviceImage->image) }}"
                                                                        alt="{{ $service->title }}" class="thumb-md shadow-sm rounded-circle" />
                                                                </a>
                                                            @endforeach                                                                                                                            
                                                        </div>
                                                    </td>                                                    
                                                    <td>
                                                        <div class="flex-grow-1 text-truncate"> 
                                                            <h6 class="m-0">{{ $service->vendor->user->name }}</h6>
                                                            <p class="text-muted tiny-font">
                                                                {{ Str::limit($service->vendor->business_name, 80, '...') }}                                                            
                                                            </p>                                                            
                                                        </div>
                                                    </td>                                                                                                        
                                                    <td class="text-end">
                                                        <div class="flex">
                                                            <a href="javascript:void"
                                                                data-id="{{ $service->id }}"
                                                                data-category="{{ $service->category_id }}"
                                                                data-sub-category="{{ $service->sub_category_id }}"
                                                                data-short-description="{{ $service->short_description }}"
                                                                data-description="{{ $service->description }}"
                                                                data-featured="{{ $service->is_featured }}"
                                                                data-status="{{ $service->status }}"
                                                                onclick="editService(this)"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#serviceModal">
                                                                <i class="las la-pen text-secondary fs-18"></i>
                                                            </a>
                                                            
                                                            <a href="#" onclick="deleteService({{ $service->id }})">
                                                                <i class="las la-trash-alt text-secondary fs-18"></i>
                                                            </a>
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

            @elseif ($user?->role === 'vendor')
                @if ($services->isNotEmpty())
                    <div class="row mt-3">
                        @foreach($services as $service)
                            @php
                                $serviceImage = $service->service_images->first();
                            @endphp

                            <div class="col-md-3">
                                <div class="card">                                    
                                    <div class="service-card">
                                        <div class="inside">                                            
                                            <div class="left">
                                                <a href="{{ route('services.edit', $service->id) }}" class="edit-icon">
                                                    <span class="sprites"></span>
                                                </a>

                                                <a href="#" onclick="deleteService({{ $service->id }})" class="delete-icon">
                                                    <span class="sprites"></span>
                                                </a>                                                
                                            </div>

                                            @if ($service->status == 'approved')
                                                <span class="badge bg-primary-subtle text-primary">Approved</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-danger">Pending</span>
                                            @endif
                                        </div>
                                        
                                        <img class="card-img-top img-fluid bg-light-alt" src="{{ asset('uploads/subcategory/' . $service->subCategory->image) }}" alt="{{ $service->subCategory->sub_category_name }}">
                                    </div>

                                    <div class="card-header">
                                        <h4 class="card-title">{{ Str::limit($service->title, 70, '...') }}</h4>
                                        <p class="text-muted">({{ $service->category?->category_name ?? 'No Category' }})</p>
                                        <p class="card-text text-muted">{{ Str::limit($service->short_description, 68, '...') }}</p>

                                        <p class="price">
                                            @if($service->discount_percent > 0)
                                                ₹{{ round($service->discount_price) }}
                                                <p class="text-muted tiny-font">
                                                    MRP
                                                    <del>₹{{ $service->subCategory->price }}</del>
                                                    <br>
                                                    <span class="discount">
                                                        {{ $service->discount_percent }}% OFF
                                                    </span>
                                                </p>
                                            @else
                                                ₹{{ number_format($service->price, 2) }}
                                            @endif                                                
                                        </p>
                                        
                                    </div>                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">Records not found</div>
                @endif
            @endif                            
        </div>
    </div>    

    @foreach($modals as $key => $modal)
        @include('admin.layouts.common', 
            [
                'modal_id' => $modal['modal_id'],
                'form_id' => $modal['form_id'],
                'method_id' => $modal['method_id'],
                'formConfig' => $modal['formConfig'],
                'title' => $modal['title'] ?? 'Modal'
            ])
    @endforeach
    
@endsection

@section('customJs')
<script>
    
</script>
@endsection