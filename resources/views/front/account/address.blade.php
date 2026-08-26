@extends('front.layouts.app')

@section('title', 'My Saved Address')

@section('content')

<div class="container">    
            @include('front.account.common.sidebar')  
        
            <div class="col-md-9 col-12 px-md-0">
                <div class="orders-details">
                    @include('front.account.common.message')
                    @include('front.layouts.address_modal')

                    <div class="flex-end">
                        <h5 class="h5">Saved Address</h5>
                        {{-- <a href="#" class=" btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#deliveryAddress">Change Default Address</a> --}}
                        @if(!in_array('Home', $addressTypes) || !in_array('Office', $addressTypes))
                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createAddressModal">
                                + Add New Address
                            </button>                    
                        @endif
                    </div>

                    @php
                        $defaultAddressId = old(
                            'default_address_id',
                            optional($address->firstWhere('default_address', 1))->id
                        );
                    @endphp                

                    <x-customer-address-form     
                        :states="$states"
                        :action="route('customer.address.store')" 
                        method="POST" 
                        title="Add New Address" 
                        buttonText="Create Address"
                        modalId="createAddressModal"
                    />
                                    
                    <div class="mt-3 ">
                        @foreach($address as $value)                            
                            <div class="card mb-3 {{ $value->default_address == 1 ? 'default-address' : 'other' }}">
                                <div class="card-header">
                                    <p class="title">{{ $value->default_address == 1 ? 'Default Address' : 'Other Address' }}</p>
                                </div>
                                <div class="card-body">                                
                                    <p class="small-font">
                                        <b>{{ $value->address_type == 'Home' ? 'Home' : 'Office' }}</b>
                                    </p>
                                    <h6>{{ $value->name }} - {{ $value->mobile }}</h6>
                                    <p class="small-font">                                    
                                        {{ $value->address }}, {{ $value->locality }},
                                        {{ $value->city }}-{{ $value->zip }}, {{ $value->state->name }}.
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>                                    
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script>
    $("#addressForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('account.updateAddress') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('editAddress'));
                modal.hide();
                location.reload();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
</script>
@endsection