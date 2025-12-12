<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationLog extends Model
{
    protected $fillable = [
        'evaluation_id',
        'nota_anterior',
        'nota_nueva',
        'estado_anterior',
        'estado_nuevo',
        'user_id',
        'motivo',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
