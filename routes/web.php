<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booker\BookerHomepageController;
use App\Http\Controllers\Booker\CheckExcursionsForDateAndTypeController;
use App\Http\Controllers\Booker\BookController;
use App\Http\Controllers\Booker\BookerReservationsController;
use App\Http\Controllers\Booker\BookerPdfReportController;
use App\Http\Controllers\Booker\DownloadBookerPdfReport;
use App\Http\Controllers\Booker\ReservationCancelationController;
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
    Route::get('/', BookerHomepageController::class)->middleware('auth');
    Route::post('/check-excursions-on-date', CheckExcursionsForDateAndTypeController::class)->middleware('auth');
    Route::get('/moje-rezervacije', [BookerReservationsController::class, 'index'])->name('my-reservations.index')->middleware('auth');
    Route::get('/moje-rezervacije/{reservation}', [BookerReservationsController::class, 'show'])->name('my-reservations.show')->middleware('auth');
    Route::post('/book', BookController::class)->middleware('auth');
    Route::post('/cancel-reservation/{reservation}', ReservationCancelationController::class)->middleware('auth');
});

Route::domain('booker.petar-booking.test')->group(function () {
    require __DIR__.'/auth.php';
});


Route::get('/', function () {
    return view('welcome');
});


//TEST ROUTE
Route::get('/booker', BookerPdfReportController::class);
//ACTUAL DOWNLOAD ROUTE
Route::get('/booker/pdf-download', DownloadBookerPdfReport::class)->name('booker-pdf-download');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
