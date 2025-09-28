<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function phases()    
    {
        return $this->hasMany(Phase::class);
    }
}
