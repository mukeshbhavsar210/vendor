@extends('admin.layouts.app')

@section('content')

<div class="card mb-0">
    <div class="card-body pb-0">
        <div class="row">            
            <div class="col-sm-7 col-12 d-flex">                    
                <div class="page-title">
                    <h4>Edit Vendor</h4>
                </div>                    
            </div>
            <div class="col-sm-5 col-12">
                <a href="{{ route('vendors.index') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>        
    
        <form action="" method="post" id="userForm" name="userForm">            
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="business_name">Business Name</label>
                        <input value="{{ $vendor->business_name }}" type="text" name="business_name" id="business_name" class="form-control" placeholder="Business Name">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input value="{{ $vendor->email }}" type="text" name="email" id="email" class="form-control" placeholder="Email">
                        <p></p>
                    </div>
                </div>            
                <div class="col-md-3 col-12">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option {{ ($vendor->status == 1) ? 'selected' : ' '}} value="1">Active</option>
                            <option {{ ($vendor->status == 0) ? 'selected' : ' '}} value="0">Block</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </div>
            </div>
        </form>
    </div>    
</section>
@endsection

@section('customJs')
    <script>
        $("#userForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("users.update",$vendor->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){
                        $('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#email').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#password').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $('#phone').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        window.location.href="{{ route('users.index') }}"

                    } else {
                        var errors = response['errors']
                        if(errors['name']){
                            $('#name').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $('#name').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['email']){
                            $('#email').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['email']);
                        } else {
                            $('#email').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['password']){
                            $('#password').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['password']);
                        } else {
                            $('#password').removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['phone']){
                            $('#phone').addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['phone']);
                        } else {
                            $('#phone').removeClass('is-invalid')
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
