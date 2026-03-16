<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    //categories
    function addCategory()
    {
        return view('addCategory');
    }

    function storeInCategory(Request $request)
    {
        $validated = $request->validate([
            'categoryname' => ['required', 'string', 'min:5', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:categories,name'],
            'description' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive']
        ]);

        $category = new Category();
        $category->name = $request->categoryname;
        $category->description = $request->description;
        $category->status = $request->status;


        if ($category->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category Added successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category add Failed'
            ]);
        }

    }

    function categories()
    {
        $categories = Category::all();
        return view('categories', ['data' => $categories]);
    }
    function editCategory($id)
    {
        $category = Category::find($id);
        return view('editCategory', ['category' => $category]);
    }

    function updateCategory(Request $request, $id)
    {

        $validated = $request->validate([
            'categoryname' => ['required', 'string', 'min:5', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/', 'unique:categories,name,' . $id],
            'description' => ['required', 'string', 'max:1000'],
            'status' => ['required', 'in:active,inactive']
        ]);

        $category = Category::find($id);

        if (isset($category)) {
            $category->name = $request->categoryname;
            $category->description = $request->description;
            $category->status = $request->status;

            if ($category->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category Update successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category Update Failed'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Record does not exist'
            ]);
        }

    }

    function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'category deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'category not found'
        ]);
    }

    //products
    function products()
    {
        $products = Product::with('category')->get();
        // return $products;
        return view('products', ['data' => $products]);
    }

    function addProduct()
    {
        $categories = Category::all();
        return view('addProduct', ['categories' => $categories]);
    }

    function storeInProduct(Request $request)
    {
        $validated = $request->validate([
            'productname' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'exists:categories,id', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);
        // check if category exists and is active
        $category = Category::where('id', $request->category)
            ->where('status', 'active')
            ->first();

        if (!$category) {
            return response()->json([
                'errors' => [
                    'category' => ['Selected category is invalid or inactive.']
                ]
            ], 422);
        }

        $product = new Product();

        $product->name = $request->productname;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category;





        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $uuid = Str::uuid()->toString();    //random string

            $imageName = $image->getClientOriginalName();   // name with extension

            $filename = pathinfo($imageName, PATHINFO_FILENAME);    //name without extension

            $extension = $image->getClientOriginalExtension();  //extension

            $product_image = $filename . '_' . $uuid . '.' . $extension;

            $image->storeAs('products', $product_image, 'public');  //store in public / products

            $product->image = $product_image;
        }




        if ($product->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product Added successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product add Failed'
            ]);
        }

    }


    function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Product not found'
        ]);
    }

    function editProduct($id)
    {
        $product = Product::find($id);
        $categories = Category::all();
        return view('editProduct', ['product' => $product, 'categories' => $categories]);
    }

    function updateProduct(Request $request, $id)
    {

        $validated = $request->validate([
            'productname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'exists:categories,id', 'integer'],
            'image' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048']
        ]);

        $product = Product::find($id);


        if (isset($product)) {
            $product->name = $request->productname;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $request->category;
            //$product->image = $request->file('image') ? explode('/', $request->file('image')->store('public'))[1] : $product->image;

             if ($request->hasFile('image')) {

                $image = $request->file('image');

                $uuid = Str::uuid()->toString();

                $imageName = $image->getClientOriginalName();

                $filename = pathinfo($imageName, PATHINFO_FILENAME);

                $extension = $image->getClientOriginalExtension();

                $product_image = $filename . '_' . $uuid . '.' . $extension;

                $image->storeAs('products', $product_image, 'public');

                $product->image = $product_image;
            }

            if ($product->save()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'product Update successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'product Update Failed'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Record does not exist'
            ]);
        }
    }


}
