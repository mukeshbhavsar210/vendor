@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.common')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-sm-7 col-12">
                <div class="page-title">
                    <h4>{{ $title }}</h4>
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
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $modal_id }}">{{ $button_name }}</button>
                </div>
            </div>
        </div> 

            <div class="table-responsive mt-2">
                @php
                    use Illuminate\Support\Str;
                @endphp

                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0">Brand</th>
                            <th class="border-top-0">Description</th>
                            <th class="border-top-0" width="10%">Action</th>
                        </tr>
                    </thead>                     
                    <tbody>
                        @if ($brands->isNotEmpty())
                            @foreach ($brands as $key => $brand)                                                                   
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                @if (!empty($brand->model))
                                                    <img src="{{ asset('uploads/brands/'.$brand->model) }}" height="85" class="me-3 align-self-center rounded" >
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="120" class="me-3 align-self-center rounded" />
                                                @endif
                                            </div>
                                            
                                            <div>
                                                @if (!empty($brand->logo))
                                                    <img src="{{ asset('uploads/brands/'.$brand->logo) }}" height="30" class="me-3 align-self-center rounded" >
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="25" class="me-3 align-self-center rounded" />
                                                @endif  
                                                <h5 class="mb-0">{{ $brand->name }}</h5>
                                                <div class="small-fonts">                                                    
                                                    <span class="text-muted">{{ $brand->id }}</span>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </td>                                      
                                    <td>
                                        <h5 class="mb-1">{{ $brand->description }}</h5>
                                        <p class="text-muted">{{ $brand->discount }}</p>
                                    </td>
                                    <td>   
                                        <div class="flex">
                                            @if ($brand->status == 1)  
                                                <span class="sprites green-tick-icon"></span>
                                            @else
                                                <span class="sprites red-tick-icon"></span>
                                            @endif                                      
                                            <a href="#" onclick="deleteBrand({{ $brand->id }})" class="delete-icon">
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

            <div class="card-footer clearfix">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>        
    
</script>
@endsection