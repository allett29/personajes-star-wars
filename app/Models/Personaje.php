<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Personaje de Star Wars. Con SoftDeletes, eliminar solo marca deleted_at
 * y el registro deja de aparecer en las consultas normales.
 */
class Personaje extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'personajes';

    protected $fillable = [
        'nombre',
        'altura',
        'masa',
        'color_cabello',
        'color_ojos',
        'genero',
        'planeta_origen',
        'origen_swapi',
        'swapi_id',
    ];

    protected $casts = [
        'altura' => 'integer',
        'masa' => 'integer',
        'origen_swapi' => 'boolean',
        'swapi_id' => 'integer',
    ];
}