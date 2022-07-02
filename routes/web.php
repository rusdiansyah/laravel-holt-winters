<?php

use App\Http\Controllers\ChartByPriceController;
use App\Http\Controllers\HoltWintersController;
use App\Http\Controllers\RiceByPriceController;
use App\Http\Controllers\RiceByStockController;
use App\Http\Controllers\RiceByStockImportController;
use App\Http\Controllers\RiceTypeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('/rice_type', RiceTypeController::class);

    Route::resource('/users', UsersController::class);

    Route::resource('/rice_by_price', RiceByPriceController::class);

    Route::resource('/rice_by_stock', RiceByStockController::class);

    Route::resource('/rice_by_stock_import', RiceByStockImportController::class);

    Route::resource('/holt_winters', HoltWintersController::class);

    Route::resource('/chart_by_price', ChartByPriceController::class);
});
