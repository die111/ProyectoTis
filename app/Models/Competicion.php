<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competicion extends Model
{
    protected $table = 'competicions';
    protected $fillable = ['nombre','descripcion','anio'];

    public function etapas(): HasMany
    {
        return $this->hasMany(Etapa::class, 'competicion_id');
    }
}
