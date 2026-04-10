<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
Route::get('/', function () {
    return view('login');
});
Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/categories/export', [CategoryController::class, 'export'])->name('categories.export');
Route::resource('categories', CategoryController::class);

Route::resource('items', ItemController::class);
Route::get('items-export', [ItemController::class, 'export'])->name('items.export');

Route::middleware(['auth'])->group(function () {
    // Route untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');

        Route::get('/admin/items', [ItemController::class, 'index'])->name('admin.items');   
        
        Route::get('/admin/users', function () {
            return view('admin.users');
        })->name('admin.users');

        Route::get('/admin/profile', function () {
            return view('admin.profile');
        })->name('admin.profile');
    });

    // Route untuk Staff
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', function () {
            return view('staff.dashboard');
        })->name('staff.dashboard');
    });
});

