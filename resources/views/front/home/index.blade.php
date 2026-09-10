@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')    

<div class="container">
    <div class="row">
        <div class="col-md-5 col-12">
            <h1>Home services at your doorstep</h1>
            <div class="card mt-3">
                <div class="card-body">                    
                    @if (getCategories()->isNotEmpty())
                        <div class="category-card">
                            @foreach (getCategories() as $category)
                                <div class="repeate" data-bs-toggle="modal" data-bs-target="#category_{{ $category->category_modal }}">
                                    <div class="thumb">
                                        @if ($category->image)
                                            <img src="{{ asset('uploads/category/' . $category->image) }}"
                                                alt="{{ $category->category_name }}">
                                        @endif
                                    </div>
                                    <p>{{ $category->category_name }}</p>
                                </div>
                                
                                <div class="modal fade" id="category_{{ $category->category_modal }}" tabindex="-1" aria-labelledby="categoryLabel_{{ $category->category_modal }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-custom">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                            <div class="modal-header">
                                                <h2 id="categoryModalLabel{{ $category->category_modal }}">{{ $category->category_name }}</h2>
                                            </div>

                                            <div class="modal-body">                                                
                                                @php
                                                    $modalCategories = getModalCategories()->where('menu_order', $category->menu_order)->groupBy('category_modal');
                                                @endphp

                                                @foreach ($modalCategories as $modalType => $categories)
                                                    @if ($modalType)
                                                        <h5 class="mb-2">{{ ucfirst($modalType) }}</h5>
                                                    @endif

                                                    <div class="modal-card">
                                                        @foreach ($categories as $category)
                                                            <div class="repeate">
                                                                @if ($category->image)
                                                                    <div class="thumb">
                                                                        <a href="{{ route('front.category', [$category->category_slug]) }}">
                                                                            <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->category_name }}">
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                <p>{{ $category->category_name }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                        {{-- @if (getCategories()->isNotEmpty())                            
                                @foreach (getCategories() as $category)
                                <div class="repeate-card">
                                    @php
                                        $subCategory = $category->subCategories->first();
                                    @endphp

                                    @if ($subCategory)                                        
                                        @if ($subCategory->image)
                                            <img src="{{ asset('uploads/subcategory/' . $subCategory->image) }}" >
                                        @endif
                                        <p>{{ $subCategory->sub_category_name }}</p>                                        
                                    @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif                         --}}                  
                </div>
            </div>
        </div>
        <div class="col-md-7 col-12">2</div>
    </div>

        @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
                @if ($category->subCategories->isNotEmpty())														                       
                    @foreach ($category->subCategories->whereNotNull('image')->where('image','!=','') as $subcategory)
                        @if ($subcategory->subSubCategories->isNotEmpty())
                            <div class="col-md-2 col-6">
                                <x-services 
                                    :item="$category"
                                    :category="$category"
                                    :subcategory="$subcategory"
                                    section="show_subcategory"
                                    :amount="false" 
                                    :title_limit="20" 
                                    :short_limit="7" 
                                />
                            </div>                                  
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif           
    </div> 
    </div>      
@endsection