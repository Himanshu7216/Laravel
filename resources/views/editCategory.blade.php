

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>edit Categories</title>
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
      <form id="editCategoryForm" action="/categories/update/{{ $category->id }}" method="post" >
        @csrf
        {{-- @method('PUT') --}}

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Update Category</h1>

            <a href="/categories" class="btn btn-outline-secondary">
                ← Back
            </a>
        </div>
        <input type="hidden" id="categoryId" value="{{ $category->id }}">
        <div class="mb-3">
          <label for="categoryname" class="form-label">Category Name</label>
          <input type="text" class="form-control" name="categoryname" id="categoryname"
            placeholder="Enter category name" maxlength="50" value="{{ $category->name }}" >
            <div class="text-danger error-categoryname"></div>

          @if($errors->has('categoryname'))
          <div class="error text-danger">{{ $errors->first('categoryname') }}</div>
          @endif

        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3" isvalid="false" placeholder="Enter description here.." maxlength="1000">{{ $category->description }}</textarea>
            <div class="text-danger error-description"></div>
          @if($errors->has('description'))
            <div class="error text-danger">{{ $errors->first('description') }}</div>
          @endif

        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status">
            <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
          @if($errors->has('status'))
            <div class="error text-danger">{{ $errors->first('status') }}</div>
          @endif
          <div class="text-danger error-status"></div>
        </div>
        <button type="submit" class="btn btn-primary">Update Category</button>
      </form>
    </div>
  </main>
    <script src="{{ asset('js/editCategory.js') }}"></script>

</body>


</html>




