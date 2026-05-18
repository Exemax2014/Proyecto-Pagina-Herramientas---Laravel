<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_anterior',
        'stock',
        'ventas',
        'energia',
        'etiqueta',
        'etiqueta_clase',
        'activo',
        'categoria_id',
        'marca_id',
    ];

    protected $casts = [
        'precio'          => 'decimal:2',
        'precio_anterior' => 'decimal:2',
        'stock'           => 'integer',
        'ventas'          => 'integer',
        'activo'          => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class);
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->where('es_principal', true);
    }
}