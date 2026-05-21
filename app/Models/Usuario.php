<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'dni',
        'telefono',
        'direccion',
        'ciudad',
        'provincia',
        'codigo_postal',
        'role',
        'activo',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'activo' => 'boolean',
    ];
}