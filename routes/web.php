<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestController;

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
});
