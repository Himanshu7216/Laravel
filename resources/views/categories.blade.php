<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Categories</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<style>
        #successNotification{
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: none;
    z-index: 9999;
    min-width: 300px;
}
    #errorNotification{
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

<body>

@include('components.navbar')

<div id="successNotification" class="alert alert-success text-center">
</div>
<div id="errorNotification" class="alert alert-danger text-center">
</div>
  <main>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h1>Categories</h1>
          <input type="button" value="Add Category" class="btn btn-secondary "
            onClick="window.location.href='{{ route('addCategory') }}'">
        </div>


        <table class="table table-striped-columns">
          <thead>
            <tr>
              <th scope="col">Sr No.</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $category)
              <tr id="categoryRow{{ $category->id }}">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                @if ($category->status ==="inactive")
                    <td class="text-danger">{{ $category->status }}</td>
                @else
                <td>{{ $category->status }}</td>
                @endif
                <td>
                  <a href="/categories/edit/{{ $category->id }}" class="btn btn-sm btn-outline-warning">Update</a>
                  <form class="deleteCategoryForm" data-id="{{ $category->id }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            Delete
                        </button>
                    </form>
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>

    </div>
  </main>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/category.js') }}"></script>
</body>

</html>
