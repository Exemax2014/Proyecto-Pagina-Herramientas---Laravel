<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Pedido extends Model
{
    protected $fillable = [
        'usuario_id',
        'codigo',
        'nombre_completo',
        'email',
        'telefono',
        'direccion',
        'ciudad',
        'provincia',
        'codigo_postal',
        'dni',
        'observaciones',
        'metodo_pago',
        'modo_entrega',
        'subtotal',
        'envio',
        'descuento',
        'total',
        'estado',
        'fecha_confirmacion',
        'fecha_preparando',
        'fecha_enviado',
        'fecha_entregado',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'envio' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_confirmacion' => 'datetime',
        'fecha_preparando' => 'datetime',
        'fecha_enviado' => 'datetime',
        'fecha_entregado' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function getCodigoVisibleAttribute(): string
    {
        if (filled($this->codigo)) {
            return $this->codigo;
        }

        return 'HF-' . Str::padLeft((string) $this->id, 6, '0');
    }

    public function asegurarCodigoVisible(): string
    {
        if (! filled($this->codigo) && $this->exists) {
            $this->forceFill([
                'codigo' => $this->codigo_visible,
            ])->saveQuietly();
        }

        return $this->codigo_visible;
    }
}
