@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')
    
<div class="container">    
    <h4>{{ ucfirst($selected_category) }}</h4>

    <div class="row mt-3">
        @foreach($categories as $category)         
            <div class="col-md-2 col-6">                
                <x-products :item="$category" section="show_category" variable="category" gallery="no" class="category_page" :producttitle="true" :hover="false" :description="false" :amount="false" :title_limit="15" :short_limit="7" />
            </div> 
        @endforeach

        {{ $categories->withQueryString()->links() }}    
    </div>
</div>
@endsection

@section('customJs')
    
@endsection