<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class InscripcionController extends Controller
{
    public function index()
    {
        return view('admin.inscripcion.index');
    }

    public function guardarEstudiantes(Request $request)
    {
        try {
            $estudiantes = $request->input('estudiantes', []);
            Log::info('Datos recibidos:', $estudiantes);
            
            $createdCount = 0;
            $skippedCount = 0;
            
            foreach ($estudiantes as $est) {
                // Verificar si ya existe un usuario con este email
                $existingUser = User::where('email', $est['email'])->first();
                if ($existingUser) {
                    Log::info('Usuario ya existe con email: ' . $est['email'] . ', saltando...');
                    $skippedCount++;
                    continue;
                }
                
                // Buscar el role_id basado en el nombre del rol
                $roleId = \App\Models\Role::where('name', $est['role'])->value('id');
                Log::info('Buscando rol: ' . $est['role'] . ', encontrado ID: ' . $roleId);
                
                if (!$roleId) {
                    // Si no encuentra el rol, usar 'evaluador' como rol por defecto
                    $roleId = \App\Models\Role::where('name', 'evaluador')->value('id');
                    Log::info('Usando rol por defecto evaluador, ID: ' . $roleId);
                }
                
                // Si aún no encuentra un rol válido, crear uno por defecto
                if (!$roleId) {
                    $roleId = 1; // Usar ID 1 como fallback
                    Log::warning('No se encontró ningún rol válido, usando ID 1 como fallback');
                }
                
                User::create([
                    'name' => $est['name'],
                    'last_name_father' => $est['last_name_father'],
                    'last_name_mother' => $est['last_name_moothe'],
                    'email' => $est['email'],
                    'password' => bcrypt($est['password']),
                    'role_id' => $roleId,
                    'area_id' => $est['area_id'] ?: 1,
                    'user_code' => $est['user_code'],
                    'is_active' => $est['is_active'] ?? true,
                ]);
                $createdCount++;
            }
            
            $message = "Se crearon $createdCount estudiantes";
            if ($skippedCount > 0) {
                $message .= " y se saltaron $skippedCount por email duplicado";
            }
            
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Error al guardar estudiantes: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
