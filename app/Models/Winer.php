<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winer extends Model
{
    protected $table = 'winners';
    
    protected $fillable = [
        'evaluation_id',
        'premio',
        'posicion',
        'observaciones',
        'is_active',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
