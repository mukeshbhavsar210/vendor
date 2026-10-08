@extends('front.layouts.app')

@section('title', 'My Dashboard')

@section('content')

<div class="container">    
    @include('front.account.common.sidebar')  
    <div class="col-md-9 col-12 px-md-0">
        <div class="details-accounts">
            <h3>Notifications</h3>
        
            <div class="row mt-4">
                @forelse ($notifications as $notify)
                    <div class="col-md-4 col-6">
                        <x-services 
                            :item="$notify->product" 
                            :notifyData="$notify"
                            section="show_notify"
                            gallery="yes"
                            variable="notify"
                            class="notify"
                            :producttitle="true"
                            :hover="true"
                            :description="true"
                            :amount="true"
                            :title_limit="27"
                            :short_limit="35"
                        />
                    </div>
                @empty
                    <p>No product notifications</p>
                @endforelse
            </div>                       
        </div>
    </div>                  
@endsection

@section('customJs')

@endsection