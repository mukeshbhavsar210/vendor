<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Administrative Panel</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">	
	<link href="{{ asset('admin-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('admin-assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body data-sidebar-size="collapsed">
	<div class="container-xxl">
			<div class="row vh-100 d-flex justify-content-center">
				<div class="col-12 align-self-center">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-4 mx-auto">
								<div class="card">
									<div class="card-body p-0 bg-black auth-header-box rounded-top">
										<div class="text-center p-3">
											<a href="index.html" class="logo logo-admin">
												<img src="{{ asset('front-assets/images/logo.png') }}" alt=""  height="50" alt="logo" class="auth-logo"/>
											</a>											
										</div>
									</div>
									<div class="card-body pt-0"> 
										
										@include('admin.message')

										<form class="mt-3"  action="{{ route('admin.authenticate') }}" method="post">
											@csrf
												<div class="form-group mb-2">
													<label class="form-label" for="username">Username</label>
													<input type="email" value="{{ old('email') }}" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
													@error('email')
														<p class="invalid-feedback">{{ $message }}</p>
													@enderror
											  	</div>

												<div class="form-group mb-2">
													<label class="form-label" for="userpassword">Password</label>
													<input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
													@error('password')
														<p  class="invalid-feedback">{{ $message }}</p>
													@enderror
												</div>

											  <div class="form-group row mt-3">
												<div class="col-sm-6">
													<div class="form-check form-switch form-switch-success">
														<input class="form-check-input" type="checkbox" id="customSwitchSuccess">
														<label class="form-check-label" for="customSwitchSuccess">Remember me</label>
													</div>
												</div>												
											</div>
											<div class="form-group row">
												<div class="col-12">
													<div class="d-grid mt-3">
														<button type="submit" class="btn btn-primary btn-block">Login</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>                                        
		</div>			
	</body>
</html>
