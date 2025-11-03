<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Log;
=======
use App\Models\Inscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FrontNotification;
>>>>>>> Stashed changes

class InscripcionController extends Controller
{
    public function index()
    {
        return view('admin.inscripcion.index');
<<<<<<< Updated upstream
=======
    }

    public function solicitud()
    {
        // Obtener todas las inscripciones con sus relaciones
        $inscripciones = Inscription::with(['user', 'competition', 'area', 'level'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.inscripcion.solicitud', compact('inscripciones'));
    }

    public function actualizarEstado(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|in:confirmada,rechazada,pendiente',
                'observaciones' => 'nullable|string'
            ]);

            $inscripcion = Inscription::findOrFail($id);
            $estadoAnterior = $inscripcion->estado;
            
            // Actualizar el estado
            $inscripcion->estado = $request->estado;
            if ($request->observaciones) {
                $inscripcion->observaciones = $request->observaciones;
            }
            $inscripcion->save();

            // Enviar notificación al estudiante
            $mensaje = $this->generarMensajeNotificacion($request->estado, $inscripcion);
            
            $inscripcion->user->notify(new FrontNotification(
                $mensaje['titulo'],
                $mensaje['mensaje'],
                $mensaje['tipo'],
                route('estudiante.inscripcion.index')
            ));

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generarMensajeNotificacion($estado, $inscripcion)
    {
        $competencia = $inscripcion->competition->name;
        
        switch ($estado) {
            case 'confirmada':
                return [
                    'titulo' => '¡Inscripción Aprobada!',
                    'mensaje' => "Tu inscripción a la competencia '{$competencia}' ha sido aprobada.",
                    'tipo' => 'success'
                ];
            case 'rechazada':
                return [
                    'titulo' => 'Inscripción Rechazada',
                    'mensaje' => "Tu inscripción a la competencia '{$competencia}' ha sido rechazada. Revisa las observaciones.",
                    'tipo' => 'error'
                ];
            case 'pendiente':
                return [
                    'titulo' => 'Inscripción en Revisión',
                    'mensaje' => "Tu inscripción a la competencia '{$competencia}' está siendo revisada.",
                    'tipo' => 'info'
                ];
            default:
                return [
                    'titulo' => 'Actualización de Inscripción',
                    'mensaje' => "El estado de tu inscripción a '{$competencia}' ha sido actualizado.",
                    'tipo' => 'info'
                ];
        }
>>>>>>> Stashed changes
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
