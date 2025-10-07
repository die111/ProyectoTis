<?php
namespace App\Http\Controllers;

use App\Models\Competicion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function contactos()
    {
        $contact = [
            'email'     => 'informaciones@umss.edu.bo',
            'phone'     => '(+591) 4 4525161',
            'address'   => 'Av. Oquendo y Jordan',
            'facebook'  => 'https://www.facebook.com/UmssBolOficial/',
            'instagram' => 'https://www.instagram.com/umssboloficial/?hl=es',
            'tiktok'    => 'https://www.tiktok.com/discover/universidad-mayor-de-san-sim%C3%B3n',
        ];
        return view('home.contactos', compact('contact'));
    }

    public function clasificados(Request $request) {
        return view('home.clasificados');
    }

    public function etapas(Request $request)
    {
        $competicion = Competicion::with('phases')
            ->when($request->integer('competicion_id'), fn($q, $id) => $q->where('id', $id))
            ->where('state', '!=', 'borrador')
            ->latest('fechaInicio')
            ->first();

        $etapas = $competicion?->phases->map(function($phase) {
            return (object)[
                'id' => $phase->id,
                'nombre' => $phase->name,
                'descripcion' => $phase->description,
                'fecha_inicio' => $phase->pivot->start_date ?? null,
                'fecha_fin' => $phase->pivot->end_date ?? null,
                'estado_badge' => $this->getEstadoBadge($phase->pivot->start_date, $phase->pivot->end_date),
                'estado_label' => $this->getEstadoLabel($phase->pivot->start_date, $phase->pivot->end_date),
            ];
        }) ?? collect();

        return view('home.etapas', compact('competicion', 'etapas'));
    }

    private function getEstadoBadge($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin) return 'badge-default';
        
        $now = now();
        $inicio = \Carbon\Carbon::parse($fechaInicio);
        $fin = \Carbon\Carbon::parse($fechaFin);

        if ($now->lt($inicio)) return 'badge-pending';
        if ($now->between($inicio, $fin)) return 'badge-active';
        return 'badge-completed';
    }

    private function getEstadoLabel($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin) return 'Sin fechas';
        
        $now = now();
        $inicio = \Carbon\Carbon::parse($fechaInicio);
        $fin = \Carbon\Carbon::parse($fechaFin);

        if ($now->lt($inicio)) return 'Próximamente';
        if ($now->between($inicio, $fin)) return 'En curso';
        return 'Finalizada';
    }
}