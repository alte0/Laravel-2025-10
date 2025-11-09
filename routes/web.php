<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('.pages.main');
});

Route::get('/profile', function () {
    return view('pages.profile');
});

Route::get('/register', function () {
    return view('pages.register');
});

Route::get('/static', function () {
    return view('pages.static');
});
