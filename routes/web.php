<?php

use Illuminate\Support\Facades\Route;

// German is the default locale without prefix; SetLocale switches on the /en prefix.
Route::view('/', 'home')->name('home');
Route::view('/en', 'home')->name('en.home');
