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
    </style>

</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h4 class="text-center mb-4">Update Profile</h4>
                    <form action="/profile/update/{{ $user->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Profile Image -->
                        <div class="text-center mb-4">

                            <div class="profile-img-wrapper">

                                <img src="{{ asset('storage/profile/'.$user->profile_picture) }}" class="profile-img">

                                <input type="file" class="form-control mt-3" name="profile_picture">

                            </div>

                        </div>


                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter your name" value="{{ $user->name }}">
                             @if($errors->has('name'))
                            <div class="error text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="number" class="form-control" name="phone" placeholder="Enter phone number" value="{{ $user->phone }}">
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

</body>

</html>
