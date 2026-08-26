


<div class="modal fade" id="login" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginLabel">Login to your Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('account.authenticate') }}" method="post" >
                @csrf
                <div class="modal-body">
                    <div class="form-group">                        
                        <label>Email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">                        
                        @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">                        
                        <label>Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" >                        
                        @error('password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>                    
                </div>

                <div class="modal-footer">
                    <div>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgot" class="btn btn-outline-dark btn-sm">Forgot Password?</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#register" class="btn btn-outline-dark btn-sm">Register</a>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-primary caps-btn retirectBack" value="Login">
                    </div>                    
                </div> 
            </form>            
        </div>
    </div>
</div>

<div class="modal fade" id="register" tabindex="-1" aria-labelledby="registerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerLabel">Register Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" name="registrationForm" id="registrationForm">                    
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" name="name">                        
                        <p></p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" placeholder="Email" id="email" name="email">                                
                                <p></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="floating-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">                                
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" id="password" name="password">                                
                                <p></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Confirmed Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">                                
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#login"  class="retirectBack btn btn-outline-dark btn-sm">Bak to Login</a>
                    <input type="submit" class="btn btn-primary" value="Register Account">
                </div> 
            </form>    
        </div>
    </div>
</div>

<div class="modal fade" id="forgot" tabindex="-1" aria-labelledby="forgotLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" name="forgotPasswordForm" id="forgotPasswordForm">                    
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Email" id="email" name="email">                        
                        <p></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#login"  class="btn btn-outline-dark btn-sm retirectBack">Bak to Login</a>
                    <input type="submit" class="btn btn-primary" value="Send Reset Link">
                </div> 
            </form>    
        </div>
    </div>
</div>