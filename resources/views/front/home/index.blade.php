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
                </div>
            </div>
        </div>
        <div class="col-md-7 col-12">
            <img src="{{ asset('front-assets/images/home_banner.jpeg') }}" alt="Urban Clap">
        </div>
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

        <section class="cmn-home">
            <h2>New and noteworthy</h2>        
            <div class="services-gallery">   
                @foreach(getSubCategories() as $service)                         
                    <x-services 
                        class="home-gallery"
                        :item="$service"
                        :data="$service"
                        gallery="homeSubCategory"
                        :hover="false"
                        :price="true"                        
                        :title_limit="25"
                        :short_limit="7"
                    />                    
                @endforeach               
            </div>
        </section>

        <section class="cmn-home">
            <div class="title-group">
                <h2>Most booked services</h2>
            </div>
            <div class="services-gallery">
                @foreach($most_booked as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach

                {{-- @foreach($services as $service)
                    <x-services 
                        :item="$service"
                        :data="$service"
                        :reviews="true"
                        :hover="false"
                        :price="true"
                        :title_limit="25"
                        :short_limit="7"
                        gallery="homeServices"
                        show="services_new"
                        class="home-gallery"
                    />
                @endforeach --}}
            </div> 
        </section>

        <section class="cmn-home">
            <div class="title-group">
                <h2>Span for women</h2>
            </div>
            <div class="services-gallery">
                @foreach($women_spa as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach
            </div> 
        </section>

        <section class="cmn-home">
            <div class="title-group">
                <h2>Cleaning Essentials</h2>
                <p>Monthly cleaning essential services</p>
            </div>

            <div class="services-gallery">
                @foreach($cleaning as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach
            </div> 
        </section>

        <section class="cmn-home">
            <div class="title-group">
                <h2>Appliance repair & service</h2>
            </div>
            <div class="services-gallery">
                @foreach($appliances as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach                
            </div> 
        </section>

        <section class="cmn-home">
            <div class="title-group">
                <h2>Home repair & installation</h2>
            </div>
            <div class="services-gallery">
                @foreach($installation as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach                
            </div> 
        </section>

        <section class="cmn-home">
            <div class="title-group">
                
                <p>Grooming essentials</p>
            </div>
            <div class="services-gallery">
                @foreach($men_spa as $categoryId => $subCategories)
                    @foreach($subCategories as $subCategory)                        
                        <x-services
                            :item="$subCategory"
                            :data="$subCategory"
                            :category="$subCategory->category"
                            :subcategory="$subCategory"
                            :ratings="$subCategory->services->flatMap->ratings"
                            show="services_new"
                            gallery="homeServices"
                            class="home-gallery"
                            :reviews="true"
                            :hover="false"
                            :price="true"
                        />
                    @endforeach
                @endforeach
            </div> 
        </section>
    </div>      
@endsection