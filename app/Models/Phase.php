<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'name', 'description', 'is_active'
    ];

    // Relación muchos a muchos con Competicion
    public function competicions()
    {
        return $this->belongsToMany(Competicion::class, 'competition_phase', 'phase_id', 'competition_id')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    // TODO: Implementar relación con stages si es necesario
    // public function stages()
    // {
    //     return $this->hasMany(Etapa::class, 'phase_id');
    // }
}
