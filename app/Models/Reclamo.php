<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inscription;
use App\Models\Evaluation;
use App\Models\User;

class Reclamo extends Model
{
    protected $table = 'reclamos';

    protected $fillable = [
        'inscription_id',
        'evaluation_id',
        'user_id',
        'fase',
        'mensaje',
        'estado', // pendiente, atendido, rechazado
        'respuesta'
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'inscription_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
