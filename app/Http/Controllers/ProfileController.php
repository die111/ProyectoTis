<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        // Resolve related display names defensively so the view doesn't
        // trigger lazy-loading (which can surface DB errors during render).
    // Use defaults so the blade won't lazy-load relations when these are unset.
    $roleName = '-';
    $areaName = '-';
        try {
            // Attempt to resolve the related role name — if this throws we'll keep the default.
            $role = $user?->role;
            if ($role) {
                $roleName = $role->name;
            }
        } catch (\Throwable $e) {
            // keep default '-'
        }

        try {
            $area = $user?->area;
            if ($area) {
                $areaName = $area->name;
            }
        } catch (\Throwable $e) {
            // keep default '-'
        }

        return view('shared.profile', compact('user', 'roleName', 'areaName'));
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        // Resolve related display names defensively to avoid lazy-loading in the view.
    $roleName = '-';
    $areaName = '-';
        try {
            $role = $user?->role;
            if ($role) {
                $roleName = $role->name;
            }
        } catch (\Throwable $e) {
            // keep default
        }

        try {
            $area = $user?->area;
            if ($area) {
                $areaName = $area->name;
            }
        } catch (\Throwable $e) {
            // keep default
        }

        return view('shared.profile-edit', compact('user', 'roleName', 'areaName'));
    }

    /**
     * Actualizar datos del perfil
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'last_name_father' => ['nullable', 'string', 'max:255'],
            'last_name_mother' => ['nullable', 'string', 'max:255'],
            'telephone_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            Log::debug('profile.update: start', ['user_id' => $user->id]);

            if ($request->hasFile('profile_photo')) {
                Log::debug('profile.update: hasFile true', ['user_id' => $user->id]);
                // borrar foto anterior si existe
                try {
                    if ($user->profile_photo) {
                        Log::debug('profile.update: deleting previous photo', ['path' => $user->profile_photo, 'user_id' => $user->id]);
                        Storage::disk('public')->delete($user->profile_photo);
                        Log::debug('profile.update: previous photo deleted', ['user_id' => $user->id]);
                    }
                } catch (\Throwable $e) {
                    Log::error('profile.update: error deleting previous photo', ['message' => $e->getMessage(), 'exception' => $e, 'user_id' => $user->id]);
                    // don't stop the update for a delete failure; continue so we can see root DB errors.
                }

                try {
                    $path = $request->file('profile_photo')->store('profile_photos', 'public');
                    Log::debug('profile.update: stored new photo', ['path' => $path, 'user_id' => $user->id]);
                    $validated['profile_photo'] = $path;
                } catch (\Throwable $e) {
                    Log::error('profile.update: error storing uploaded photo', ['message' => $e->getMessage(), 'exception' => $e, 'user_id' => $user->id]);
                    // rethrow - file store errors are actionable
                    throw $e;
                }
            } else {
                Log::debug('profile.update: no uploaded file', ['user_id' => $user->id]);
            }

            Log::debug('profile.update: about to fill user', ['validated_keys' => array_keys($validated), 'user_id' => $user->id]);
            $user->fill($validated);

            Log::debug('profile.update: about to save user', ['user_changes' => $user->getChanges(), 'user_id' => $user->id]);
            $user->save();
            Log::debug('profile.update: user saved', ['user_id' => $user->id]);

            return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
        } catch (\Throwable $e) {
            // Log full exception so we can see the original DB error that caused the
            // Postgres transaction to become aborted (25P02 appears later).
            Log::error('Profile update failed during test run.', [
                'message' => $e->getMessage(),
                'exception' => $e,
                'user_id' => $user->id ?? null,
            ]);

            // Re-throw so the test still fails, but logs will contain more detail.
            throw $e;
        }
    }
}
