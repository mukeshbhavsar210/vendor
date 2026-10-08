@extends('front.layouts.app')

@section('title', 'Saved Cards')

@section('content')

<div class="container">    
    @include('front.account.common.sidebar')              
    <div class="col-md-9 col-12 px-md-0">                    
        <div class="orders-details">
            <h3>Saved Cards</h3>                
        </div>
    </div>
</div>       
@endsection

@section('customJs')
<script>
    
</script>
@endsection