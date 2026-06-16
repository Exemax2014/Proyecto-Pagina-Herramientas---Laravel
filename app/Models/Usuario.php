<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Usuario extends Model
{
    // Estos campos se mantienen solo como compatibilidad temporal.
    // La fuente real de domicilio del sistema es la tabla `domicilios`.
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

    public function ensureLegacyDomicilioPrincipal(): ?Domicilio
    {
        $domicilioPrincipal = $this->domicilioPrincipal();

        if ($domicilioPrincipal) {
            return $domicilioPrincipal;
        }

        $legacyAddress = $this->legacyAddressData();

        foreach (['calle', 'numero', 'ciudad', 'provincia'] as $field) {
            if ($legacyAddress[$field] === '') {
                return null;
            }
        }

        return $this->domicilios()->create([
            'calle' => $legacyAddress['calle'],
            'numero' => $legacyAddress['numero'],
            'piso_departamento' => null,
            'ciudad' => $legacyAddress['ciudad'],
            'provincia' => $legacyAddress['provincia'],
            'codigo_postal' => $legacyAddress['codigo_postal'] ?: null,
            'referencia' => null,
            'es_principal' => true,
        ]);
    }

    public function syncLegacyAddressFromDomicilio(?Domicilio $domicilio): void
    {
        $this->forceFill([
            'direccion' => $domicilio
                ? trim($domicilio->calle . ' ' . $domicilio->numero)
                : null,
            'ciudad' => $domicilio?->ciudad,
            'provincia' => $domicilio?->provincia,
            'codigo_postal' => $domicilio?->codigo_postal,
        ])->saveQuietly();
    }

    protected function legacyAddressData(): array
    {
        $direccion = trim((string) $this->direccion);

        if ($direccion === '') {
            return [
                'calle' => '',
                'numero' => '',
                'ciudad' => trim((string) $this->ciudad),
                'provincia' => trim((string) $this->provincia),
                'codigo_postal' => trim((string) $this->codigo_postal),
            ];
        }

        if (preg_match('/^(.*?)(?:\s+(\d+[A-Za-z0-9\-\/]*))$/', $direccion, $matches)) {
            $calle = trim($matches[1]);
            $numero = trim($matches[2]);
        } else {
            $calle = $direccion;
            $numero = '';
        }

        return [
            'calle' => $calle,
            'numero' => $numero,
            'ciudad' => trim((string) $this->ciudad),
            'provincia' => trim((string) $this->provincia),
            'codigo_postal' => trim((string) $this->codigo_postal),
        ];
    }
}
