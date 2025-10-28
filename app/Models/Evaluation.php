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
}
