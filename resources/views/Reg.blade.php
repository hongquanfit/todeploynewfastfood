<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="{{asset('public/Login/animate.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('public/Login/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('public/Login/css/font-awesome.min.css')}}">
</head>
<body>
<div class="gray-bg" style="height: inherit;">
	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div>
				<h2 class="logo-name">&nbsp;</h2>
			</div>
			@if(session('wrong'))
			<div class="alert alert-danger">
			    {{ __('regFail') }}
			</div>
			@endif
			<form class="m-t" role="form" action="{{route('doReg')}}" method="post">
				{{csrf_field()}}
				<div class="form-group">
					<input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Name">
				</div>
				<div class="form-group {{ $errors->has('email') ? ' has-error':''}}">
					<input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="Email">
					<small class="text-danger">{{ $errors->first('email') }}</small>
				</div>
				<div class="form-group {{ $errors->has('password') ? ' has-error':''}}">
					<input type="password" name="password" class="form-control" placeholder="Password">
					<small class="text-danger">{{ $errors->first('password') }}</small>
				</div>
				<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error':''}}">
					<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
					<small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Register</button>
			</form>
			<div>
				Already have an account? <a href="{{ url('login') }}">Login!</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>
