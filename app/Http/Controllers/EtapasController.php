<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competicion;
use App\Models\Etapa;

class EtapasController extends Controller
{
    // GET /etapas  -> catálogo público
    public function index(Request $request)
    {
        // Trae la última competición (o filtra por ?competicion_id=)
        $competicion = Competicion::with(['etapas' => fn($q) => $q->orderBy('orden')])
            ->when($request->integer('competicion_id'), fn($q,$id)=>$q->where('id',$id))
            ->latest('anio')
            ->first();

        $etapas = $competicion?->etapas ?? collect();

        return view('etapas.index', compact('competicion','etapas'));
    }

    // GET /etapas/{id} -> detalle (opcional)
    public function show(Etapa $etapa)
    {
        return view('etapas.show', compact('etapa'));
    }

    // GET /admin/etapas -> pantalla de gestión (a futuro)
    public function admin(Request $request)
    {
        $phases = Etapa::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.etapas.index', compact('phases'));
    } 
}
