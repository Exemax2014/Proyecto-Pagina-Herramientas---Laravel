<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Usuario extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'dni',
        'telefono',
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

    public function domicilios(): HasMany
    {
        return $this->hasMany(Domicilio::class);
    }

    public function domicilioPrincipal(): ?Domicilio
    {
        if ($this->relationLoaded('domicilios')) {
            /** @var Collection<int, Domicilio> $domicilios */
            $domicilios = $this->getRelation('domicilios');

            return $domicilios->firstWhere('es_principal', true) ?: $domicilios->first();
        }

        return $this->domicilios()
            ->orderByDesc('es_principal')
            ->latest('id')
            ->first();
    }
}
