<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'auditable_type','auditable_id','user_id','action','old_values','new_values','meta','created_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
