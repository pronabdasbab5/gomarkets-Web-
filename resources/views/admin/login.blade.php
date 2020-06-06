<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GOMARKETS Admin - Logins</title>

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">GM</h1>

            </div>
            <h3>Welcome to GM</h3>
            <p>Login in. To see it in action.</p>
            @if (session()->has('login_error'))
                <div class="alert alert-danger mb-4" role="alert">
                    {{ session()->get('login_error') }}
                </div>
            @endif
            <form class="m-t" role="form" method="POST" action="{{ url('admin-login') }}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <input name="username" type="text" value="{{ old('username') }}" class="form-control" placeholder="Contact No or Email">
                    @error('username')
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
            <p class="m-t"> <small>Developed By SoftZoned Team </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootstrap.js') }}"></script>

</body>
</html>
