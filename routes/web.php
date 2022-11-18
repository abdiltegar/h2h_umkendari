<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestController;
use App\Http\Controllers\BsiController;
use App\Http\Controllers\BmiController;

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

Route::controller(RestController::class)->group(function () {
    Route::get('test', 'index')->name('test');

    Route::get('/inquiry', 'Inquiry')->name('inquiry');
    Route::get('/payment', 'Payment')->name('payment');
});

Route::controller(BsiController::class)->group(function () {
    Route::get('/bsi', 'Index')->name('bsi.index');
    Route::post('/bsi', 'Index')->name('bsi.index.post');

    Route::post('/bsi/generate_checksum', 'Generate')->name('bsi.generate');
});

Route::controller(BmiController::class)->group(function () {
    Route::get('/bmi', 'Index')->name('bmi.index');
    Route::post('/bmi', 'Index')->name('bmi.index.post');

    Route::post('/bmi/generate_checksum', 'Generate')->name('bmi.generate');
});