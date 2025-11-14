<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionCategoryArea extends Model
{
    protected $table = 'competition_category_area';
    protected $fillable = [
        'competition_id',
        'categoria_id',
        'area_id',
    ];

    public function competition()
    {
        return $this->belongsTo(Competicion::class, 'competition_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
