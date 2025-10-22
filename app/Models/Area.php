<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    // Relacion uno a muchos con user
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function competiciones()
    {
        return $this->morphToMany(Competicion::class, 'competitionable');
    }
}
