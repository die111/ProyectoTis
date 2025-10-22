<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    // Relación uno a muchos con User
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    // Relación muchos a muchos con Permission
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
