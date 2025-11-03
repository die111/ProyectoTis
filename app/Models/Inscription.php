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

    protected $casts = [
        'es_grupal' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function competition()
    {
        return $this->belongsTo(Competicion::class, 'competition_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
