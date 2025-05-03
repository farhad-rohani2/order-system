<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

require __DIR__ . '/auth.php';

Route::view('/admin/orders', 'admin.orders')->middleware(['auth', 'admin', 'ensureSanctumToken'])->name('admin.orders');
Route::view('/user/orders', 'user.orders')->middleware(['auth', 'ensureSanctumToken'])->name('user.orders');
Route::view('/products', 'products.index')->middleware(['auth', 'ensureSanctumToken'])->name('products.index');

