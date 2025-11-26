<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Verifica si la fase está siendo utilizada en alguna competición
     */
    public function isInUse()
    {
        return DB::table('competition_phase')
            ->where('phase_id', $this->id)
            ->exists();
    }

    // TODO: Implementar relación con stages si es necesario
    // public function stages()
    // {
    //     return $this->hasMany(Etapa::class, 'phase_id');
    // }
}
