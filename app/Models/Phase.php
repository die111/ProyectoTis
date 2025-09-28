<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $fillable = [
        'area_id', 'name', 'description', 'start_date', 'end_date'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
