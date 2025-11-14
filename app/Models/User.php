<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\User
 *
 * Annotations to help static analyzers (Intelephense) recognize methods and
 * properties injected by the Notifiable trait.
 *
 * @mixin \Illuminate\Notifications\Notifiable
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection $unreadNotifications
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany unreadNotifications()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'ci',
        'address',
        'telephone_number',
        'date_of_birth',
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

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function evaluationsAsEvaluator()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    /**
     * Canal para notificaciones broadcast
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.User.' . $this->id;
    }

    /**
     * Verifica si el usuario tiene un permiso específico a través de su rol
     */
    public function hasPermissionTo(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('name', $permissionName)
            ->exists();
    }
}
