<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}