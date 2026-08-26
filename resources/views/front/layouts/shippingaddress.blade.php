<div class="modal fade" id="addAddress2" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" id="create_addressForm" name="create_addressForm">
                            <div class="modal-body"> 
                                <h4>Contact Details</h4>
                                <div class="row">                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" >
                                            <p></p>
                                        </div>
                                    </div>  
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile" >
                                            <p></p>
                                        </div>
                                    </div>
                                </div>

                                <h4>Address</h4>
                                <div class="row">                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="House Number/Tower/Block*" class="form-control" ></textarea>
                                            <p></p>
                                        </div>
                                    </div>                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Locality</label>
                                            <input type="text" name="locality" id="locality" class="form-control" placeholder="Address (locality, building, street)*" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" >
                                            <p></p>
                                        </div>
                                    </div>        
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Pincode</label>
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Pincode" >
                                            <p></p>
                                        </div>
                                    </div>                                                               
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select name="state" id="state" class="form-control">
                                                <option value="">Select a State</option>
                                                    @if ($states->isNotEmpty())
                                                        {{-- @foreach ($states as $value)
                                                            <option {{ (!empty($customerAddress) && $customerAddress->state_id == $value->id) ? 'selected' : '' }} value="{{ $state->id }}" >{{ $state->name }}</option>
                                                        @endforeach --}}
                                                        <option value="rest_of_state">Rest of the state</option>
                                                    @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>                                      
                                    
                                    {{-- 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Order notes</label>
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                            <p></p>
                                        </div>
                                    </div>                     --}}
                                </div>                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>