<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="css_login/my-login.css">
	<link rel="icon" href="img/Noble.webp">
</head>

<body>
	<div class="wrapper fadeInDown">
		<div id="formContent">
			<!-- Tabs Titles -->
				<img class="user" src="img/Noble.webp">
			<h2 class="active">Sign In</h2>
			<!-- Login Form -->
			<form method="POST" class="my-login-validation" autocomplete="off" action="{{ route('login') }}">
				@csrf
				<input id="email" type="text" class="form-control fadeIn second" name="email" value="{{ old('email') }}" required autofocus placeholder="email">
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
					<a class="underlineHover" href="/">Back</a>
				</div>
			</div>
		</div>
	</div>

	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	@if (Session::has('success'))
	<script>
		swal("Congrate!", "{!! Session::get('success') !!}", "success", {
			button: "OK",
		});
	</script>
	@endif
	@if (Session::get('fail'))
	<script>
		swal("Sorry Fail!", "{!! Session::get('fail') !!}", "warning", {
			button: "OK",
		});
	</script>
	@endif
</body>

</html>