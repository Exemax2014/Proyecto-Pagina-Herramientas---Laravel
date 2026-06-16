<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Producto extends Model
{
    protected static ?bool $etiquetasDisponiblesCache = null;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'precio_anterior',
        'stock',
        'ventas',
        'etiqueta',
        'etiqueta_clase',
        'etiqueta_id',
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

    public function etiquetaManual(): BelongsTo
    {
        return $this->belongsTo(Etiqueta::class, 'etiqueta_id');
    }

    public static function etiquetasDisponibles(): bool
    {
        if (static::$etiquetasDisponiblesCache !== null) {
            return static::$etiquetasDisponiblesCache;
        }

        static::$etiquetasDisponiblesCache = Schema::hasTable('etiquetas')
            && Schema::hasColumn('productos', 'etiqueta_id');

        return static::$etiquetasDisponiblesCache;
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagen::class);
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImagen::class)->where('es_principal', true);
    }

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function tieneOfertaActiva(): bool
    {
        return $this->precio_anterior !== null
            && (float) $this->precio_anterior > (float) $this->precio;
    }

    public function getPorcentajeDescuentoAttribute(): ?int
    {
        if (! $this->tieneOfertaActiva()) {
            return null;
        }

        $precioAnterior = (float) $this->precio_anterior;
        $precioActual = (float) $this->precio;

        if ($precioAnterior <= 0) {
            return null;
        }

        return (int) round((($precioAnterior - $precioActual) / $precioAnterior) * 100);
    }

    public function getEtiquetaManualNombreAttribute(): ?string
    {
        if (! static::etiquetasDisponibles()) {
            return $this->etiqueta ?: null;
        }

        return $this->etiquetaManual?->nombre ?: ($this->etiqueta ?: null);
    }

    public function getEtiquetaManualColorAttribute(): ?string
    {
        if (! static::etiquetasDisponibles()) {
            return $this->etiqueta ? '#111111' : null;
        }

        return $this->etiquetaManual?->color ?: ($this->etiqueta ? '#111111' : null);
    }

    public function getEtiquetaOfertaTextoAttribute(): ?string
    {
        if (! $this->tieneOfertaActiva()) {
            return null;
        }

        return $this->porcentaje_descuento
            ? $this->porcentaje_descuento . '% OFF'
            : 'Oferta';
    }

    public function getEtiquetaOfertaColorAttribute(): string
    {
        return Etiqueta::OFERTA_COLOR;
    }

    public function getEtiquetasVisualesAttribute(): array
    {
        $etiquetas = [];

        if ($this->tieneOfertaActiva()) {
            $etiquetas[] = [
                'texto' => $this->etiqueta_oferta_texto,
                'color' => $this->etiqueta_oferta_color,
                'texto_color' => '#ffffff',
                'tipo' => 'oferta',
            ];
        }

        $manualName = $this->etiqueta_manual_nombre;

        if ($manualName && strcasecmp($manualName, 'Oferta') !== 0) {
            $etiquetas[] = [
                'texto' => $manualName,
                'color' => $this->etiqueta_manual_color ?: '#111111',
                'texto_color' => static::etiquetasDisponibles()
                    ? ($this->etiquetaManual?->texto_color ?: '#ffffff')
                    : '#ffffff',
                'tipo' => 'manual',
            ];
        }

        return $etiquetas;
    }
}
