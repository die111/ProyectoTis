<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'competition_id',
        'user_id',
        'area_id',
        'level_id',
        'estado',
        'es_grupal',
        'grupo_nombre',
        'observaciones',
        'is_active'
    ];

    // Relación con el usuario (estudiante)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la competición
    public function competicion()
    {
        return $this->belongsTo(Competicion::class, 'competition_id');
    }

    // Relación con el área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Relación con el nivel
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
