<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booker\BookerHomepageController;
use App\Http\Controllers\Booker\CheckExcursionsForDateAndTypeController;
use App\Http\Controllers\Booker\BookController;
use App\Http\Controllers\BookerPdfReportController;
use App\Http\Controllers\DownloadBookerPdfReport;
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


Route::domain('booker.petar-booking.test')->group(function () {
    Route::get('/', BookerHomepageController::class);
    Route::post('/check-excursions-on-date', CheckExcursionsForDateAndTypeController::class);
    Route::post('/book', BookController::class);
});

Route::get('/', function () {
    return view('welcome');
});


//TEST ROUTE
Route::get('/booker', BookerPdfReportController::class);
//ACTUAL DOWNLOAD ROUTE
Route::get('/booker/pdf-download', DownloadBookerPdfReport::class)->name('booker-pdf-download');