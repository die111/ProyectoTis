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
        'role',
        'area',
        'is_active',
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

    // Métodos para verificar roles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResponsableArea(): bool
    {
        return $this->role === 'responsable_area';
    }

    public function isEvaluador(): bool
    {
        return $this->role === 'evaluador';
    }

    public function isCoordinador(): bool
    {
        return $this->role === 'coordinador';
    }

    // Método para verificar si puede acceder a un área específica
    public function canAccessArea(string $area): bool
    {
        return $this->isAdmin() || $this->area === $area;
    }

    // Obtener el nombre del rol para mostrar
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrador',
            'responsable_area' => 'Responsable de Área',
            'evaluador' => 'Evaluador',
            'coordinador' => 'Coordinador',
            default => 'Usuario'
        };
    }
}