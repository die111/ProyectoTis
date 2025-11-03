<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    protected $table = 'medals';

    protected $fillable = [
        'type', // e.g., 'gold', 'silver', 'bronze'
        'recipient_name',
        'competition_id',
    ];

    // Relación: una medalla pertenece a una competición
    public function competition()
    {
        return $this->belongsTo(Competicion::class, 'competition_id');
    }
}
