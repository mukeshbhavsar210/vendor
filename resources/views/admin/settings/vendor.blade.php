@extends('admin.layouts.app')

@section('content')

@include('admin.message')

<div class="card mb-0">
    <div class="card-body pb-0">            
        <div class="row">                
            <div class="col-sm-7 col-12">
                <div class="page-title">
                    <h4>Vendors</h4>
                    <span class="counts">{{ $total  }}</span>
                </div>
            </div>
            <div class="col-sm-5 col-12 float-end">
                <div class="flexContainer">
                    <form action="" method="get" >
                        <div class="d-flex">
                            <div class="card-title">
                                <button type="button" onclick="window.location.href='{{ $refresh }}'" class="btn btn-default btn-sm">
                                    <?xml version="1.0" encoding="utf-8"?>
                                        <svg width="20px" height="20px" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                        <g fill="none" fill-rule="evenodd" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" transform="matrix(0 1 1 0 2.5 2.5)">
                                        <path d="m3.98652376 1.07807068c-2.38377179 1.38514556-3.98652376 3.96636605-3.98652376 6.92192932 0 4.418278 3.581722 8 8 8s8-3.581722 8-8-3.581722-8-8-8"/>
                                        <path d="m4 1v4h-4" transform="matrix(1 0 0 -1 0 6)"/>
                                        </g>
                                    </svg>
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

                    <a class="btn btn-primary" href="#" onclick="createVendorModal()" data-bs-toggle="modal" data-bs-target="#vendorModal" >Create Vendor</a>
                    {{-- <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $modal_id }}">{{ $button_name }}</button> --}}
                </div>
            </div>
        </div>                        
    </div>
</div>

<div class="accordion mt-1" id="categoryAccordion">
    @foreach ($vendors as $vendor)                                                     
        <div class="accordion-item">                                
            <div class="accordion-header" id="cat{{ $vendor->id }}">
                <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#catCollapse{{ $vendor->id }}">
                    <div class="category-card">
                        <div class="icon-head">
                            @php
                                $serviceIds = is_array($vendor->services)
                                    ? $vendor->services
                                    : (json_decode($vendor->services ?? '[]', true) ?? []);
                            @endphp
                        
                            <h5>{{ $vendor->business_name }}</h5>
                            <span class="counts">{{ count($serviceIds) }}</span>
                        </div>

                        <div class="flex-end">
                            @if ($vendor->status == 'approved')  
                                <span class="sprites green-tick-icon"></span>
                            @else
                                <span class="sprites red-tick-icon"></span>
                            @endif
                        </div>                        
                    </div>
                </div>
            </div>

            <div id="catCollapse{{ $vendor->id }}" class="accordion-collapse collapse" data-bs-parent="#categoryAccordion">
                <div class="accordion-body p-3">                    
                    <div class="flex">
                        <a href="{{ route('vendors.edit', $vendor->id) }}">
                            @if (!empty($vendor->image))
                                <img src="{{ asset('uploads/vendors/'.$vendor->logo) }}" height="80" class="me-3 align-self-center rounded">
                            @else
                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="80" class="me-3 align-self-center rounded">
                            @endif                                            
                        </a>
                        <div>                        
                            <h6 class="mb-0">{{ $vendor->user->name }}</h6>
                            <p class="mb-0">{{ $vendor->address }}</p>
                            <p class="mb-0 text-muted">Email: {{ $vendor->email }}</p>
                            <p class="mb-0 text-muted">Phone: {{ $vendor->phone }}, Mobile: {{ $vendor->mobile }}</p>

                            <div class="vendor-category">
                                @forelse($serviceIds as $serviceId)
                                    @if(isset($subCategories[$serviceId]))
                                        <div class="card">                                            
                                            <img src="{{ asset('uploads/subcategory/'.$subCategories[$serviceId]->image) }}" alt="{{ $subCategories[$serviceId]->sub_category_name }}" class="thumb" />                                            
                                            <p class="mb-0">
                                                {{ Str::limit($subCategories[$serviceId]->sub_category_name, 17) }}                                                
                                            </p>
                                        </div>
                                    @endif
                                @empty
                                    <span class="text-muted">No services selected</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex-end">
                            <a href="javascript:void"
                                data-id="{{ $vendor->id }}"
                                data-category-modal="{{ $vendor->category_modal }}"
                                data-business-name="{{ $vendor->business_name }}"                                
                                data-status="{{ $vendor->status }}"
                                onclick="editVendorModal(this)"
                                data-bs-toggle="modal" 
                                data-bs-target="#categoryModal"
                                class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                            <a href="#" onclick="deleteVendor({{ $vendor->id }})" class="btn btn-sm btn-outline-danger">Delete</a>

                            @if(auth()->user()?->role === 'admin')
                                <a href="#" class="btn btn-sm btn-outline-primary">Approve</a>
                            @endif
                        </div> 
                    </div>                                       
                </div>
            </div>
        </div>
    @endforeach                
</div>

    <div class="card-footer clearfix">
        {{ $vendors->links() }}
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
