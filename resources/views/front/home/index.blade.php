@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')    

<div class="container">
    <div class="row mt-5">
        <div class="col-md-5 col-12">
            <h1>Home services at your<br /> doorstep</h1>
            <div class="card mt-3">
                <div class="card-body">                    
                    @if (getCategories()->isNotEmpty())
                        <div class="category-card">
                            @foreach (getCategories() as $category)                                

                                @if ($category->open_to == 'link')                                    
                                    <a href="{{ route('front.category', [$category->category_slug]) }}" class="repeate">
                                        <div class="thumb">
                                            <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->category_name }}">                                        
                                        </div>
                                        <p>{{ $category->category_name }}</p>
                                    </a>
                                @else
                                    <div class="repeate" data-bs-toggle="modal" data-bs-target="#modal_{{ $category->category_slug }}">
                                        <div class="thumb">                                        
                                            <img src="{{ asset('uploads/category/' . $category->image) }}" alt="{{ $category->category_name }}">                                        
                                        </div>
                                        <p>{{ $category->category_name }}</p>
                                    </div>
                                @endif                                
                                
                                <div class="modal fade" id="modal_{{ $category->category_slug }}" tabindex="-1" aria-labelledby="categoryLabel_{{ $category->category_modal }}" aria-hidden="true">
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
    
    @php
        $homeServiceSections = [
            [
                'title' => 'Most booked services',
                'text' => '',
                'modal' => '',
                'data'  => $most_booked,
                'banner'  => '',
            ],
            [
                'title' => 'New and noteworthy',
                'text' => '',
                'modal' => '',
                'data'  => $new_and_noteworthy,
                'banner'  => '',
            ],
            [
                'title' => 'Spa for women',
                'text' => '',
                'modal' => 'modal_womens-salon-spa',
                'data'  => $women_spa,
                'banner'  => 'home_banner2.jpeg',
            ],
            [
                'title' => 'Cleaning Essentials',
                'text' => 'Monthly cleaning essential services',
                'modal' => 'modal_cleaning',
                'data'  => $cleaning,
                'banner'  => '',
            ],
            [
                'title' => 'Appliance repair & service',
                'text' => '',
                'modal' => 'modal_ac-appliance-repair',
                'data'  => $appliances,
                'banner'  => '',
            ],
            [
                'title' => 'Home repair & installation',
                'text' => '',
                'modal' => 'modal_installation',
                'data'  => $installation,
                'banner'  => 'home_banner3.jpeg',
            ],
            [
                'title' => 'Salon for men',
                'text' => 'Grooming essentials',
                'modal' => 'modal_mens-salon-massage',
                'data'  => $men_spa,
                'banner'  => '',
            ],
        ];
    @endphp        

    @if ($homeServiceSections)                    
        @foreach($homeServiceSections as $section)
            <section class="cmn-home">
                <div class="title-group">
                    <div>
                        <h2>{{ $section['title'] }}</h2>
                        @if ($section['text'])
                            <p>{{ $section['text'] }}</p>    
                        @endif                        
                    </div>

                    @if ($section['modal'])
                        <a class="btn fix-height-btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $section['modal'] }}">
                            See all
                        </a>
                    @endif
                </div>

                <div class="services-gallery mb-3">
                    @foreach($section['data'] as $categoryId => $subCategories)
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

                @if ($section['banner'])
                    <img src="{{ asset('front-assets/images/'.$section['banner']) }}" alt="Business" class="mt-5">
                @endif
            </section>
        @endforeach
    @endif
</div>
@endsection