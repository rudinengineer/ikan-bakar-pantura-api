<?php

use App\Http\Controllers\Administrator\AppSettingController;
use App\Http\Controllers\Administrator\AuthController;
use App\Http\Controllers\Administrator\CategoryController;
use App\Http\Controllers\Administrator\HomeController;
use App\Http\Controllers\Administrator\OrderController;
use App\Http\Controllers\Administrator\OrderItemsController;
use App\Http\Controllers\Administrator\PacketController;
use App\Http\Controllers\Administrator\PacketProductController;
use App\Http\Controllers\Administrator\ProductController;
use App\Http\Controllers\Administrator\ProfileController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\StoreController;
use App\Http\Controllers\Administrator\UserAccessController;
use App\Http\Controllers\Administrator\UserAccessItemController;
use App\Http\Controllers\Administrator\UserController;
use Illuminate\Support\Facades\Route;

/* 404 Not Found */

Route::get('notfound', function () {
    return view('pages.notfound');
})->name('notfound');

/* Auth Login */
Route::middleware('guest')->prefix('login')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    /* Main Layout */
    Route::get('/', [HomeController::class, 'index']);

    /* Logout */
    Route::get('/auth/logout', [AuthController::class, 'logout'])->name('logout');

    /* Dashboard */
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    /* Category */
    Route::name('category.')->prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/select2', [CategoryController::class, 'select2'])->name('select2');
        Route::get('/datatables', [CategoryController::class, 'datatables'])->name('datatables');
        Route::get('/{category}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    });

    /* Product */
    Route::name('product.')->prefix('product')->group(function () {
        Route::get('/fetch-datatables', [ProductController::class, 'datatables'])->name('datatables');
        Route::get('select2', [ProductController::class, 'select2'])->name('select2');

        /* Route Resource */
        Route::resource('/', ProductController::class)->parameters([
            '' => 'product'
        ]);
    });

    /* Store */
    Route::name('store.')->prefix('store')->group(function () {
        Route::get('/fetch-datatables', [StoreController::class, 'datatables'])->name('datatables');
        Route::get('select2', [StoreController::class, 'select2'])->name('select2');

        /* Route Resource */
        Route::resource('/', StoreController::class)->parameters([
            '' => 'store'
        ]);
    });

    /* Order */
    Route::name('order.')->prefix('order')->group(function () {
        Route::get('/fetch-datatables', [OrderController::class, 'datatables'])->name('datatables');
        Route::get('select2', [OrderController::class, 'select2'])->name('select2');

        /* Route Resource */
        Route::post('/{order}/update-payment-total', [OrderController::class, 'updatePaymentTotal'])->name('update-payment-total');
        Route::post('/{order}/confirmed', [OrderController::class, 'confirmOrder'])->name('update-payment-total');
        Route::resource('/', OrderController::class)->parameters([
            '' => 'order'
        ]);
    });

    /* Order Items */
    Route::name('order-items.')->prefix('order-items')->group(function () {
        Route::get('/{order}/fetch-datatables', [OrderItemsController::class, 'datatables'])->name('datatables');

        /* Route Resource */
        Route::get('/{order}', [OrderItemsController::class, 'index']);
        Route::resource('/', OrderItemsController::class)->parameters([
            '' => 'orderitems'
        ]);
        Route::get('/{order}/create', [OrderItemsController::class, 'create'])->name('create');
        Route::post('/{order}/create', [OrderItemsController::class, 'store'])->name('store');
    });

    /* Packet */
    Route::name('packet.')->prefix('packet')->group(function () {
        Route::get('/fetch-datatables', [PacketController::class, 'datatables'])->name('datatables');
        Route::get('select2', [PacketController::class, 'select2'])->name('select2');

        /* Route Resource */
        Route::resource('/', PacketController::class)->parameters([
            '' => 'packet'
        ]);
    });

    /* Packet Product */
    Route::name('packet-product.')->prefix('packet-product')->group(function () {
        Route::get('/{packet}/fetch-datatables', [PacketProductController::class, 'datatables'])->name('datatables');
        Route::get('/{packet}', [PacketProductController::class, 'index']);

        Route::prefix('/{packet}/create')->group(function () {
            Route::get('/', [PacketProductController::class, 'create'])->name('create');
            Route::post('/', [PacketProductController::class, 'store'])->name('store');
        });

        Route::delete('/{packetproducts}/delete', [PacketProductController::class, 'destroy'])->name('destroy');
    });

    /* Profile */
    Route::name('profile.')->prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/', [ProfileController::class, 'update'])->name('update');
    });

    /* Role */
    Route::name('role.')->prefix('role')->group(function () {
        Route::get('/fetch-datatables', [RoleController::class, 'datatables'])->name('datatables');
        Route::get('select2', [RoleController::class, 'select2'])->name('select2');

        /* Route Resource */
        Route::resource('/', RoleController::class)->parameters([
            '' => 'role'
        ]);
    });

    /* User Management */
    Route::name('user-management.')->prefix('user-management')->group(function () {
        Route::get('/fetch-datatables', [UserController::class, 'datatables'])->name('datatables');

        /* Route Resource */
        Route::resource('/', UserController::class)
            ->parameters([
                '' => 'user'
            ]);
    });

    /* User Access */
    Route::name('user-access.')->prefix('user-access/{role}')->group(function () {
        Route::get('/fetch-datatables', [UserAccessController::class, 'datatables'])->name('datatables');
        Route::get('/', [UserAccessController::class, 'edit'])->name('edit');
        Route::post('/edit', [UserAccessController::class, 'update'])->name('update');
        Route::post('/update-all', [UserAccessController::class, 'update_all'])->name('update-all');
    });

    /* User Access Item */
    Route::name('user-access-item.')->prefix('user-access-item')->group(function () {
        Route::get('/fetch-datatables', [UserAccessItemController::class, 'datatables'])->name('datatables');

        /* Route Resource */
        Route::resource('/', UserAccessItemController::class)->parameters([
            '' => 'useraccessitem'
        ]);
    });

    /* App Setting */
    Route::name('app-setting.')->prefix('app-setting')->group(function () {
        Route::get('/', [AppSettingController::class, 'index'])->name('index');
        Route::post('/', [AppSettingController::class, 'update'])->name('update');
    });
});
