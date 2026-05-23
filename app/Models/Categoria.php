<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'imagen_url',
        'mostrar_en_inicio',
        'orden_inicio',
    ];

    protected $casts = [
        'mostrar_en_inicio' => 'boolean',
        'orden_inicio' => 'integer',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
