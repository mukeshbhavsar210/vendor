@extends('front.layouts.app')

@section('title', 'Register Account')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6">1</div>
        <div class="col-md-6">
            <div class="login-form">
                <h4 class="modal-title">Register Account</h4>
                <p class="tiny-font">Join us now to be a part of {{ config('app.name') }} family.</p>

                <form action="" method="post" name="registrationForm" id="registrationForm" class="mt-3">                    
                    <div class="form-group">
                        <input type="text" class="form-control floating-input" id="name" name="name">
                        <label class="floating-label">Name</label>
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control floating-input" id="email" name="email">
                        <label class="floating-label">Email</label>
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control floating-input" id="mobile" name="mobile">
                        <label class="floating-label">Mobile</label>
                        <p></p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="password" class="form-control floating-input" id="password" name="password">
                                <label class="floating-label">Password</label>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="password" class="form-control floating-input" id="password_confirmation" name="password_confirmation">
                                <label class="floating-label">Confirm Password</label>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-end">
                        {{-- <a href="#" class="forgot-link">Forgot Password?</a> --}}
                        <p class="mt-2">Already have an account? <a href="{{ route('account.login') }}"><b>Login</b></a></p>
                        <button type="submit" class="btn btn-primary">Register Account</button>
                    </div>                
                </form>                
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')

<script type="text/javascript">
    
</script>

@endsection
