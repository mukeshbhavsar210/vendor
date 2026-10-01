 <div class="modal fade" id="selectAddress" tabindex="-1" aria-labelledby="selectAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectAddressLabel">Saved addresses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            @php
                $types = $delivery_address->pluck('address_type')->toArray();
            @endphp                          

            <form method="POST" action="{{ route('address.default') }}">
                @csrf

                <div class="modal-body">
                    <a href="#" class="btn btn-outline-dark mb-4" data-bs-toggle="modal" data-bs-target="#createAddressModal">
                        + Add another address
                    </a>

                    @php
                        $defaultAddressId = old(
                            'address_id',
                            optional($address->firstWhere('default_address', 1))->id
                        );
                    @endphp

                    @foreach($delivery_address as $value)
                        <div class="default-card">                            
                            <label class="delivery-address-card">
                                <div class="card-body">           
                                    <label class="custom-radio">                                                                                                                    
                                        <input type="radio" name="address_id" value="{{ $value->id }}" class="address-radio" {{ $defaultAddressId == $value->id ? 'checked' : '' }} >
                                        <span class="radio-mark"></span>                                    
                                    </label>

                                    <div class="address-content">
                                        <div class="left">
                                            <p><b>{{ $value->address_type }}</b></p>

                                            {{-- <p>{{ $value->default_address ? 'Default' : 'Other' }} Address</p> --}}
                                            {{-- <h6>{{ $value->name }} - {{ $value->mobile }}</h6> --}}
                                            <p class="text-muted mb-0">{{ Str::limit($value->address, 50, '...') }}</p>

                                            <div class="d-none control-btn">
                                                <p class="text-muted mb-0">{{ $value->locality }}, {{ $value->city }} - {{ $value->zip }}, 
                                                    {{ $value->state->name ?? '' }}.
                                                </p>                                                
                                                
                                                <div class="flex-end">
                                                    <ul class="flex mt-3">                                                                                                                        
                                                        <li><button type="submit" name="action" value="delete" class="btn btn-outline-danger btn-sm caps-btn">Delete</button></li>
                                                        <li>
                                                            <button type="button"
                                                                class="btn btn-outline-dark caps-btn btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editAddressModal"                                                
                                                                data-id="{{ $value->id }}"
                                                                data-name="{{ $value->name }}"
                                                                data-mobile="{{ $value->mobile }}"
                                                                data-address="{{ $value->address }}"
                                                                data-state="{{ $value->state_id }}">
                                                                Edit
                                                            </button>
                                                        </li>                                                             
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="default" class="btn btn-primary w-100">Proceed</button>
                </div>
            </form>            
        </div>
    </div>
</div>