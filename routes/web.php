<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

// route client
Route::get('/', [HomeController::class, 'index'])->name('client.home');


Route::get('product/{category_id}', [ClientProductController::class, 'index'])->name('client.products.index');
Route::get('product-detail/{id}', [ClientProductController::class, 'show'])->name('client.products.show');

Route::middleware('auth')->group(function(){
    Route::post('add-to-cart', [CartController::class, 'store'])->name('client.carts.add');
    Route::get('carts', [CartController::class, 'index'])->name('client.carts.index');
    Route::post('update-quantity-product-in-cart/{cart_product_id}', [CartController::class, 'updateQuantityProduct'])->name('client.carts.update_product_quantity');
    Route::post('remove-product-in-cart/{cart_product_id}', [CartController::class, 'removeProductInCart'])->name('client.carts.remove_product');

    Route::post('apply-coupon', [CartController::class, 'applyCoupon'])->name('client.carts.apply_coupon');

    Route::get('checkout', [CartController::class, 'checkout'])->name('client.checkout.index')->middleware('user.can_checkout_cart');
    Route::post('process-checkout', [CartController::class, 'processCheckout'])->name('client.checkout.proccess')->middleware('user.can_checkout_cart');

    Route::get('list-orders', [OrderController::class, 'index'])->name('client.orders.index');

    Route::post('orders/cancel/{id}', [OrderController::class, 'cancel'])->name('client.orders.cancel');

});


Auth::routes();


// route admin
Route::middleware('auth')->group(function(){

    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('coupons', CounponController::class);
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('update-status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update_status');
});







