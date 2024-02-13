<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// KEY : MULTIPERMISSION starts
Route::group(['middleware' => ['auth']], function() {
// Route::group(function() {
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('category', App\Http\Controllers\ParentCategoryController::class);
    Route::post('category-status',[App\Http\Controllers\ParentCategoryController::class,'changeStatus']);
    Route::get('search',[App\Http\Controllers\ParentCategoryController::class,'filterCategory'])->name('category.search');
    Route::resource('subcategory', App\Http\Controllers\SubCategoryController::class);
    Route::post('subcategory-status',[App\Http\Controllers\SubCategoryController::class,'changeStatus']);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::post('product-status',[App\Http\Controllers\ProductController::class,'changeStatus']);
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    // Ajax Web
    Route::post('getsubcategories', [App\Http\Controllers\ProductController::class,'getSubCategoryByParentCatId']);
});
// KEY : MULTIPERMISSION ends

require __DIR__.'/auth.php';
