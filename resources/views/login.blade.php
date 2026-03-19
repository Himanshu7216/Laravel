<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>
<body>

    <div id="successNotification" class="alert alert-success text-center">
    </div>
    <div id="errorNotification" class="alert alert-danger text-center">
    </div>

    <form id="loginForm" class="container col-lg-6 " action="/login" method="POST">
        @csrf
        <h1 class="text-center">Login</h1>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" maxlength="50"
                placeholder="Enter Email here.."  >
            <div class="text-danger error-email"></div>
            @if($errors->has('email'))
                <div class="error text-danger">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                placeholder="Enter Password here.."  maxlength="20">
            <div class="text-danger error-password"></div>
            @if($errors->has('password'))
                <div class="error text-danger">{{ $errors->first('password') }}</div>
            @endif
        </div>
        {{-- only 2FA user show --}}
        @if(session('enable_two_factors') == "enable")
            <div class="mb-3" id="otpDiv">
                <label for="otp" class="form-label">OTP</label>
                <input type="text" class="form-control" id="otp" name="otp" maxlength="6">
                <div class="text-danger error-otp"></div>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        @endif

        <button type="submit" class="btn btn-primary">Login</button>
        <P>don't have an account? <a class="text-decoration-none" href="/signup">Sign up</a></P>
    </form>


    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
