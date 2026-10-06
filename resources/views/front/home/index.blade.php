@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')    

<div class="container">
    <div class="row mt-5">
        <div class="col-md-5 col-12">
            <h1>Home services at your<br /> doorstep</h1>
            <div class="card mt-3">
                <div class="card-body">
                    @if ($getCategories->isNotEmpty())
                        <div class="category-card">
                            @foreach ($getCategories as $category)
                                @php
                                    $openTo = $category->open_to;
                                    $modalType = $category->category_modal;
                                    $categories = $modalCategories->get($modalType, collect());
                                @endphp 

                                <x-services
                                    :item="$category"
                                    :data="$category"
                                    :category="$category"
                                    :allServices="$allServices"
                                    :categories="$modalCategories->get($modalType, collect())"
                                    :modalType="$modalType"
                                    :openTo="$openTo"
                                    show="thumb-services"
                                    class=""
                                    :reviews="false"
                                    :price="false"
                                />                                                                                            
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
                                show="all-services"                                
                                class="home-gallery"
                                :reviews="true"
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