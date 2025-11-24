<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Audit;

class AuditPolicy
{
    /**
     * Determine whether the user can view any audits.
     */
    public function viewAny(?User $user): bool
    {
        if (!$user) return false;

        // Allow if user has explicit permission
        if ($user->hasPermissionTo('view_audits')) return true;

        // Allow if role name contains 'admin' (case-insensitive)
        if ($user->role && str_contains(strtolower($user->role->name), 'admin')) return true;

        return false;
    }

    /**
     * Determine whether the user can view a specific audit.
     */
    public function view(?User $user, Audit $audit): bool
    {
        return $this->viewAny($user);
    }
}
