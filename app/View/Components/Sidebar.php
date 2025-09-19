<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $user;
    public $menuItems;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->menuItems = $this->getMenuItems();
    }

    private function getMenuItems(): array
    {
        $user = $this->user;
        $items = [];

        // Dashboard común para todos
        $items[] = [
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-home',
            'active' => request()->routeIs('dashboard*')
        ];

        // Menús específicos por rol
        switch ($user->role) {
            case 'admin':
                $items = array_merge($items, [
                    [
                        'name' => 'Usuarios',
                        'route' => 'users.index',
                        'icon' => 'fas fa-users',
                        'active' => request()->routeIs('users*')
                    ],
                    [
                        'name' => 'Olimpistas',
                        'route' => 'olimpistas.index',
                        'icon' => 'fas fa-user-graduate',
                        'active' => request()->routeIs('olimpistas*')
                    ],
                    [
                        'name' => 'Áreas',
                        'route' => 'areas.index',
                        'icon' => 'fas fa-layer-group',
                        'active' => request()->routeIs('areas*')
                    ],
                    [
                        'name' => 'Evaluaciones',
                        'route' => 'evaluaciones.index',
                        'icon' => 'fas fa-clipboard-check',
                        'active' => request()->routeIs('evaluaciones*')
                    ],
                    [
                        'name' => 'Reportes',
                        'route' => 'reportes.index',
                        'icon' => 'fas fa-chart-bar',
                        'active' => request()->routeIs('reportes*')
                    ],
                    [
                        'name' => 'Configuración',
                        'route' => 'config.index',
                        'icon' => 'fas fa-cog',
                        'active' => request()->routeIs('config*')
                    ]
                ]);
                break;

            case 'responsable_area':
                $items = array_merge($items, [
                    [
                        'name' => 'Mi Área',
                        'route' => 'responsable.area',
                        'icon' => 'fas fa-layer-group',
                        'active' => request()->routeIs('responsable.area*')
                    ],
                    [
                        'name' => 'Evaluadores',
                        'route' => 'responsable.evaluadores',
                        'icon' => 'fas fa-users',
                        'active' => request()->routeIs('responsable.evaluadores*')
                    ],
                    [
                        'name' => 'Evaluaciones',
                        'route' => 'responsable.evaluaciones',
                        'icon' => 'fas fa-clipboard-check',
                        'active' => request()->routeIs('responsable.evaluaciones*')
                    ],
                    [
                        'name' => 'Clasificados',
                        'route' => 'responsable.clasificados',
                        'icon' => 'fas fa-trophy',
                        'active' => request()->routeIs('responsable.clasificados*')
                    ],
                    [
                        'name' => 'Certificados',
                        'route' => 'responsable.certificados',
                        'icon' => 'fas fa-certificate',
                        'active' => request()->routeIs('responsable.certificados*')
                    ]
                ]);
                break;

            case 'evaluador':
                $items = array_merge($items, [
                    [
                        'name' => 'Mis Evaluaciones',
                        'route' => 'evaluador.evaluaciones',
                        'icon' => 'fas fa-clipboard-check',
                        'active' => request()->routeIs('evaluador.evaluaciones*')
                    ],
                    [
                        'name' => 'Olimpistas',
                        'route' => 'evaluador.olimpistas',
                        'icon' => 'fas fa-user-graduate',
                        'active' => request()->routeIs('evaluador.olimpistas*')
                    ]
                ]);
                break;

            case 'coordinador':
                $items = array_merge($items, [
                    [
                        'name' => 'Seguimiento',
                        'route' => 'coordinador.seguimiento',
                        'icon' => 'fas fa-tasks',
                        'active' => request()->routeIs('coordinador.seguimiento*')
                    ],
                    [
                        'name' => 'Reportes',
                        'route' => 'coordinador.reportes',
                        'icon' => 'fas fa-chart-line',
                        'active' => request()->routeIs('coordinador.reportes*')
                    ]
                ]);
                break;
        }

        return $items;
    }

    public function render(): View
    {
        return view('components.sidebar');
    }
}