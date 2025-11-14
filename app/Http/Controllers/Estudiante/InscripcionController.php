<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Competicion;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CompetitionCategoryArea;
use App\Models\Level;
use App\Models\Categoria;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\FrontNotification;

class InscripcionController extends Controller
{
    /**
     * Muestra la vista principal de inscripción
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener competencias activas que están en período de inscripción
        $competenciasActivas = Competicion::inscripcionAbierta()
            ->with(['area', 'phases'])
            ->orderBy('fechaInicio', 'asc')
            ->get();
        
        // Obtener inscripciones del usuario
        $misInscripciones = Inscription::where('user_id', $user->id)
            ->with(['competition'])
            ->get();
        
        return view('estudiante.inscripcion.index', compact('competenciasActivas', 'misInscripciones', 'user'));
    }

    /**
     * Obtiene las competencias activas disponibles
     */
    public function competenciasActivas()
    {
        $competencias = Competicion::inscripcionAbierta()
            ->with(['area', 'phases'])
            ->orderBy('fechaInicio', 'asc')
            ->get();
        
        return response()->json($competencias);
    }

    /**
     * Muestra el formulario de inscripción para una competencia específica
     */
    public function create($competicionId)
    {
        // Cargar la competencia
        $competencia = Competicion::with(['area', 'phases'])->findOrFail($competicionId);
        
        // Verificar que la competencia esté activa
        if ($competencia->state !== 'activa') {
            return redirect()->route('estudiante.inscripcion.index')
                ->with('error', 'Esta competencia no está disponible para inscripción.');
        }

        // Verificar que no haya terminado
        if ($competencia->fechaFin < now()) {
            return redirect()->route('estudiante.inscripcion.index')
                ->with('error', 'Esta competencia ya ha finalizado.');
        }
        
        // Verificar si está en período de inscripción
        if (!$competencia->isInscripcionAbierta()) {
            $status = $competencia->getInscripcionStatus();
            return redirect()->route('estudiante.inscripcion.index')
                ->with('error', $status['message']);
        }

        $user = Auth::user();
        $areas = Area::where('is_active', true)->get();
        $levels = Level::all();

        return view('estudiante.inscripcion.create', compact('competencia', 'areas', 'levels', 'user'));
    }

    /**
     * Inscribe al estudiante en una competencia
     */
    public function inscribir(Request $request, Competicion $competicion)
    {
        try {
            $user = Auth::user();
            
            // Validar datos requeridos
            $request->validate([
                'area_id' => 'required|exists:areas,id',
                'level_id' => 'required|exists:levels,id',
                'es_grupal' => 'boolean',
                'grupo_nombre' => 'nullable|string|max:255',
                'observaciones_estudiante' => 'nullable|string',
            ]);
            
            // Verificar si la competencia está activa
            if ($competicion->state !== 'activa') {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Esta competencia no está activa.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Esta competencia no está activa.');
            }
            
            // Verificar si estamos dentro del rango de fechas de inscripción
            if (!$competicion->isInscripcionAbierta()) {
                $status = $competicion->getInscripcionStatus();
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $status['message']
                    ], 400);
                }
                return redirect()->back()->with('error', $status['message']);
            }
            
            // Verificar si ya está inscrito en esta área
            $yaInscrito = Inscription::where('user_id', $user->id)
                ->where('competition_id', $competicion->id)
                ->where('area_id', $request->area_id)
                ->exists();
            
            if ($yaInscrito) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya estás inscrito en esta competencia y área.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Ya estás inscrito en esta competencia y área.');
            }
            
            // Determinar categoria_id asociada a la competencia y área
            $pair = CompetitionCategoryArea::where('competition_id', $competicion->id)
                ->where('area_id', $request->area_id)
                ->first();

            if (!$pair) {
                $msg = 'No hay categorías configuradas para la combinación competencia/área seleccionada. Se asignará una categoría por defecto. Contacta al administrador para revisar.';
                Log::warning($msg . ' competition_id=' . $competicion->id . ' area_id=' . $request->area_id . ' user_id=' . $user->id);
                // Añadir mensaje de sesión para usuarios web
                if (! $request->expectsJson()) {
                    session()->flash('warning', $msg);
                }

                // Buscar una categoría activa primero, luego la primera disponible
                $fallbackCategoria = Categoria::where('is_active', true)->first() ?? Categoria::first();
                if (! $fallbackCategoria) {
                    // Crear categoría por defecto
                    $fallbackCategoria = Categoria::create([
                        'nombre' => 'Sin categoría',
                        'descripcion' => 'Categoría generada automáticamente al inscribir sin configuración',
                        'is_active' => false,
                    ]);
                }
                $categoriaId = $fallbackCategoria->id;
            } else {
                $categoriaId = $pair->categoria_id;
            }

            $fase = 1;

            // Crear la inscripción
            $inscripcion = Inscription::create([
                'user_id' => $user->id,
                'competition_id' => $competicion->id,
                'area_id' => $request->area_id,
                'categoria_id' => $categoriaId,
                'fase' => $fase,
                'level_id' => $request->level_id,
                'estado' => 'pendiente',
                'es_grupal' => $request->es_grupal ?? false,
                'grupo_nombre' => $request->grupo_nombre,
                'observaciones_estudiante' => $request->observaciones_estudiante,
            ]);
            
            // Enviar notificación a usuarios con permiso de 'inscripcion'
            try {
                $permission = Permission::where('name', 'inscripcion')->first();
                if ($permission) {
                    // Obtener todos los roles que tienen este permiso
                    $roleIds = $permission->roles()->pluck('roles.id');
                    
                    // Obtener usuarios activos que tienen alguno de esos roles
                    $usersToNotify = User::whereIn('role_id', $roleIds)
                        ->where('is_active', true)
                        ->get();
                    
                    // Enviar notificación a cada usuario
                    foreach ($usersToNotify as $userToNotify) {
                        $userToNotify->notify(new FrontNotification(
                            'Nueva Inscripción Pendiente',
                            "El estudiante {$user->name} {$user->last_name_father} se ha inscrito a la competencia '{$competicion->name}' y requiere aprobación.",
                            'info',
                            route('admin.inscripcion.solicitud') . '?inscripcion_id=' . $inscripcion->id,
                            $inscripcion->id
                        ));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error al enviar notificaciones de nueva inscripción: ' . $e->getMessage());
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Te has inscrito exitosamente a la competencia: ' . $competicion->name,
                    'inscripcion' => $inscripcion
                ]);
            }
            
            return redirect()->route('estudiante.inscripcion.index')
                ->with('success', 'Te has inscrito exitosamente a la competencia: ' . $competicion->name);
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la inscripción: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al procesar la inscripción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra las inscripciones del estudiante
     */
    public function misInscripciones()
    {
        $user = Auth::user();
        
        $inscripciones = Inscription::where('user_id', $user->id)
            ->with(['competition.area', 'competition.phases'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('estudiante.inscripcion.mis-inscripciones', compact('inscripciones', 'user'));
    }
}
