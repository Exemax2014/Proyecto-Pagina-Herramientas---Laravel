<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Etiqueta extends Model
{
    public const OFERTA_NOMBRE = 'Oferta';
    public const OFERTA_COLOR = '#d9a441';

    protected $fillable = [
        'nombre',
        'slug',
        'color',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public static function buildSlug(string $nombre): string
    {
        $slug = Str::slug($nombre);

        return $slug !== '' ? $slug : Str::lower(trim($nombre));
    }

    public function getTextoColorAttribute(): string
    {
        $color = ltrim((string) $this->color, '#');

        if (strlen($color) !== 6 || ! ctype_xdigit($color)) {
            return '#ffffff';
        }

        $red = hexdec(substr($color, 0, 2));
        $green = hexdec(substr($color, 2, 2));
        $blue = hexdec(substr($color, 4, 2));
        $luminance = (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;

        return $luminance > 155 ? '#111111' : '#ffffff';
    }
}
