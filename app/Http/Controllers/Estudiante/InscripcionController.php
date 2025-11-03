<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competicion;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    /**
     * Muestra la vista principal de inscripción
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener competencias activas (permitir inscripción antes de que inicien)
        $competenciasActivas = Competicion::where('state', 'activa')
            ->where('fechaFin', '>=', now()) // Solo que no hayan terminado
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
        $competencias = Competicion::where('state', 'activa')
            ->where('fechaFin', '>=', now()) // Solo que no hayan terminado
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

        $user = Auth::user();
        $areas = \App\Models\Area::where('is_active', true)->get();
        $levels = \App\Models\Level::all();

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
                'observaciones' => 'nullable|string',
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
            
            // Crear la inscripción
            $inscripcion = Inscription::create([
                'user_id' => $user->id,
                'competition_id' => $competicion->id,
                'area_id' => $request->area_id,
                'level_id' => $request->level_id,
                'estado' => 'pendiente',
                'es_grupal' => $request->es_grupal ?? false,
                'grupo_nombre' => $request->grupo_nombre,
                'observaciones' => $request->observaciones,
            ]);
            
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
