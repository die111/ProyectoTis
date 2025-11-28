<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionPhase extends Model
{
    protected $table = 'competition_phase';
    protected $fillable = [
        'competition_id',
        'phase_id',
        // agrega otros campos si existen
    ];
    public $timestamps = false;
}
