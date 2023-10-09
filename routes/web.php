<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;
use App\Http\Controllers\UserController;

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


Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/',[HomeController::class,'index']);
    Route::post('/search',[HomeController::class,'search'])->name('search');
    Route::get('/search_history',[HomeController::class,'search_history'])->name('search_history');
   
});


