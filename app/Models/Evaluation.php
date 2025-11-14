<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'inscription_id',
        'stage_id',
        'evaluator_id',
        'nota',
        'estado',
        'observaciones_evaluador',
        'is_active',
    ];

    /**
     * Relación con la inscripción
     */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'inscription_id');
    }

    /**
     * Relación con el stage
     */
    public function stage()
    {
        return $this->belongsTo(Etapa::class, 'stage_id');
    }

    /**
     * Relación con el evaluador
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
