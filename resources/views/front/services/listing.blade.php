@extends('front.layouts.app')

@section('title', 'Products')

{{-- @section('title', $product->title . ' - ' .$product->short_description . ' | Category: ' . $product->category->category_name . ' | '  . config('app.name'))
@section('meta_description', Str::limit($product->short_description, 155))
@section('meta_keywords', $product->title) --}}

@section('content')
    
<div class="container-fluid">
    <div class="d-none d-md-block">
        <div class="light-font">
            <ol class="breadcrumb primary-color">
                <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $selected_item2->sub_category_name ?? '' }} / {{ $selected_item3->sub_sub_category_name ?? '' }}</li>                    
            </ol>
        </div>
        <p class="tiny-font">                                                
            {{ $selected_item1->category_name ?? '' }} / {{ $selected_item2->sub_category_name ?? '' }} / {{ $selected_item3->sub_sub_category_name ?? '' }}
            <span class="text-muted">- {{ $products->total() }} items</span>
        </p> 
    
        <div class="row mt-3 col-12">
            <div class="col-md-2 ">            
                <div class="flex-end">
                    <h5>FILTERS</h5>
                    @if($filtersApplied)                    
                        <a href="{{ url()->current() }}" class="btn btn-outline-dark btn-sm">Clear All</a>                    
                    @endif
                </div>
            </div>

            <div class="col-md-10 col-12">
                <div class="row">
                    <div class="col-md-10 col-12">
                        <div class="custom-dropdown">
                            <a href="javascript:0" class="dropdown">
                                Size
                                <span class="rotate">
                                    <span class="sprites down-arrow-icon"></span>                                    
                                </span>
                            </a>
                            <div class="dropdown-menu-select">
                                <x-filters :items="$sizes" type="size" valueField="name" labelField="code" title="" :showColor="false" :showPercent="false" :sizeFilter="true" :mobileView="false" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-12">                        
                            <div class="custom-dropdown">
                                <a href="javascript:0" class="dropdown">
                                    Sort
                                    <span class="rotate">
                                        <span class="sprites down-arrow-icon"></span>                                    
                                    </span>
                                </a>
                                <div class="dropdown-menu-select sort-filter">
                                    <ul class="sort-options">   
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'recommended']) }}" class="sort-item {{ request('sort') == 'recommended' ? 'active' : '' }}">Recommended</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'popularity']) }}" class="sort-item {{ request('sort') == 'popularity' ? 'active' : '' }}">Popularity</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="sort-item {{ request('sort') == 'latest' ? 'active' : '' }}">What's New</a></li>        
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'discount']) }}" class="sort-item {{ request('sort') == 'discount' ? 'active' : '' }}">Better Discount</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="sort-item {{ request('sort') == 'price_desc' ? 'active' : '' }}">Price: High to Low</a></li>                                        
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="sort-item {{ request('sort') == 'price_asc' ? 'active' : '' }}">Price: Low to High</a></li>                                        
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" class="sort-item {{ request('sort') == 'rating' ? 'active' : '' }}">Customer Rating</a></li>
                                    </ul>
                                </div>
                            </div>                            
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row border-product">
        <div class="col-md-2 col-12 sticky right-border d-none d-md-block">
            <x-filters :items="$item3" type="category" valueField="sub_sub_category_slug" labelField="sub_sub_category_name" nameClass="name" title="Categories" :showColor="false" :showPercent="false" :limit="17" :selected="$categoryArray" :sizeFilter="false" :mobileView="false"  />
            <x-filters :items="$brands" type="brand" valueField="slug" labelField="name" nameClass="name" title="Brands" :showColor="false" :showPercent="false" :limit="17" :sizeFilter="false"  :mobileView="false" />

            <div class="filter-group">
                <h5 class="h5 mb-2">Price</h5>
                <input type="text" class="js-range-slider-desktop" name="my_range" value="" />
            </div>

            <x-filters :items="$colors" type="color" valueField="name" labelField="name" title="Color" :showColor="true" :showPercent="false" :sizeFilter="false" :mobileView="false"  />
            <x-filters :items="$discounts" type="discount" valueField="percentage" labelField="percentage" title="Discount Range" :showColor="false" :showPercent="true" :sizeFilter="false" :mobileView="false" />
        </div>

        <div class="col-md-10 col-12">
            <div class="listing-products">
                <div class="row">
                    @foreach($products as $product) 
                        <div class="col-md-3 col-6">
                            <x-products :item="$product" :wishlistProductIds="$wishlistProductIds" section="show_products" gallery="yes" variable="product" class="product" :producttitle="true" :hover="true" :description="true" :amount="true" :title_limit="27" :short_limit="30" />
                        </div>
                    @endforeach
                </div>
                <div class="col-md-12 pt-5">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-block d-md-none">
    <div class="mobile-filter-control">
        <a href="javascript:0" type="button" class="btn-custom filter-btn-1" data-bs-toggle="modal" data-bs-target="#mobile-filter2-Modal">
            <div class="flex-mobile">
                <span class="sprites"></span>
                <span class="label">Sort</span>
            </div>        
        </a>
        <a href="javascript:0" type="button" class="btn-custom filter-btn-2" data-bs-toggle="modal" data-bs-target="#mobile-filter1-Modal">
            <div class="flex-mobile">
                <span class="sprites"></span>
                <span class="label">Filter</span>
            </div>
        </a>
    </div>
