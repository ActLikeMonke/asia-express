<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// German is the default locale without prefix; SetLocale switches on the /en prefix.
Route::get('/', HomeController::class)->name('home');
Route::get('/en', HomeController::class)->name('en.home');
