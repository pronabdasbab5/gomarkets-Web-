<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GOMARKETS Admin - Register</title>

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">GM</h1>

            </div>
            <h3>Register to GoMarkets</h3>
            @if (session()->has('msg'))
                <div class="alert alert-success mb-4" role="alert">
                    {{ session()->get('msg') }}
                </div>
            @endif
            <form class="m-t" role="form" method="POST" action="{{ url('admin-register') }}" autocomplete="off">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" >
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input type="numeric" class="form-control" name="contact_no" placeholder="Contact No" value="{{ old('contact_no') }}" >
                    @error('contact_no')
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="{{ route('admin.login') }}">Login</a>
            </form>
            <p class="m-t"> <small>Developed By SoftZoned Team</p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}s"></script>
    <script src="{{ asset('backend/js/popper.min.js') }}s"></script>
    <script src="{{ asset('backend/js/bootstrap.js') }}s"></script>
</body>
</html>