<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $fillable = ['nombre'];

    // Relacion uno a muchos con Competicion
    public function competiciones()
    {
        return $this->morphToMany(Competicion::class, 'competitionable');
    }
}
