<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'name', 'description','clasificados', 'is_active'
    ];

    // Relacion uno a muchos con Competicion
    public function phases()
    {
        return $this->belongsToMany(Phase::class, 'competition_phase')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }
}