</div>

<div class="modal fade bottom-modal1" id="mobile-filter1-Modal" tabindex="-1" aria-labelledby="mobile-filter1-ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mobile-filter1-ModalLabel">Filters</h5>
        <div>
            @if($filtersApplied)                    
                <a href="{{ url()->current() }}" class="btn btn-outline-dark btn-sm">Clear All</a>                    
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body">
        <div class="mobile-filter-tabs">
            <div class="left">
                <div class="nav flex-column nav-pills" id="v-tab" role="tablist">
                    <a href="javascript:0" class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab1">
                        Categories
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab2">
                        Size
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab3">
                        Price
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab4">
                        Brand
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab5">
                        Color
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab6">
                        Discount
                    </a>
                    <a href="javascript:0" class="nav-link" data-bs-toggle="pill" data-bs-target="#tab7">
                        More filters
                    </a>
                </div>
            </div>

            <div class="right">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab1">
                        <x-filters :items="$item3" type="category" valueField="sub_sub_category_slug" labelField="sub_sub_category_name" nameClass="name" title="" :showColor="false" :showPercent="false" :limit="17" :selected="$categoryArray" :sizeFilter="false" :mobileView="true" />
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <x-filters :items="$sizes" type="size" valueField="name" labelField="code" title="Size" :showColor="false" :showPercent="false" :sizeFilter="true" :mobileView="true" />
                    </div>
                    <div class="tab-pane fade" id="tab3">                        
                        <div class="filter-group">
                            <p class="text-muted">Selected Price range</p>
                            <h6 class="mb-4"><span id="selectedPriceRange"></span></h6>
                            <input type="text" class="js-range-slider-mobile" name="my_range" value="" />
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab4">
                        <x-filters :items="$brands" type="brand" valueField="slug" labelField="name" nameClass="name" title="Brands" :showColor="false" :showPercent="false" :limit="17" :sizeFilter="false" :mobileView="true" />
                    </div>
                    <div class="tab-pane fade" id="tab5">
                        <x-filters :items="$colors" type="color" valueField="name" labelField="name" title="Color" :showColor="true" :showPercent="false" :sizeFilter="false" :mobileView="true" />
                    </div>
                    <div class="tab-pane fade" id="tab6">
                        <x-filters :items="$discounts" type="discount" valueField="percentage" labelField="percentage" title="Discount" :showColor="false" :showPercent="true" :sizeFilter="false" :mobileView="true" />
                    </div>
                    <div class="tab-pane fade" id="tab7">
                        More filters
                    </div>
                </div>
            </div>
        </div>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade bottom-modal2" id="mobile-filter2-Modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">      
      <div class="modal-header">
        <h5 class="modal-title">Sort By</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <ul class="sort-options">            
            <li><a href="?sort=popularity" class="sort-item {{ request('sort') == 'popularity' ? 'active' : '' }}">Popularity</a></li>
            <li><a href="?sort=latest" class="sort-item {{ request('sort') == 'latest' ? 'active' : '' }}">What's New</a></li>        
            <li><a href="?sort=discount" class="sort-item {{ request('sort') == 'discount' ? 'active' : '' }}">Better Discount</a></li>
            <li><a href="?sort=price_desc" class="sort-item {{ request('sort') == 'price_desc' ? 'active' : '' }}">Price: High to Low</a></li>
            <li><a href="?sort=price_asc" class="sort-item {{ request('sort') == 'price_asc' ? 'active' : '' }}">Price: Low to High</a></li>
            <li><a href="?sort=rating" class="sort-item {{ request('sort') == 'rating' ? 'active' : '' }}">Customer Rating</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

