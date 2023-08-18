<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CartController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/tim-kiem', [WelcomeController::class, 'searchClient'])->name('searchClient');

Route::group(['prefix' => 'san-pham'], function () {
    Route::get('/{slug}.html', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/{slug}', [ProductController::class, 'productByCategory'])->name('product.byCategory');
    Route::get('/color/{slug}', [ProductController::class, 'productByColor'])->name('product.ByColor');
    Route::get('/size/{slug}', [ProductController::class, 'productBySize'])->name('product.BySize');

});

// cart
Route::group(['prefix' => 'gio-hang'], function () {

    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/', [CartController::class, 'add'])->name('cart.add');
    Route::get('/buyNow/{id}', [CartController::class, 'buyNow'])->name('cart.buyNow');
    Route::post('/update', [CartController::class, 'updateAjax'])->name('cart.updateAjax');
    Route::get('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

});


Route::group(['prefix' => 'admin',], function () {
    include 'admin.php';
});