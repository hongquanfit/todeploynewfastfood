<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/Login/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/Login/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/assets/Font-Awesome/web-fonts-with-css/css/fontawesome-all.min.css')}}">
</head>
<body>
<div class="gray-bg" style="height: inherit;">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h2 class="logo-name">&nbsp;</h2>
            </div>
            @if(session('failed'))
            <div class="alert alert-danger">
                {{ __('loginFail') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{ __('successReg') }}
            </div>
            @endif
            <form class="m-t" role="form" action="{{route('doLogin')}}" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
            <div>
                Don't have an account? <a href="{{url('register')}}">Register!</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
