<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PacketController;
use App\Http\Controllers\PacketProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => false,
        'message' => 'Route not found'
    ], 404);
});

/* Auth */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('jwt.auth');

Route::middleware('role:admin')->group(function () {
    /* Order */
    Route::prefix('order')->group(function () {
        Route::get('/datatables', [OrderController::class, 'datatables']);
        Route::get('/order-report-day', [OrderController::class, 'orderReportDay']);
        Route::get('/pending-report-total', [OrderController::class, 'pendingReportTotal']);
        Route::get('/confirmed-report-total', [OrderController::class, 'confirmedReportTotal']);
    });

    /* Category */
    Route::prefix('category')->group(function () {
        Route::get('/datatables', [CategoryController::class, 'datatables']);
        Route::post('/{category}', [CategoryController::class, 'update']);
    });

    /* Packet */
    Route::prefix('packet')->group(function () {
        Route::get('/datatables', [PacketController::class, 'datatables']);
        Route::post('/', [PacketController::class, 'store']);
        Route::get('/product/{packet}', [PacketController::class, 'packetProducts']);
        Route::post('/product/{packet}', [PacketController::class, 'saveProduct']);
        Route::delete('/product/{packetproduct}', [PacketController::class, 'deletePacketProduct']);
        Route::post('/{packet}', [PacketController::class, 'update']);
        Route::delete('/{packet}', [PacketController::class, 'delete']);
    });

    /* Packet Product */
    Route::prefix('packet-product')->group(function () {
        Route::get('/datatables/{packet}', [PacketProductController::class, 'datatables']);
        Route::post('/{packet}', [PacketProductController::class, 'store']);
        Route::delete('/{packetproducts}', [PacketProductController::class, 'delete']);
    });

    /* Product */
    Route::prefix('product')->group(function () {
        Route::get('/datatables', [ProductController::class, 'datatables']);
        Route::get('/select2', [ProductController::class, 'select2']);
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'delete']);
    });

    /* Customer */
    Route::get('/customer/report-total', [UserController::class, 'reportTotal']);
});

/* Category */
Route::get('/categories', [CategoryController::class, 'all']);

/* Packet */
Route::get('/packet/{slug}', [PacketController::class, 'getByCategory']);

/* Product */
Route::get('/product/{slug}', [ProductController::class, 'getByPacket']);

/* Checkout */
Route::post('/checkout', [OrderController::class, 'checkout']);

/* Order History */
Route::get('/order/history', [OrderController::class, 'history']);
