<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'last_name_father',
        'last_name_mother',
        'area_id',
        'user_code',
        'school',
        'level',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relación muchos a uno con Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Relación muchos a uno con Area
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}