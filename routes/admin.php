<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DasboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;



Route::group(['namespace' => 'Admin', 'middleware' => ['checkAdmin']], function () {
    Route::get('/', [DasboardController::class, 'Dashboard'])->name('admin.home');
});


Route::group(['middleware' => ['checkAdmin']], function () {
    Route::resource('products', ProductController::class, ['as' => 'admin']);
    Route::get('products/restore/{id}', [ProductController::class, 'restore'])->name('admin.products.restore');
    Route::get('products/delete/{id}', [ProductController::class, 'delete'])->name('admin.products.delete');
    Route::get('products/deleteForce/{id}', [ProductController::class, 'deleteForce'])->name('admin.products.deleteForce');
    Route::post('products/action', [ProductController::class, 'action'])->name('admin.products.action');

    
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('colors', ColorController::class, ['as' => 'admin']);
    Route::resource('sizes', SizeController::class, ['as' => 'admin']);
});

// Route::get('categories/{category}/delete', [CategoryController::class, 'delete'])->name('admin.categories.delete');