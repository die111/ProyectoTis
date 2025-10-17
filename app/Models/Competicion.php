<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Level;

class Competicion extends Model
{
    // Relación: una competición pertenece a un área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    protected $table = 'competicions';
    protected $fillable = [
        'name',
        'description',
        'fechaInicio',
        'fechaFin',
        'state'
    ];
    protected $casts = [
        'fechaInicio' => 'datetime',
        'fechaFin' => 'datetime',
    ];

    // Relación polimórfica con niveles
    public function levels()
    {
        return $this->morphedByMany(Level::class, 'competitionable', 'competitionables', 'competition_id');
    }

    // Relación polimórfica con áreas
    public function areas()
    {
        return $this->morphedByMany(Area::class, 'competitionable', 'competitionables', 'competition_id');
    }

    // Relación con fases
    public function phases()
    {
        return $this->belongsToMany(Phase::class, 'competition_phase', 'competition_id', 'phase_id')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }
}
