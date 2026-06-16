<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domicilio extends Model
{
    protected $fillable = [
        'usuario_id',
        'calle',
        'numero',
        'piso_departamento',
        'ciudad',
        'provincia',
        'codigo_postal',
        'referencia',
        'es_principal',
        'activo',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
