<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booker\BookerHomepageController;
use App\Http\Controllers\Crew\CrewHomepageController;
use App\Http\Controllers\Booker\CheckExcursionsForDateAndTypeController;
use App\Http\Controllers\Booker\BookController;
use App\Http\Controllers\Booker\BookerRegularExcursionsReservationsController;
use App\Http\Controllers\Booker\BookerPrivateExcursionsReservationsController;
use App\Http\Controllers\Booker\PrivateExcursionsReservationController;
use App\Http\Controllers\Booker\BookerPdfReportController;
use App\Http\Controllers\Booker\DownloadBookerPdfReport;
use App\Http\Controllers\Booker\UpdateProfileDataController;
use App\Http\Controllers\Booker\ReservationCancelationController;
use App\Http\Controllers\Crew\ExcursionStationReservationsController;
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

// BOOKER ROUTES
Route::domain(config('app.booker_subdomain'))
->middleware(['auth', 'role:Booker'])
->group(function () {
    Route::get('/', BookerHomepageController::class)->name('booker.homepage');
    Route::post('/check-excursions-on-date', CheckExcursionsForDateAndTypeController::class);
    Route::get('/moje-rezervacije/redovni', [BookerRegularExcursionsReservationsController::class, 'index'])->name('my-reservations.regular.index');
    Route::get('/moje-rezervacije/redovni/{reservation}', [BookerRegularExcursionsReservationsController::class, 'show'])->name('my-reservations.regular.show');
    Route::get('/moje-rezervacije/privatni', [BookerPrivateExcursionsReservationsController::class, 'index'])->name('my-reservations.private.index');
    Route::get('/moje-rezervacije/privatni/{reservation}', [BookerPrivateExcursionsReservationsController::class, 'show'])->name('my-reservations.private.show');
    Route::get('privatne-ture', [PrivateExcursionsReservationController::class, 'index'])->name('private-excursions.index');
    Route::get('privatne-ture/nova', [PrivateExcursionsReservationController::class, 'create'])->name('private-excursions.create');
    Route::post('private-tours', [PrivateExcursionsReservationController::class, 'store'])->name('private-excursions.store');
    Route::post('/book', BookController::class);
    Route::post('/cancel-reservation/{reservation}', ReservationCancelationController::class);
    Route::get('podaci/izmjena', [UpdateProfileDataController::class, 'edit'])->name('booker.profile.edit');
    Route::patch('profile/update', [UpdateProfileDataController::class, 'update'])->name('booker.profile.update');
});

// CREW ROUTES
Route::domain(config('app.crew_subdomain'))
->middleware(['auth', 'role:Crew Member'])
->group(function () {
    Route::get('/', CrewHomepageController::class)->name('crew.homepage');
    Route::get('/{excursion}/{station}', ExcursionStationReservationsController::class)->name('crew.excursion-station.details');
});

// AUTH    
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});


//TEST ROUTE
Route::get('/booker', BookerPdfReportController::class);
//ACTUAL DOWNLOAD ROUTE
Route::get('/booker/pdf-download-regular-excursions-detailed', [DownloadBookerPdfReport::class, 'indexRegularDetailed'])->name('booker-pdf-download-regular-excursions-detailed');
Route::get('/booker/pdf-download-regular-excursions-simple', [DownloadBookerPdfReport::class, 'indexRegularSimple'])->name('booker-pdf-download-regular-excursions-simple');
Route::get('/booker/pdf-download-private-excursions', [DownloadBookerPdfReport::class, 'indexPrivate'])->name('booker-pdf-download-private-excursions');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
