@extends('front.layouts.app')

@section('title', 'Login Account')

@section('content')

    @include('front/layouts/message')

    <div class="container">
        <div class="row">
            <div class="col-md-6">1</div>
            <div class="col-md-6">
                <div class="login-form">
                    <h4 class="modal-title">Login / Signup</h4>
                    <p class="tiny-font">Join us now to be a part of {{ config('app.name') }} family.</p>

                    <form action="{{ route('account.authenticate') }}" method="post" class="mt-4" >
                        @csrf                        
                        <div class="form-group">
                            <input type="text" class="form-control floating-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                            <label class="floating-label">Email</label>
                            @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control floating-input @error('password') is-invalid @enderror" name="password" >
                            <label class="floating-label">Password</label>
                            @error('password')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex-end">
                            {{-- <a href="#" class="forgot-link mt-3">Forgot Password?</a> --}}
                            <p class="mt-2">Don't have an account? <a href="{{ route('account.register') }}" ><b>Sign up</b></a></p>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    
                    <div class="social-btns">
                        <p class="or">OR</p>
                        <div class="flex">                            
                            <a href="{{ url('auth/google') }}" class="btn btn-outline-dark w-50">
                                {{-- <span class="sprites"></span> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-google_svg__a)"><path fill="#4285F4" d="M15.844 8.184c0-.544-.044-1.09-.138-1.625H8.16v3.08h4.321a3.703 3.703 0 0 1-1.6 2.431v2h2.579c1.514-1.394 2.384-3.452 2.384-5.886Z"></path><path fill="#34A853" d="M8.16 16c2.158 0 3.977-.708 5.303-1.93l-2.578-2c-.717.488-1.643.765-2.722.765-2.087 0-3.857-1.409-4.492-3.302h-2.66v2.061A8.001 8.001 0 0 0 8.16 16Z"></path><path fill="#FBBC04" d="M3.668 9.534a4.792 4.792 0 0 1 0-3.063V4.41H1.011a8.007 8.007 0 0 0 0 7.184l2.657-2.06Z"></path><path fill="#EA4335" d="M8.16 3.166a4.347 4.347 0 0 1 3.069 1.2l2.284-2.284A7.689 7.689 0 0 0 8.16 0 7.998 7.998 0 0 0 1.011 4.41l2.657 2.06C4.3 4.575 6.073 3.167 8.16 3.167Z"></path></g><defs><clipPath id="login-google_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                Google
                            </a>                        
                            <a href="{{ url('auth/facebook') }}" class="btn btn-outline-dark w-50">
                                {{-- <span class="sprites"></span> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="none" viewBox="0 0 16 16" style="height: 16px; width: 16px;" class=" " stroke="none"><g clip-path="url(#login-facebook_svg__a)"><path fill="#1877F2" d="M16 8a8 8 0 1 0-9.25 7.903v-5.59H4.719V8H6.75V6.237c0-2.005 1.194-3.112 3.022-3.112.875 0 1.79.156 1.79.156V5.25h-1.008c-.994 0-1.304.617-1.304 1.25V8h2.219l-.355 2.313H9.25v5.59A8.002 8.002 0 0 0 16 8Z"></path><path fill="#fff" d="M11.114 10.313 11.47 8H9.25V6.5c0-.633.31-1.25 1.304-1.25h1.008V3.281s-.915-.156-1.79-.156c-1.828 0-3.022 1.107-3.022 3.112V8H4.719v2.313H6.75v5.59c.828.13 1.672.13 2.5 0v-5.59h1.864Z"></path></g><defs><clipPath id="login-facebook_svg__a"><path fill="#fff" d="M0 0h16v16H0z"></path></clipPath></defs></svg>
                                Facebook
                            </a>                                             
                        </div>          
                    </div>

                    <p class="mt-3 tiny-font">By creating an account or logging in, you agree with {{ config('app.name') }} T&C and Privacy Policy</p>
                </div>
            </div>
        </div>
    </div>
@endsection