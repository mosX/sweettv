<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PusherController;
use App\Http\Controllers\VideoController;

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

Route::get('/', function () {
    return view('index');
});

Route::post('/videos', [VideoController::Class,'save']);
Route::get('/videos', [VideoController::Class,'getData']);
Route::delete('/videos/{id}', [VideoController::Class,'remove']);