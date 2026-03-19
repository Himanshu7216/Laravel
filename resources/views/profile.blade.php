<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <style>
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #dee2e6;
        }

        .profile-img-wrapper {
            position: relative;
            width: 120px;
            margin: auto;
        }

        .img-upload {
            position: absolute;
            bottom: 0;
            right: 0;
        }

        #successNotification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 9999;
            min-width: 300px;
        }

        #errorNotification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 9999;
            min-width: 300px;
        }
    </style>

</head>

<body class="bg-light">
    <div id="successNotification" class="alert alert-success text-center"></div>
    <div id="errorNotification" class="alert alert-danger text-center"></div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <div class="d-flex justify-content-end mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="twoFactorToggle"
                                name="enable_two_factors" {{ $user->enable_two_factors == 'enable' ? 'checked' : '' }}>
                            <label class="form-check-label" for="twoFactorToggle">
                                2FA
                            </label>
                        </div>
                    </div>
                    <h4 class="text-center mb-4">Update Profile</h4>
                    <form action="/profile/update/{{ $user->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Profile Image -->
                        <div class="text-center mb-4">
                            <div class="profile-img-wrapper">
                                <img src="{{ asset('storage/profile/' . $user->profile_picture) }}" class="profile-img">
                                <input type="file" class="form-control mt-3" name="profile">
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter your name"
                                value="{{ $user->name }}">
                            <div class="text-danger error-name"></div>
                            @if($errors->has('name'))
                                <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="number" class="form-control" name="phone" placeholder="Enter phone number"
                                value="{{ $user->phone }}">
                            <div class="text-danger error-phone"></div>
                            @if($errors->has('phone'))
                                <div class="error text-danger">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/profile.js') }}"></script>
</body>

</html>