@section('customJs')
    <script>
        $(document).ready(function () {                
            $('.dropdown').click(function (e) {
                e.stopPropagation();
                $(this).find('i').toggleClass('rotate');
                $('.custom-dropdown-select').removeClass('active'); // close others
                $(this).parent().toggleClass('active');
            });

            // Click outside to close
            $(document).click(function () {
                $('.custom-dropdown-select').removeClass('active');
            });

            $('.form-check-input:checked').each(function () {
                $(this).parent().addClass('active');                
            });            
        });

        $(document).on('change', '.form-check-input', function () {            
            if ($(this).is(':checked')) {
                $(this).parent().addClass('active');                
            } else {
                $(this).parent().removeClass('active');                
            }
        });

        $('.form-check-input').on('change', function () {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let type = $(this).data('type');
            let value = $(this).val();
            let existing = params.get(type);
            let values = existing ? existing.split(',') : [];

            if (this.checked) {
                if (!values.includes(value)) {
                    values.push(value);
                }
            } else {
                values = values.filter(v => v !== value);
            }

            if (values.length > 0) {
                params.set(type, values.join(','));
            } else {
                params.delete(type);
            }
            window.location.href = url.toString();
        });

        $(".category-label").on('change', apply_category_filters);
        $(".brand-label").on('change', apply_brand_filters);
        $(".size-label").on('change', apply_size_filters);
        $(".color-label").on('change', apply_color_filters);        
        $(".discount-label").on('change', apply_discount_filters);
        $("#sortFilter").on('change', apply_sort_filters);      

        function apply_category_filters(){
            var category = [];
            $(".category-label").each(function(){
                if ($(this).is(":checked") == true){
                    category.push($(this).val());
                }
            });           
            var url = '{{ url()->current() }}?';
            if (category.length > 0) {
                url += '&category='+category.toString();
            }            
            window.location.href = url;
        }

        function apply_brand_filters(){
            var brands = [];
            $(".brand-label").each(function(){
                if ($(this).is(":checked") == true){
                    brands.push($(this).val());
                }
            });           
            var url = '{{ url()->current() }}?';
            if (brands.length > 0) {
                url += '&brand='+brands.toString();
            }            
            window.location.href = url;
        }

        function apply_color_filters(){
            var colors = [];

            $(".color-label:checked").each(function(){
                colors.push($(this).val());
            });

            var params = new URLSearchParams(window.location.search);

            if (colors.length > 0) {
                params.set('color', colors.join(','));
            } else {
                params.delete('color');
            }

            window.location.href = '{{ url()->current() }}?' + params.toString();
        }

        function apply_discount_filters(){
            var discount = [];
            $(".discount-label:checked").each(function(){
                discount.push($(this).val());
            });

            var params = new URLSearchParams(window.location.search);

            if (discount.length > 0) {
                params.set('discount', discount.join(','));
            } else {
                params.delete('discount');
            }
            window.location.href = '{{ url()->current() }}?' + params.toString();
        }
        
        function apply_size_filters(){
            var sizes = [];

            $(".size-label:checked").each(function(){
                sizes.push($(this).val());
            });

            var params = new URLSearchParams(window.location.search);

            if (sizes.length > 0) {
                params.set('size', sizes.join(','));
            } else {
                params.delete('size');
            }

            window.location.href = '{{ url()->current() }}?' + params.toString();
        }

        function apply_sort_filters() {
            var sort = $("#sortFilter").val();
            var params = new URLSearchParams();

            if (sort !== '') {
                params.set('sort', sort);
            }
            window.location.href = '{{ url()->current() }}?' + params.toString();
        }

        $(document).ready(function () {
            initPriceSlider(
                ".js-range-slider-desktop",
                {{ $priceMin }},
                {{ $priceMax }}
            );

            initPriceSlider(
                ".js-range-slider-mobile",
                {{ $priceMin }},
                {{ $priceMax }},
                true
            );
        });

        function initPriceSlider(selector, minVal, maxVal, showLabel = false) {
            $(selector).ionRangeSlider({
                type: "double",
                min: 0,
                max: 10000,
                from: minVal,
                to: maxVal,
                step: 10,
                skin: "round",
                prefix: "₹",

                onStart: function (data) {
                    if (showLabel) updatePriceDisplay(data.from, data.to);
                },

                onChange: function (data) {
                    if (showLabel) updatePriceDisplay(data.from, data.to);
                },

                onFinish: function (data) {
                    applyPriceFilter(data.from, data.to);
                }
            });
        }

        function updatePriceDisplay(min, max) {
            $("#selectedPriceRange").text("₹" + min + " - ₹" + max);
        }

        function applyPriceFilter(min, max) {
            let params = new URLSearchParams(window.location.search);

            params.set('price_min', min);
            params.set('price_max', max);

            window.location.href = window.location.pathname + '?' + params.toString();
        }

        // function apply_search_filters(){            
        //     var keyword = $('#search').val();
        //     if(keyword.length > 0){
        //         url += '&search='+keyword;
        //     }            
        //     window.location.href = url;
        // }

    </script>
@endsection