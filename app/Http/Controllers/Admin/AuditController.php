<?php
namespace App\Http\Controllers\Admin;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Audit::class);

        // Eager-load user to avoid N+1 and ensure names are available in the view
        $q = Audit::with('user');

        if ($request->filled('model')) {
            $q->where('auditable_type', $request->model);
        }
        if ($request->filled('user_id')) {
            $q->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $q->where('action', $request->action);
        }
        if ($request->filled('from')) {
            $q->where('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->where('created_at', '<=', $request->to);
        }

        $audits = $q->orderBy('created_at','desc')->paginate(15);

        // Obtener lista de modelos disponibles para filtrar (buscar recursivamente en app/Models)
        $models = [];
        try {
            $base = app_path('Models');
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base));
            foreach ($rii as $file) {
                if ($file->isDir()) continue;
                if ($file->getExtension() !== 'php') continue;
                $relative = str_replace($base . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $class = 'App\\Models\\' . str_replace(DIRECTORY_SEPARATOR, '\\', substr($relative, 0, -4));
                if (class_exists($class)) {
                    $models[$class] = class_basename($class);
                }
            }
            asort($models);
        } catch (\Throwable $e) {
            // En caso de error, dejar una lista mínima para que la vista funcione
            $models = [
                'App\\Models\\Inscription' => 'Inscripción',
                'App\\Models\\Evaluation' => 'Evaluación',
                'App\\Models\\User' => 'Usuario',
            ];
        }

        return view('admin.audits.index', compact('audits', 'models'));
    }

    public function show(Audit $audit)
    {
        $this->authorize('view', $audit);
        // Obtener historial completo de auditorías para el mismo registro (mismo modelo + id)
        $history = Audit::where('auditable_type', $audit->auditable_type)
            ->where('auditable_id', $audit->auditable_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.audits.show', compact('audit', 'history'));
    }
}
