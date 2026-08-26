<nav aria-label="breadcrumb" class="d-none d-md-block">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('front.home') }}">Home</a>
        </li>           
        
        <li class="breadcrumb-item">
            <a href="{{ route('front.shop', [
                $product->subSubCategory->subCategory->category->category_slug,
                $product->subSubCategory->subCategory->sub_category_slug
            ]) }}">
                {{ $product->subSubCategory->subCategory->sub_category_name }}
            </a>
        </li>
        
        <li class="breadcrumb-item">
            <a href="{{ route('front.shop', [
                $product->subSubCategory->subCategory->category->category_slug,
                $product->subSubCategory->subCategory->sub_category_slug,
                $product->subSubCategory->sub_sub_category_slug
            ]) }}">
                {{ $product->subSubCategory->sub_sub_category_name }}
            </a>
        </li>
        
        <li class="breadcrumb-item active">{{ $product->title }}</li>
    </ol>
</nav>