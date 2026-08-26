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

@include('admin.layouts.common')
    
<div class="card">
    <div class="card-body pt-1">
        <div class="table-responsive">          
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-top-0" width="40">ID</th>
                            <th class="border-top-0">Contents</th>                            
                            <th class="border-top-0" width="100">Menu Order</th>
                            <th class="border-top-0" width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($pages->isNotEmpty())
                            @foreach ($pages as $page)
                                <tr>
                                    <td>{{ $page->id }}</td>
                                    <td>
                                        <h5 class="mb-1">{{ $page->name }}</h5>
                                        {!! Str::limit($page->content, 110, '...') !!}
                                    </td>                                    
                                    <td>{{ $page->menu_order }}</td>
                                    <td>
                                        <div class="flex">
                                            <a href="{{ route('pages.edit', $page->id ) }}"  class="edit-icon">
                                                <span class="sprites"></span>
                                            </a>
                                            <a href="javascript:0" onclick="deletePage({{ $page->id }})" class="delete-icon" >
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
                {{ $pages->links() }}
            </div>
        </div>
    </div>
@endsection