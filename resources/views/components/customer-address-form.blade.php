@props([
    'address' => null,
    'homeExists' => false,
    'title' => '',
    'buttonText' => '',
    'modalId' => '',
    'action' => '',
    'method' => 'POST'
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ $action }}" method="POST">
                @csrf

                @if($method === 'PUT')
                    @method('PUT')
                @endif

                <div class="modal-body customer-address"> 
                    <h4 class="mb-1">Contact Details</h4>
                    <div class="row">                    
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" name="name" id="name" class="form-control floating-input" value="{{ old('name', $address->name ?? '') }}" >
                                <label class="floating-label">Name</label>
                                <p></p>
                            </div>
                        </div>  
                        <div class="col-6">
                            <div class="form-group">                                
                                <input type="text" name="mobile" id="mobile" class="form-control" value={{ (!empty($address)) ? $address->mobile : '' }}>
                                <label class="floating-label">Mobile</label>
                                <p></p>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-1">Address</h4>
                    <div class="row">                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="address" id="address" cols="30" rows="3" placeholder="House Number/Tower/Block*" class="form-control floating-input" >
                                    {{ (!empty($address)) ? $address->address : '' }}
                                </textarea>
                                <label class="floating-label">House Number/Tower/Block</label>
                                <p></p>
                            </div>
                        </div>                    
                        <div class="col-md-12 col-12">
                            <div class="form-group">                                
                                <input type="text" name="locality" id="locality" class="form-control" value={{ (!empty($address)) ? $address->locality : '' }}>
                                <label class="floating-label">Locality, Building, Street</label>
                            </div>
                        </div>
                        <div class="col-md-5 col-12">
                            <div class="form-group">
                                <input type="text" name="city" id="city" class="form-control" value={{ (!empty($address)) ? $address->city : '' }}>
                                <label class="floating-label">City</label>
                                <p></p>
                            </div>
                        </div>                                                                                                                      
                        <div class="col-md-4 col-12">
                            <div class="form-group">                                
                                <select name="state_id" required class="form-select">
                                    <option value="">State</option>

                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ old('state_id', $address->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach

                                    <option value="rest_of_state"
                                        {{ old('state_id', $address->state_id ?? '') == 'rest_of_state' ? 'selected' : '' }}>
                                        Rest of the state
                                    </option>
                                </select>
                                <p></p>
                            </div>
                        </div>  
                        <div class="col-md-3 col-12">
                            <div class="form-group">                                
                                <input type="text" name="zip" id="zip" class="form-control" value={{ (!empty($address)) ? $address->zip : '' }}>
                                <label class="floating-label">Pincode</label>
                                <p></p>
                            </div>
                        </div> 
                        <div class="col-12">                            
                            @if(!$homeExists)
                                <div class="form-group">                                                    
                                    @php 
                                        $addressType = old('address_type', auth()->user()->address->address_type ?? '');
                                    @endphp

                                    {{-- @php 
                                        $addressType = old('address_type', optional(optional(auth()->user())->address)->address_type);
                                    @endphp --}}

                                    <label for="City">Types of address <span class="required">*</span></label><br /> 
                                    <label for="home">
                                        <input type="radio" name="address_type" id="home" value="Home" checked
                                            {{ old('address_type', $addressType ?? 'Home') == 'Home' ? 'checked' : '' }}>
                                        Home
                                    </label>

                                    <label for="office">
                                        <input type="radio" name="address_type" id="office" value="Office"
                                        {{ $addressType == 'Office' ? 'checked' : '' }}>
                                        Office
                                    </label>
                                </div>
                            @else
                                <input type="hidden" name="address_type" value="Office">
                                <label><input type="radio" checked disabled>Office</label>
                            @endif
                            
                            <div class="form-check mt-3">                                   
                                @php
                                    $defaultAddress = old('default_address', auth()->user()?->address?->default_address);
                                @endphp

                                <input type="checkbox" class="form-check-input" name="default_address" id="default_address" value="1"
                                    {{ $defaultAddress ? 'checked' : '' }}>

                                <label class="form-check-label" for="default_address">Make this as my default Address</label>
                            </div>
                        </div>
                    </div>                
                                    
                    <div class="flex mt-3">
                        <button type="submit" class="btn btn-primary w-100">{{ $buttonText }}</button> 
                    </div>
               </div>
            </form>
        </div>
    </div>            
</div>  