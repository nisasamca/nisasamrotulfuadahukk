<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\UserController;

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

Route::resource('users', UserController::class);

Route::middleware(['auth'])->group(function () {
    // Route untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');

        Route::get('/admin/items', [ItemController::class, 'index'])->name('admin.items');   
        
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [UserController::class, 'store']);

        Route::post('/admin/users/{id}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.resetPassword');

        Route::get('/admin/profile', function () {
            return view('admin.profile');
        })->name('admin.profile');
    });

    // Route untuk Staff
Route::middleware(['role:staff'])->group(function () {

    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');

    Route::get('/staff/items', function () {
        $items = \App\Models\Item::with('category')->get();
        $categories = \App\Models\Category::all();
        return view('staff.items', compact('items', 'categories'));
    })->name('staff.items');

    // LENDING
    Route::get('/staff/lending', [LendingController::class, 'index'])
        ->name('staff.lending');

    Route::get('/staff/lending/export', [LendingController::class, 'export'])
        ->name('staff.lending.export');

    Route::post('/staff/lending/store', [LendingController::class, 'store'])
        ->name('staff.lending.store');

    Route::patch('/staff/lending/return/{id}', [LendingController::class, 'return'])
        ->name('staff.lending.return');

    Route::delete('/staff/lending/delete/{id}', [LendingController::class, 'destroy'])
        ->name('staff.lending.delete');

    Route::post('/staff/lending/{id}/pdf', [LendingController::class, 'exportPdf'])
        ->name('staff.lending.pdf');

    Route::get('/staff/users/edit', function () {
        return view('staff.users_edit');
    })->name('staff.users.edit');

    Route::post('/staff/users/update', [UserController::class, 'updateProfile'])->name('staff.profile.update');

});

});