<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reclamo;

class ReclamoController extends Controller
{
    // Lista de reclamos (pendientes y recientes)
    public function index(Request $request)
    {
        $query = $request->input('q');
        $reclamos = Reclamo::with(['inscription.user', 'evaluation', 'user'])
            ->when($query, function($q) use ($query) {
                $q->whereHas('user', function($u) use ($query) {
                    $u->where('name', 'like', "%$query%")
                      ->orWhere('last_name_father', 'like', "%$query%")
                      ->orWhere('email', 'like', "%$query%");
                })->orWhere('mensaje', 'like', "%$query%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reclamos.index', compact('reclamos', 'query'));
    }

    // Mostrar detalle de un reclamo
    public function show($id)
    {
        $reclamo = Reclamo::with(['inscription.competition', 'user', 'evaluation'])->findOrFail($id);
        return view('admin.reclamos.show', compact('reclamo'));
    }

    // Actualizar estado o respuesta del reclamo
    public function update(Request $request, $id)
    {
        $reclamo = Reclamo::findOrFail($id);
        $accion = $request->input('accion');

        if ($accion === 'atender') {
            $reclamo->estado = 'atendido';
            $reclamo->save();

            // Notificar al autor del reclamo que fue atendido
            try {
                $reclamo->user->notify(new \App\Notifications\FrontNotification(
                    'Reclamo Atendido',
                    'Tu reclamo ha sido marcado como atendido por el equipo.',
                    'success',
                    route('estudiante.inscripcion.detalle', $reclamo->inscription_id) . '?reclamo_id=' . $reclamo->id,
                    $reclamo->inscription_id
                ));
            } catch (\Exception $e) {
                // ignore
            }

            return redirect()->route('admin.reclamos.show', $reclamo->id)->with('success', 'Reclamo marcado como atendido.');
        }

        return redirect()->route('admin.reclamos.show', $reclamo->id);
    }
}
