<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/quienes-somos', function () {
    return view('pages.quienes-somos');
})->name('quienes-somos');

Route::get('/comercializacion', function () {
    return view('pages.comercializacion');
})->name('comercializacion');

Route::get('/contacto', function () {
    return view('pages.contacto');
})->name('contacto');

Route::get('/terminos', function () {
    return view('pages.terminos');
})->name('terminos');

Route::get('/catalogo', function () {
    return view('pages.catalogo');
})->name('catalogo');

Route::get('/consultas', function () {
    return view('pages.consultas');
})->name('consultas');

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::get('/registro', function () {
    return view('pages.registro');
})->name('registro');