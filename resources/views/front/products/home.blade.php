@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')
    <div class="container">
    <h2 class="home-title">Global Brands</h2>        
    <div class="brand-slider">
        @foreach(getBrands() as $brand)                
            <div class="brand-item">
                <x-products :item="$brand" section="show_brand" variable="brand" gallery="brand" :hover="false" :description="false" :amount="false" :title_limit="20" :short_limit="7" />                    
            </div>
        @endforeach
    </div>

    <h2 class="home-title mt-5">Shop By Category</h2>                
    <div class="row">
        @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
                @if ($category->subCategories->isNotEmpty())														                       
                    @foreach ($category->subCategories->whereNotNull('image')->where('image','!=','') as $subcategory)
                        @if ($subcategory->subSubCategories->isNotEmpty())
                            <div class="col-md-2 col-6">
                                <x-products 
                                    :item="$category"
                                    :category="$category"
                                    :subcategory="$subcategory"
                                    section="show_subcategory" gallery="category" class="home"
                                    :hover="false" 
                                    :producttitle="false" 
                                    :description="false" 
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