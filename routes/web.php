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
    Route::resource('subcategory', App\Http\Controllers\SubCategoryController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('orders', App\Http\Controllers\OrderController::class);
});
// KEY : MULTIPERMISSION ends

require __DIR__.'/auth.php';
