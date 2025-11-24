<?php
namespace App\Http\Controllers\Admin;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Audit::class);

        $q = Audit::query();

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

        // Obtener lista de modelos disponibles para filtrar
        $modelFiles = glob(app_path('Models') . '/*.php');
        $models = [];
        foreach ($modelFiles as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $class = "App\\Models\\{$name}";
            if (class_exists($class)) {
                $models[$class] = class_basename($class);
            }
        }
        asort($models);

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
