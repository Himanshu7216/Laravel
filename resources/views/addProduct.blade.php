<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>add product</title>
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
      <form id="addProductForm" action="/products/add" method="post" enctype="multipart/form-data">
        @csrf
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Add Product</h1>

            <a href="/products" class="btn btn-outline-secondary">
                ← Back
            </a>
        </div>
        <div class="mb-3">
          <label for="productname" class="form-label">Product Name</label>
          <input type="text" class="form-control" name="productname" id="productname" placeholder="Enter product name" maxlength="100">
           <div class="text-danger error-productname"></div>
          @if($errors->has('productname'))
          <div class="error text-danger">{{ $errors->first('productname') }}</div>
          @endif
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3" maxlength="1000"></textarea>
            <div class="text-danger error-description"></div>
          @if($errors->has('description'))
            <div class="error text-danger">{{ $errors->first('description') }}</div>
          @endif
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Price</label>
          <input type="text" class="form-control" step="0.01" min="0" name="price" id="price"
            placeholder="Enter product price" maxlength="10">
            <div class="text-danger error-price"></div>

            @if($errors->has('price'))
            <div class="error text-danger">{{ $errors->first('price') }}</div>
          @endif
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select class="form-select" id="category" name="category">
                <option value="">Select Category</option>
            @foreach ($categories as $category)
                @if ($category->status === "active")
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif

            @endforeach
          </select>
            <div class="text-danger error-category"></div>

          @if($errors->has('category'))
            <div class="error text-danger">{{ $errors->first('category') }}</div>
          @endif
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Product Image</label>
          <input type="file" class="form-control" name="image" id="image">
                      <div class="text-danger error-image"></div>

          @if($errors->has('image'))
            <div class="error text-danger">{{ $errors->first('image') }}</div>
          @endif
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
      </form>
    </div>
  </main>
<script src="{{ asset('js/addProduct.js') }}"></script>
</body>

</html>
