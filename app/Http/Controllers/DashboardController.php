<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */

    public function index(): View
    {
        $user = Auth::user();
        
        return match($user->role) {
            'admin' => $this->adminDashboard(),
            'responsable_area' => $this->responsableDashboard(),
            'evaluador' => $this->evaluadorDashboard(),
            'coordinador' => $this->coordinadorDashboard(),
            default => view('dashboard.default')
        };
    }

    private function adminDashboard(): View
    {
        $stats = [
            'total_olimpistas' => 0,
            'total_evaluaciones' => 0,
            'areas_activas' => 0,
            'usuarios_activos' => User::where('is_active', true)->count()
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function responsableDashboard(): View
    {
        $user = Auth::user();
        
        // Estadísticas específicas del área
        $stats = [
            'olimpistas_area' => 0, 
            'evaluaciones_pendientes' => 0,
            'evaluadores_asignados' => \App\Models\User::where('role', 'evaluador')
                ->where('area', $user->area)
                ->count(),
            'clasificados' => 0
        ];
        
        return view('dashboard.responsable', compact('stats'));
    }

    private function evaluadorDashboard(): View
    {
        $user = Auth::user();
        
        // Estadísticas del evaluador
        $stats = [
            'evaluaciones_asignadas' => 0,
            'evaluaciones_completadas' => 0,
            'olimpistas_evaluados' => 0,
            'promedio_notas' => 0
        ];

        return view('dashboard.evaluador', compact('stats'));
    }

    private function coordinadorDashboard(): View
    {
        // Estadísticas generales de coordinador
        $stats = [
            'areas_total' => 0, 
            'evaluaciones_proceso' => 0,
            'evaluaciones_completadas' => 0,
            'progreso_general' => 0
        ];

        return view('dashboard.coordinador', compact('stats'));
    }
}