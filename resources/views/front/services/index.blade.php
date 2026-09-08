@extends('front.layouts.app')

@section('title', 'Online Fashion Shopping for Men and Women')

@section('content')
    
<div class="container">    
    <div class="row">
        <div class="col-md-4 col-6">
            <h1>{{ ucfirst($selected_category) }}</h1>                    
        </div>                
        
        @foreach($services as $value)        
            {{ $value->title }}

            <div>
                <x-products :item="$value"                     
                    variable="category" 
                    gallery="no" 
                    class="category_page" 
                    :producttitle="true" 
                    :hover="false" 
                    :description="false" 
                    :amount="false" 
                    :title_limit="15" 
                    :short_limit="7" 
                />
            </div> 
        @endforeach
        
    </div>
</div>
@endsection

@section('customJs')
    
@endsection