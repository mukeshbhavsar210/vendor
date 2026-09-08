@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')
    
<div class="container">    
    <div class="row">
        <h4 class="mb-3">Sub Category</h4>

        @foreach($subcategories as $subcategory)                     
            <div class="col-md-2 col-6">
                <x-products :item="$subcategory" section="show_subcategory" variable="subcategory" gallery="no" class="sub_category_page" :producttitle="true" :hover="false" :description="false" :amount="false" :title_limit="20" :short_limit="7" />
            </div>
        @endforeach

        {{ $subcategories->withQueryString()->links() }}    
    </div>
</div>
@endsection

@section('customJs')
    
@endsection