@extends('layouts.app')

@section('contenido')
    <h1>Quiénes Somos</h1>

    <a class="nav-link {{ request()->is('contacto') ? 'active' : '' }}" href="{{ url('/contacto') }}">Contacto</a>

@endsection