<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
Route::get('/', function () {
    return view('welcome');
});
Route::middleware('checklogin')->group(function(){

    // Route::get('/login',[UserController::class,'loginPage']);
    Route::view('/login','login')->name('login');
    Route::view('/signup','signup')->name('signup');

    Route::post('/login',[UserController::class,'login']);
    Route::post('/signup',[UserController::class,'signup']);

    Route::get('/home',[UserController::class,'home'])->name('home');

});

Route::get('/logout',[UserController::class,'logout']);

Route::middleware('checklogin')->group(function(){

    // Route::get('/home',[UserController::class,'home'])->name('home');
    Route::get('/profile',[UserController::class,'profile']);
    Route::put('/profile/update/{id}',[UserController::class,'updateProfile']);

    Route::controller(HomeController::class)->group(function(){
        //categories
        Route::get('/categories','categories');
        Route::get('/categories/add','addCategory')->name('addCategory');
        Route::post('/categories/add','storeInCategory');

        Route::get('categories/edit/{id}','editCategory');
        Route::put('/categories/update/{id}','updateCategory');
        Route::delete('/categories/delete/{id}','deleteCategory');

        //products
        Route::get('/products','products');
        Route::get('/products/add','addProduct')->name('addProducts');
        Route::post('/products/add','storeInProduct');

        Route::get('/products/edit/{id}','editProduct');
        Route::put('/products/update/{id}','updateProduct');
        Route::delete('/products/delete/{id}','deleteProduct');

    });

});
