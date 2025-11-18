<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Level;
use App\Models\Categoria;
use App\Models\CompetitionCategoryArea;

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
        'inscripcion_inicio' => 'datetime',
        'inscripcion_fin' => 'datetime',
        'evaluacion_inicio' => 'datetime',
        'evaluacion_fin' => 'datetime',
        'premiacion_inicio' => 'datetime',
        'premiacion_fin' => 'datetime',
    ];
    
    /**
     * Scope: Competencias con inscripción abierta
     */
    public function scopeInscripcionAbierta($query)
    {
        $today = now()->startOfDay();
        
        return $query->where('state', 'activa')
            ->where('fechaFin', '>=', now())
            ->where(function($q) use ($today) {
                $q->where(function($subq) use ($today) {
                    // Competencias con fechas de inscripción definidas y dentro del rango
                    $subq->whereNotNull('inscripcion_inicio')
                        ->whereNotNull('inscripcion_fin')
                        ->where('inscripcion_inicio', '<=', $today)
                        ->where('inscripcion_fin', '>=', $today);
                })->orWhere(function($subq) {
                    // Competencias sin fechas de inscripción (siempre abiertas si están activas)
                    $subq->whereNull('inscripcion_inicio')
                        ->whereNull('inscripcion_fin');
                });
            });
    }

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
            ->withPivot('start_date', 'end_date', 'clasificados', 'color', 'classification_type', 'classification_cupo', 'classification_nota_minima')
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
    
    /**
     * Verifica si la competencia está en período de inscripción
     */
    public function isInscripcionAbierta(): bool
    {
        // Si no tiene fechas de inscripción definidas, se considera abierta (si está activa)
        if (is_null($this->inscripcion_inicio) && is_null($this->inscripcion_fin)) {
            return $this->state === 'activa';
        }
        
        $today = now()->startOfDay();
        
        // Verificar que estemos dentro del rango de fechas
        $dentroDelRango = true;
        
        if ($this->inscripcion_inicio) {
            $dentroDelRango = $dentroDelRango && $today->greaterThanOrEqualTo($this->inscripcion_inicio);
        }
        
        if ($this->inscripcion_fin) {
            $dentroDelRango = $dentroDelRango && $today->lessThanOrEqualTo($this->inscripcion_fin);
        }
        
        return $this->state === 'activa' && $dentroDelRango;
    }
    
    /**
     * Obtiene el estado del período de inscripción
     */
    public function getInscripcionStatus(): array
    {
        if (is_null($this->inscripcion_inicio) && is_null($this->inscripcion_fin)) {
            return [
                'status' => 'abierta',
                'message' => 'Inscripciones abiertas'
            ];
        }
        
        $today = now()->startOfDay();
        
        if ($this->inscripcion_inicio && $today->lt($this->inscripcion_inicio)) {
            return [
                'status' => 'no_iniciada',
                'message' => 'Las inscripciones inician el ' . $this->inscripcion_inicio->format('d/m/Y'),
                'fecha_inicio' => $this->inscripcion_inicio
            ];
        }
        
        if ($this->inscripcion_fin && $today->gt($this->inscripcion_fin)) {
            return [
                'status' => 'finalizada',
                'message' => 'Las inscripciones finalizaron el ' . $this->inscripcion_fin->format('d/m/Y'),
                'fecha_fin' => $this->inscripcion_fin
            ];
        }
        
        return [
            'status' => 'abierta',
            'message' => 'Inscripciones abiertas hasta el ' . ($this->inscripcion_fin ? $this->inscripcion_fin->format('d/m/Y') : 'nuevo aviso'),
            'fecha_fin' => $this->inscripcion_fin
        ];
    }
}
