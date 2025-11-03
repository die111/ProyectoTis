<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $fillable = [
        'nombre', 
        'descripcion', 
        'primero', 
        'segundo', 
        'tercero', 
        'cuarto', 
        'quinto', 
        'sexto', 
        'is_active'
    ];

    public function competiciones()
    {
        return $this->morphToMany(Competicion::class, 'competitionable');
    }
}