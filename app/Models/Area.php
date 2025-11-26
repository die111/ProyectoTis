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

    // Relación con competition_category_area
    public function competitionCategoryAreas()
    {
        return $this->hasMany(CompetitionCategoryArea::class, 'area_id');
    }

    // Método para verificar si el área está siendo usada
    public function isInUse()
    {
        return $this->competitionCategoryAreas()->exists();
    }
}
