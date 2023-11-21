<?php

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCouponController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteProductController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Services\Newsletter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::get('/mail', [\App\Http\Controllers\MailController::class, 'sendMail']);

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/products/{product:slug}/comments', [CommentController::class, 'store'])->name('comment.add');

Route::post('newsletter', NewsletterController::class)->name('newsletter');

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteProductController::class, 'index'])->name('favorites');
    Route::delete('/favorites/{favorite}', [FavoriteProductController::class, 'destroy'])->name('favorites.delete');
    Route::post('/products/{product:slug}/favorites', [FavoriteProductController::class, 'store'])->name('favorites.store');


    Route::get('/cart', [CartItemController::class, 'index'])->name('cart');
    Route::post('/products/{product:slug}/cart', [CartItemController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}/increase', [CartItemController::class, 'increaseQuantity'])->name('cart.increase');
    Route::patch('/cart/{cartItem}/decrease', [CartItemController::class, 'decreaseQuantity'])->name('cart.decrease');
    Route::delete('/cart/{cartItem}', [CartItemController::class, 'destroy'])->name('cart.delete');
    Route::delete('/cart', [CartItemController::class, 'destroyAll'])->name('cart.delete.all');

    Route::post('/coupon', [CouponController::class, 'store'])->name('coupon.store');
    Route::delete('/coupon', [CouponController::class, 'destroy'])->name('coupon.delete');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('can:admin')->group(function () {
    Route::get('/admin/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('/admin/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/admin/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::patch('/admin/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{product}', [AdminProductController::class, 'destroy'])->name('products.delete');

    Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::get('/admin/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/admin/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/admin/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::patch('/admin/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.delete');

    Route::get('/admin/coupons', [AdminCouponController::class, 'index'])->name('admin.coupons');
    Route::get('/admin/coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/admin/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/admin/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::patch('/admin/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/admin/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
