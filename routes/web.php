<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::prefix('dashboard/seller')
    ->middleware('role:seller')
    ->name('dashboard.sellers.')
    ->group(function () {
        Route::get('/', [AuthController::class, 'dashboardSellerHome'])->name('etalase');
        Route::post('/add-product', [AuthController::class, 'AddProduct']);
        Route::get('/detail-product/{id}', [AuthController::class, 'DetailProduct']);
        Route::delete('/delete-product/{id}', [AuthController::class, 'DeleteProduct']);
        Route::post('/update-product/{id}', [AuthController::class, 'UpdateProduct']);
        Route::get('/data', [AuthController::class, 'dashboardSellerData'])->name('data');
        Route::get('/riwayat', [AuthController::class, 'dashboardSellerRiwayat'])->name('riwayat');
        Route::post('/update-status/{id}', [AuthController::class, 'UpdateStatusSeller']);
    });


Route::prefix('dashboard/customer')
    ->middleware('role:customer')
    ->name('dashboard.customers.')
    ->group(function () {
        Route::get('/', [AuthController::class, 'dashboardCustomerHome'])->name('home');
        Route::post('/add-transaksi', [AuthController::class, 'AddTransaksi']);
        Route::post('/get-harga', [AuthController::class, 'getHarga']);
        Route::get('/riwayat', [AuthController::class, 'dashboardCustomerRiwayat'])->name('riwayat');
        Route::post('/update-status/{id}/{total}/{barang_id}', [AuthController::class, 'UpdateStatusCustomer']);
    });


Route::get('/database/image/{filename}', function ($filename) {
    $path = base_path('database/images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return Response::make(file_get_contents($path), 200, [
        'Content-Type' => $mimeType
    ]);
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => redirect('/login'));
