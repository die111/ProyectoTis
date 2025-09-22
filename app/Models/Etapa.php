<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etapa extends Model
{
    protected $table = 'etapa';
    protected $fillable = ['competicion_id','nombre','fecha_inicio','fecha_fin','estado','orden'];
    protected $casts = ['fecha_inicio'=>'date','fecha_fin'=>'date'];

    public function competicion(): BelongsTo
    {
        return $this->belongsTo(Competicion::class,'competicion_id');
    }

    // Helpers para la vista
    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'concluido' => 'badge--ok',
            'en_proceso'=> 'badge--info',
            default     => 'badge--warn',
        };
    }

    public function getEstadoLabelAttribute(): string
    {
        return strtoupper(match ($this->estado) {
            'concluido' => 'Concluido',
            'en_proceso'=> 'En Proceso',
            default     => 'Pendiente',
        });
    }
}
