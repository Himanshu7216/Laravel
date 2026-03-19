<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">

</head>
<body>
    <div id="successNotification" class="alert alert-success text-center">
        Signup successful! Redirecting to login...
    </div>
    <div id="errorNotification" class="alert alert-danger text-center">

    </div>
    <form id="signupForm" class="container col-lg-6 " action="/signup" method="post" enctype="multipart/form-data"
        autocomplete="off">
        @csrf
        <h1 class="text-center">Signup</h1>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" maxlength="50" autocomplete="off">
            <div class="text-danger error-name"></div>
            @if($errors->has('name'))
                <div class="error text-danger">{{ $errors->first('name') }}</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="text" class="form-control" id="email" name="email" maxlength="50" autocomplete="off">
            <div class="text-danger error-email"></div>
            @if($errors->has('email'))
                <div class="error text-danger">{{ $errors->first('email') }}</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" maxlength="10" autocomplete="off">
            <div class="text-danger error-phone"></div>
            @if($errors->has('phone'))
                <div class="error text-danger">{{ $errors->first('phone') }}</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" maxlength="20" autocomplete="off">
            <div class="text-danger error-password"></div>
            @if($errors->has('password'))
                <div class="error text-danger">{{ $errors->first('password') }}</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="profile" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="profile" name="profile">
            <div class="text-danger error-profile"></div>
            @if($errors->has('profile'))
                <div class="error text-danger">{{ $errors->first('profile') }}</div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-secondary" onclick="resetValidation()">Reset</button>
        <P>already have an account? <a class="text-decoration-none" href="/login">Login</a></P>
    </form>

    <script src="{{ asset('js/signup.js') }}"></script>

</body>

</html>
