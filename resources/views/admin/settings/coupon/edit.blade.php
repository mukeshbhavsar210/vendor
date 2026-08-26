@extends('admin.layouts.app')

@section('content')

<div class="card mb-0">
    <div class="card-body pb-0">            
        <div class="row">                
            <div class="col-sm-7 col-12">
                <div class="page-title">
                    <h4>Edit Coupon</h4>                    
                </div>
            </div>
            <div class="col-sm-5 col-12 float-end">
                <a href="{{ route('coupons.index') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>
    </div>
</div>

<form action="" method="post" id="discountForm" name="discountForm">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Coupon Code" value="{{ $coupon->code}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Coupon Code Name" value="{{ $coupon->name}}">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_uses">Max uses</label>
                                <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Uses" value="{{ $coupon->max_uses}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_uses_user">Max uses User</label>
                                <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max uses User" value="{{ $coupon->max_uses_user}}">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option {{ ($coupon->type == 'percent') ? 'selected' : '' }} value="Percent">Percent</option>
                                    <option {{ ($coupon->type == 'fixed') ? 'selected' : '' }} value="Fixed">Fixed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control">{{ $coupon->description }}</textarea>
                        <p></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount amount" value="{{ $coupon->discount_amount }}" >
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_amount">Min Amount</label>
                                <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount" value="{{ $coupon->min_amount }}" >
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option {{ ($coupon->status == 1) ? 'selected' : '' }}  value="1">Active</option>
                                    <option {{ ($coupon->status == 0) ? 'selected' : '' }}  value="0">Block</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="starts_at">Starts at</label>
                                <input autocomplete="off" type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts at" value="{{ $coupon->starts_at }}">
                                <p></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expires_at">Expires at</label>
                                <input autocomplete="off" type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires at" value="{{ $coupon->expires_at }}">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
    </div>
    </div>
</form>

@endsection

@section('customJs')
    <script>
        $(document).ready(function(){
            $('#starts_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });

            $('#expires_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });
        });

        $("#discountForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("coupons.update", $coupon->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){

                        window.location.href="{{ route('coupons.index') }}"

                        $('#code').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#discount_amount').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#starts_at').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#expires_at').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                    } else {
                        var errors = response['errors']
                        if(errors['code']){
                            $('#code').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['code']);
                        } else {
                            $('#code').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['discount_amount']){
                            $('#discount_amount').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['discount_amount']);
                        } else {
                            $('#discount_amount').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['starts_at']){
                            $('#starts_at').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['starts_at']);
                        } else {
                            $('#starts_at').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['expires_at']){
                            $('#expires_at').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['expires_at']);
                        } else {
                            $('#expires_at').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                    }

                }, error: function(jqXHR, exception) {
                    console.log("Something event wrong");
                }
            })
        });
    </script>
@endsection
