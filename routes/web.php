<?php

use App\Http\Controllers\HomeController;
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

Route::controller(HomeController::class)->group(function () {
    Route::view('/', 'home')->name('home');
    Route::post('store', 'store')->name('store');
    Route::get('list', 'list')->name('list');
    Route::get('download-bank-records', 'downloadBankRecords')->name('download-bank-records');
    Route::get('download-order-payment-records', 'downloadOrderPaymentRecords')->name('download-order-payment-records');
    Route::post('ignore-record', 'ignoreRecord')->name('ignore-record');
});
