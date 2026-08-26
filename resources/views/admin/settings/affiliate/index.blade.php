@extends('admin.layouts.app')

@section('content')

@include('admin.message')

<div class="card mb-0">
    <div class="card-body pb-0">            
        <div class="row">                
            <div class="col-sm-7 col-12">
                <div class="page-title">
                    <h4>{{ $title }}</h4>
                    <span class="counts">{{ $total }}</span>
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
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $modal_id }}">{{ $button_name }}</button>                            
                </div>
            </div>
        </div>                        
    </div>
</div>

<div class="card custom-card">
    @include('admin.layouts.common')

    <div class="card-body">
        <div class="table-responsive">               
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Product Name/Platform</th>       
                        <th class="border-top-0 text-end" width="120">Price</th>
                        <th class="border-top-0 text-end" width="120">Percentages</th>
                        <th class="border-top-0 text-end" width="80">Views</th>
                        <th class="border-top-0 text-end" width="80">Likes</th>
                        <th class="border-top-0 text-end" width="80">Status</th>
                        <th class="border-top-0 text-end" width="80">Action</th>
                    </tr>
                </thead>  
                <tbody>
                    @if ($affiliates->isNotEmpty())
                        @foreach ($affiliates as $value)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="http://{{ $value->affiliate_url }}" target="_blank">
                                            @if (!empty($value->image))
                                                <img src="{{ asset('uploads/affiliate_products/'.$value->image) }}" height="90" class="me-3 align-self-center rounded">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="90" class="me-3 align-self-center rounded">
                                            @endif                                            
                                        </a>

                                        <div>
                                            <a href="{{ route('affiliate_services.edit', $value->id) }}">{{ Str::limit($value->title, 60, '...') }}</a><br />
                                            <p class="text-muted">{{ $value->affiliate_platform }}</p>
                                            <p>
                                                @if ($value->in_stock > 0)
                                                    <span class="text-muted">Stock:</span>
                                                    <span class="text-success">{{ $value->in_stock == 1 ? 'Yes' : 'No' }}</span>
                                                @else
                                                    <span class="text-danger">Out of Stock</span>
                                                @endif
                                            </p>                                            
                                        </div>
                                    </div>
                                </td>
                                @php
                                    $discount = $value->discounted_percentage ?? 0;
                                    $price = $value->price ?? 0;
                                    $finalPrice = $price - ($price * $discount / 100);
                                @endphp                                
                                <td class="text-end">
                                    ₹{{ round($finalPrice) }}<br />
                                    <span class="text-muted text-decoration-line-through">
                                        ₹{{ round($price) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="text-success">
                                        {{ $discount }}%
                                    </span>
                                </td>                                
                                <td class="text-end">{{ $value->views }}</td>
                                <td class="text-end">{{ $value->likes }}</td>
                                <td class="text-end float-end mt-4">                    
                                    @if ($value->status == 1)  
                                        <span class="sprites green-tick-icon"></span>
                                    @else
                                        <span class="sprites red-tick-icon"></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex">
                                        <a href="{{ route('affiliate_services.edit', $value->id ) }}" class="edit-icon">
                                            <span class="sprites"></span>
                                        </a>
                                        <a href="javascript:void(0);" data-id="{{ $value->id }}" class="delete-icon delete-product">
                                            <span class="sprites"></span>
                                        </a>
                                    </div>
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
    </div>
</div>   

<div class="card-footer clearfix">
    {{ $affiliates->links() }}
</div>

@endsection