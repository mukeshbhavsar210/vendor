@extends('front.layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container">    
    @include('front.account.common.sidebar')

                <div class="col-md-9 col-12 px-md-0">
                    <div class="details-accounts">
                        @include('front.account.common.message')

                        @include('front.account.common.modal', [
                            'form' => $profileFormConfig,
                            'model' => $user
                        ])

                        @include('front.account.common.modal', [
                            'form' => $passwordFormConfig,
                            'model' => null
                        ])

                        <h3>Profile Details</h3> 

                        <div class="order-history">
                            <div class="individual">
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Name</div>
                                    <div class="col-md-9 col-12">{{ $user->name }}</div>
                                </div>                                
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Email ID</div>
                                    <div class="col-md-9 col-12">{{ $user->email }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Mobile Number</div>
                                    <div class="col-md-9 col-12">{{ $user->mobile }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Alternate Mobile</div>
                                    <div class="col-md-9 col-12">{{ $user->alternate_mobile }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Gender</div>
                                    <div class="col-md-9 col-12">{{ $user->gender == 'male' ? 'Male' : 'Female' }}</div>
                                </div>   
                                <div class="row mb-2">
                                    <div class="col-md-3 col-12 text-muted">Date of Birth</div>
                                    <div class="col-md-9 col-12">
                                        {{ \Carbon\Carbon::parse($user->birthdate)->format('d M, Y') }}
                                    </div>
                                </div>           
                                <div class="row mt-4">
                                    <div class="col-md-3 col-12"></div>
                                    <div class="col-md-9 col-12">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                            Edit Profile
                                        </button>

                                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                            Edit Password
                                        </button>
                                    </div>
                                </div>                                                 
                            </div>
                        </div>
                    </div>                  
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>
    $("#changePasswordForm").submit(function(event){
        event.preventDefault();

        $("#submit").prop('disbled','true');

        $.ajax({
            url: '{{ route("account.processChangePassword") }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                $("#submit").prop('disbled','false');
                if (response.status == true){
                    $('#old_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $('#new_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $('#confirm_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    //window.location.href = '{{ route("account.changePassword") }}'
                } else {
                    var errors = response.errors;

                    if(errors.old_password){
                        $('#old_password').addClass('is-invalid').siblings('p').html(errors.old_password).addClass('invalid-feedback');
                    } else {
                        $('#old_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }

                    if(errors.new_password){
                        $('#new_password').addClass('is-invalid').siblings('p').html(errors.new_password).addClass('invalid-feedback');
                    } else {
                        $('#new_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }

                    if(errors.confirm_password){
                        $('#confirm_password').addClass('is-invalid').siblings('p').html(errors.confirm_password).addClass('invalid-feedback');
                    } else {
                        $('#confirm_password').removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    }
                }
            }
        })
    })
</script>
@endsection
