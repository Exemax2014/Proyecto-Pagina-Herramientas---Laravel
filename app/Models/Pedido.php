<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'observaciones',
        'metodo_pago',
        'subtotal',
        'envio',
        'descuento',
        'total',
        'estado',
        'fecha_confirmacion',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'envio' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_confirmacion' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }
}
