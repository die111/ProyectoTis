<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Area;
use App\Models\Role;
use App\Models\Competicion;
use App\Models\Inscription;
use App\Models\Categoria;
use App\Models\CompetitionCategoryArea;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Notifications\FrontNotification;
use Illuminate\Support\Facades\Notification;

class InscripcionController extends Controller
{
    public function index()
    {
        $competiciones = Competicion::select('id', 'name', 'state')
            ->where('state', 'activa')
            ->orderBy('name')
            ->get();
        return view('admin.inscripcion.index', compact('competiciones'));
    }

    public function getAreas()
    {
        try {
            $areas = Area::select('id', 'name')->where('is_active', true)->get();
            return response()->json(['success' => true, 'areas' => $areas]);
        } catch (\Exception $e) {
            Log::error('Error al obtener áreas: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getCompeticiones()
    {
        try {
            $competiciones = Competicion::select('id', 'name', 'description', 'state')
                ->get();
            return response()->json(['success' => true, 'competiciones' => $competiciones]);
        } catch (\Exception $e) {
            Log::error('Error al obtener competiciones: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Normaliza nombres: quita tildes, pasa a minúsculas y limpia espacios
    protected function normalizeName(?string $value): string
    {
        if ($value === null) return '';
        $s = Str::of($value)->ascii()->lower()->trim();
        // opcional: elimina caracteres no alfanuméricos excepto espacios
        $s = preg_replace('/[^a-z0-9 ]+/', '', (string) $s);
        // colapsar múltiples espacios
        $s = preg_replace('/\s+/', ' ', (string) $s);
        return trim((string) $s);
    }

    // Resuelve el ID de área por nombre
    protected function resolveAreaId($input, array $areaMap, array $areaList): ?int
    {
        if (is_null($input)) return null;
        // Si llega numérico, devolver directo
        if (is_numeric($input)) return (int) $input;

        $norm = $this->normalizeName((string)$input);
        if ($norm === '') return null;

        if (isset($areaMap[$norm])) return (int) $areaMap[$norm];

        foreach ($areaList as $row) {
            if (str_contains($row['norm'], $norm) || str_contains($norm, $row['norm'])) {
                return (int) $row['id'];
            }
        }

        $bestId = null; $bestPct = 0.0;
        foreach ($areaList as $row) {
            $pct = 0.0;
            similar_text($norm, $row['norm'], $pct);
            if ($pct > $bestPct) { $bestPct = $pct; $bestId = (int) $row['id']; }
        }
        if ($bestId !== null && $bestPct >= 70.0) return $bestId;

        return null;
    }

    protected function resolveCategoriaId($inputNombre): ?int
    {
        if ($inputNombre === null) return null;
        $norm = $this->normalizeName((string)$inputNombre);
        if ($norm === '') return null;

        $categorias = Categoria::select('id', 'nombre', 'is_active')->get();
        $map = [];
        $list = [];
        foreach ($categorias as $c) {
            $n = $this->normalizeName($c->nombre);
            if ($n !== '') $map[$n] = $c->id;
            $list[] = ['id' => $c->id, 'norm' => $n];
        }
        if (isset($map[$norm])) return (int) $map[$norm];
        // similitud básica
        $bestId = null; $bestPct = 0.0;
        foreach ($list as $row) {
            $pct = 0.0; similar_text($norm, $row['norm'], $pct);
            if ($pct > $bestPct) { $bestPct = $pct; $bestId = (int)$row['id']; }
        }
        return $bestPct >= 70.0 ? $bestId : null;
    }

    public function solicitud()
    {
        // Obtener todas las inscripciones con sus relaciones
        $inscripciones = Inscription::with(['user', 'competition', 'area', 'level'])
            ->where('fase', '1')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.inscripcion.solicitud', compact('inscripciones'));
    }

    public function show($id)
    {
        // Buscar la inscripción con todas sus relaciones
        $inscripcion = Inscription::with(['user', 'competition', 'area', 'level', 'categoria'])
            ->findOrFail($id);
        
        return view('admin.inscripcion.show', compact('inscripcion'));
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
            
            // Si se rechaza, guardar el motivo en motivo_rechazo
            if ($request->estado === 'rechazada' && $request->observaciones) {
                $inscripcion->motivo_rechazo = $request->observaciones;
            }
            
            $inscripcion->save();

            // Enviar notificación al estudiante
            $mensaje = $this->generarMensajeNotificacion($request->estado, $inscripcion);
            
            $inscripcion->user->notify(new FrontNotification(
                $mensaje['titulo'],
                $mensaje['mensaje'],
                $mensaje['tipo'],
                route('estudiante.inscripcion.index') . '?inscripcion_id=' . $inscripcion->id,
                $inscripcion->id  // Pasar el ID de la inscripción
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
                    'mensaje' => "Tu inscripción a la competencia '{$competencia}' ha sido rechazada. Revisa el motivo del rechazo.",
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
    }

    public function guardarEstudiantes(Request $request)
    {
        try {
            $estudiantes = $request->input('estudiantes', []);
            $competitionId = $request->input('competition_id');
            if (!$competitionId) {
                return response()->json(['success' => false, 'error' => 'competition_id es requerido'], 422);
            }
            Log::info('Datos recibidos:', $estudiantes);
            Log::info('competition_id: ' . $competitionId);
            
            $createdUsers = 0; $skippedUsersDuplicate = 0;
            $createdInscriptions = 0; $skippedInscriptionsDuplicate = 0;

            // ID del rol Estudiante (obligatorio)
            $studentRoleId = Role::whereRaw('LOWER(name) = ?', ['estudiante'])->value('id');
            if (!$studentRoleId) {
                Log::error('No existe el rol "Estudiante" en la tabla roles.');
                return response()->json([
                    'success' => false,
                    'error' => 'No existe el rol "Estudiante". Créalo en la tabla roles.'
                ], 422);
            }

            $areas = Area::where('is_active', true)->get(['id','name']);
            $areaMap = []; 
            $areaList = [];
            foreach ($areas as $a) {
                $norm = $this->normalizeName($a->name);
                if ($norm !== '') { $areaMap[$norm] = $a->id; }
                $areaList[] = [ 'id' => $a->id, 'norm' => $norm ];
            }

            $skippedNoEmail = 0;
            foreach ($estudiantes as $est) {
                // Normalizar y proteger accesos a claves del CSV
                $name = $est['name'] ?? '';
                $lastNameFather = $est['last_name_father'] ?? '';
                $lastNameMother = $est['last_name_mother'] ?? '';
                $ci = $est['ci'] ?? null;
                $email = $est['email'] ?? null;
                $password = $est['password'] ?? null;
                $userCode = $est['user_code'] ?? null;
                $isActive = $est['is_active'] ?? true;

                // Si no hay email no podemos crear usuario (email es identificador)
                if (empty($email)) {
                    $skippedNoEmail++;
                    Log::warning('Fila de estudiantes omitida por faltar email: ' . json_encode($est));
                    continue;
                }

                // Verificar si usuario existe por email
                $existingUser = User::where('email', $email)->first();

                // Mapear área
                $areaId = $this->resolveAreaId($est['area_id'] ?? null, $areaMap, $areaList);
                if (!$areaId) {
                    $areaId = Area::first()?->id ?? 1;
                    Log::warning('No se encontró área válida para "' . ($est['area_id'] ?? '') . '", usando ID por defecto: ' . $areaId);
                }

                // Crear o reutilizar usuario
                if (!$existingUser) {
                    // Si no viene password, generar una password aleatoria segura
                    $plainPassword = $password ?? Str::random(10);
                    $user = User::create([
                        'name' => $name,
                        'last_name_father' => $lastNameFather,
                        'last_name_mother' => $lastNameMother,
                        'ci' => $ci,
                        'email' => $email,
                        'password' => bcrypt($plainPassword),
                        'role_id' => $studentRoleId, // Rol Estudiante
                        'area_id' => $areaId,
                        'user_code' => $userCode,
                        'is_active' => $isActive,
                    ]);
                    $createdUsers++;
                } else {
                    $user = $existingUser;
                    $skippedUsersDuplicate++;
                }

                // Resolver categoría opcional por nombre enviado
                $categoriaId = null;
                if (!empty($est['categoria'] ?? '')) {
                    $categoriaId = $this->resolveCategoriaId($est['categoria']);
                }

                // Crear inscripción si no existe para esta competición y usuario
                $yaExiste = Inscription::where('competition_id', $competitionId)
                    ->where('user_id', $user->id)
                    ->exists();
                if ($yaExiste) {
                    $skippedInscriptionsDuplicate++;
                } else {
                    Inscription::create([
                        'competition_id' => (int)$competitionId,
                        'user_id' => $user->id,
                        'area_id' => $areaId,
                        'categoria_id' => $categoriaId,
                        'fase' => 1,
                        'estado' => 'confirmada',
                        'is_active' => true,
                    ]);
                    $createdInscriptions++;
                }
            }
            
            $message = "Usuarios creados: $createdUsers";
            if ($skippedUsersDuplicate > 0) $message .= ", usuarios existentes: $skippedUsersDuplicate";
            $message .= ", inscripciones creadas: $createdInscriptions";
            if ($skippedInscriptionsDuplicate > 0) $message .= ", inscripciones existentes: $skippedInscriptionsDuplicate";
            if ($skippedNoEmail > 0) $message .= ", filas sin email: $skippedNoEmail";
            
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('Error al guardar estudiantes/inscripciones: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getCompetitionAreasCategories($competitionId)
    {
        try {
            $rows = CompetitionCategoryArea::with(['area:id,name', 'categoria:id,nombre'])
                ->where('competition_id', $competitionId)
                ->get();

            $grouped = [];
            foreach ($rows as $row) {
                if (!$row->categoria) continue; // se requiere categoría para agrupar
                $catId = $row->categoria->id;
                if (!isset($grouped[$catId])) {
                    $grouped[$catId] = [
                        'id' => $catId,
                        'nombre' => $row->categoria->nombre,
                        'areas' => []
                    ];
                }
                if ($row->area) {
                    $areaId = $row->area->id;
                    // evitar duplicados
                    if (!isset($grouped[$catId]['areas'][$areaId])) {
                        $grouped[$catId]['areas'][$areaId] = [
                            'id' => $areaId,
                            'name' => $row->area->name
                        ];
                    }
                }
            }

            // Convertir áreas asociativas a listas simples
            $categorias = array_map(function($c){
                $c['areas'] = array_values($c['areas']);
                return $c;
            }, array_values($grouped));

            return response()->json([
                'success' => true,
                'categorias' => $categorias,
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo categorías/áreas agrupadas: '.$e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
