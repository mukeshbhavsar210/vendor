@extends('admin.layouts.app')

@section('content')

<div class="card mb-0">
    <div class="card-body pb-0">            
        <div class="row">                
            <div class="col-sm-3 col-12">
                <div class="page-title">
                    <h4>Category</h4>
                    <span class="counts">{{ $total  }}</span>
                </div>
            </div>
            <div class="col-sm-9 col-12 float-end">
                <div class="flexContainer">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuBtn">
                            + ADD
                        </button>

                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li><a class="dropdown-item open-modal" href="#" onclick="createCategoryModal()" data-bs-toggle="modal" data-bs-target="#categoryModal" >Create Category</a></li>
                            <li><a class="dropdown-item open-modal" href="#" onclick="createSubCategoryModal()" data-bs-toggle="modal" data-bs-target="#subCategoryModal">Create Sub Category</a></li>                            
                        </ul>
                    </div>                     

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
                </div>
            </div>
        </div>                        
    
        <div class="accordion mt-1" id="categoryAccordion">
            @foreach ($categories as $category)                                                     
                <div class="accordion-item">                                
                    <div class="accordion-header" id="cat{{ $category->id }}">
                        <div class="accordion-button collapsed p-2" data-bs-toggle="collapse" data-bs-target="#catCollapse{{ $category->id }}">
                            <div class="category-card">                                
                                <div class="flex-grow-1">
                                    <h5>{{ $category->category_name }}
                                        @if ($category->sub_categories_count > 0)
                                            - {{ $category->sub_categories_count }}    
                                        @endif                                        
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="catCollapse{{ $category->id }}" class="accordion-collapse collapse" data-bs-parent="#categoryAccordion">
                        <div class="accordion-body">
                            <div class="category-card-flex">                                
                                @foreach ($category->subCategories as $sub)                                    
                                    <div class="sub-category-card">
                                        <div class="hover-card">
                                            <div class="overlay-new">
                                                <a href="#" class="delete-icon"  onclick="deleteSubCategory({{ $sub->id }})">
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>
                                            <img src="{{ asset('uploads/subcategory/' . $sub->image) }}" alt="{{ $sub->name }}" class="thumb" >
                                        </div>
                                        <p class="mb-0">{{ Str::limit($sub->sub_category_name, 22) }}</p>
                                    </div>                                    
                                @endforeach                                
                            </div>

                            <div class="flex">
                                <a href="javascript:0" 
                                    class="btn btn-outline-primary btn-sm" 
                                    data-id="{{ $category->id }}"
                                    data-category-modal="{{ $category->category_modal }}"
                                    data-category-name="{{ $category->category_name }}"
                                    data-show-home="{{ $category->showHome }}"
                                    data-menu-order="{{ $category->menu_order }}"
                                    data-status="{{ $category->status }}"
                                    onclick="editCategoryModal(this)"                                                
                                    data-bs-toggle="modal" 
                                    data-bs-target="#categoryModal" >
                                    Edit
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="deleteCategory({{ $category->id }})" >
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>                                                    
            @endforeach                
        </div>
    </div>                                                                                           
                    
    {{ $categories->links() }}    
</div>   

@foreach($modals as $key => $modal)
    @include('admin.layouts.common', [
        'modal_id' => $modal['modal_id'],
        'form_id' => $modal['form_id'],
        'method_id' => $modal['method_id'],        
        'formConfig' => $modal['formConfig'],
        'title' => $modal['title'] ?? 'Modal'
    ])
@endforeach

@endsection