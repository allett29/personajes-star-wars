<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    use HasFactory;

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