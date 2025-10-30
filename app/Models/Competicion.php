<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Level;
use App\Models\Categoria;

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
        'inscripcion_inicio',
        'inscripcion_fin',
        'evaluacion_inicio',
        'evaluacion_fin',
        'premiacion_inicio',
        'premiacion_fin',
        'state'
    ];
    protected $casts = [
        'fechaInicio' => 'datetime',
        'fechaFin' => 'datetime',
        'inscripcion_inicio' => 'date',
        'inscripcion_fin' => 'date',
        'evaluacion_inicio' => 'date',
        'evaluacion_fin' => 'date',
        'premiacion_inicio' => 'date',
        'premiacion_fin' => 'date',
    ];

    // Relación polimórfica con niveles (compatibilidad)
    public function levels()
    {
        return $this->morphedByMany(Level::class, 'competitionable', 'competitionables', 'competition_id');
    }

    // Relación polimórfica con categorías (nueva)
    public function categorias()
    {
        return $this->morphedByMany(Categoria::class, 'competitionable', 'competitionables', 'competition_id');
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
            ->withPivot('start_date', 'end_date', 'clasificados', 'classification_type', 'classification_cupo', 'classification_nota_minima')
            ->withTimestamps();
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'competition_id');
    }

    public function medals()
    {
        return $this->hasMany(Medal::class, 'competition_id');
    }

    public function categoryAreas()
    {
        return $this->hasMany(CompetitionCategoryArea::class, 'competition_id');
    }
}
