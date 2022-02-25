<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Login page</title>
	<!--link rel="stylesheet" href="bootstrap/css/bootstrap.css"-->
	<link rel="stylesheet" type="text/css" href="css_login/my-login.css">
	<link rel="icon" href="img/Noble.png" type="image/png">
</head>

<body>
	
							<!--form method="POST" class="my-login-validation" autocomplete="off" action="{{ route('login') }}">
                                @csrf
								<div class="form-group">
									<label for="email">E-Mail Address</label>
									<input id="email" type="email" class="form-control" name="email" value="" required autofocus placeholder="Enter email">
                                    <span class="text-danger">@error('email'){{ $message }}@enderror</span>
								</div>

								<div class="form-group">
									<label for="password">Password
										<a href="{{route('password.request')}}" class="float-right">
											Forgot Password?
										</a>
									</label>
									<input id="password" type="password" class="form-control" name="password" required data-eye placeholder="Enter password">
                                    <span class="text-danger">@error('password'){{ $message }}@enderror</span>
								</div>

								<div class="form-group">
									<div class="custom-checkbox custom-control">
										<input type="checkbox" name="remember" id="remember" class="custom-control-input">
										<label for="remember" class="custom-control-label">Remeber Me</label>
									</div>
								</div>

								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block">
										Login
									</button>
								</div>
								<div class="mt-4 text-center">
									Don't have an account? <a href="{{route('register')}}">Create One</a>
								</div>
							</!--form>
						</div>
					</div>
				
				</div>
			</div>
		</div>
	</!--section>
-->
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Sign In </h2>
    
    <!-- Login Form -->
    <form method="POST" class="my-login-validation" autocomplete="off" action="{{ route('login') }}">
	@csrf
	<input id="email" type="text" class="form-control fadeIn second" name="email" value="" required autofocus placeholder="email">
    <span class="text-danger">@error('email'){{ $message }}@enderror</span>
      <!--input type="text" id="login" class="fadeIn second" name="login" placeholder="login"-->
	<input id="password" type="password" class="form-control fadeIn third" name="password" required data-eye placeholder="password">
	<span class="text-danger">@error('password'){{ $message }}@enderror</span>
      <!--input type="text" id="password" class="fadeIn third" name="login" placeholder="password"-->
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
		<div class="row">
		<div class="col-4 col-md-5">

	</div>

	  <a class="underlineHover" href="/">Back</a>
	 
	  </div>
    </div>

  </div>
</div>
	<script src="bootstrap/js/popper.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="js_login/my-login.js"></script>

	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    @if (Session::has('success'))
                        <script>
                            swal("Congrate!","{!! Session::get('success') !!}","success",{
                            button:"OK",
                            });             
                        </script>
                    @endif
                    @if (Session::get('fail'))
                        <script>
                            swal("Sorry Fail!","{!! Session::get('fail') !!}","warning",{
                            button:"OK",
                            });             
                        </script>
                    @endif
</body>
</html>
