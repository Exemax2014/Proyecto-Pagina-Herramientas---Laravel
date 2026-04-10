<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.inicio');
});

Route::get('/quienes-somos', function () {
    return view('pages.quienes');
});

Route::get('/comercializacion', function () {
    return view('pages.comercializacion');
});

Route::get('/contacto', function () {
    return view('pages.contacto');
});

Route::get('/terminos', function () {
    return view('pages.terminos');
});
