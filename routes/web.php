<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('home');
Route::view('/quienes-somos', 'pages.quienes-somos')->name('quienes-somos');
Route::view('/comercializacion', 'pages.comercializacion')->name('comercializacion');
Route::view('/contacto', 'pages.contacto')->name('contacto');
Route::view('/terminos', 'pages.terminos')->name('terminos');
Route::view('/catalogo', 'pages.catalogo')->name('catalogo');
Route::view('/consultas', 'pages.consultas')->name('consultas');
Route::view('/login', 'pages.login')->name('login');
Route::view('/registro', 'pages.registro')->name('registro');
