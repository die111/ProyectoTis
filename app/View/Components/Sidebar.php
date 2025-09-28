<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

        // Menús específicos por rol
        switch ($user->role) {
            case 'admin':
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ],
                    [
                        'name' => 'Competición',
                        'route' => '#',
                        'icon' => 'fas fa-trophy',
                        'active' => $this->isRouteActive(['admin.competicion.index', 'admin.etapas.*']),
                        'submenu' => [
                            [
                                'name' => 'Panel de Competición',
                                'route' => 'admin.competicion.index',
                                'icon' => 'fas fa-trophy',
                                'active' => $this->isRouteActive(['admin.competicion.index'])
                            ],
                            [
                                'name' => 'Fases',
                                'route' => 'admin.etapas.index',
                                'icon' => 'fas fa-sitemap',
                                'active' => $this->isRouteActive(['admin.etapas.*'])
                            ],
                        ]
                    ],
                    [
                        'name' => 'Roles',
                        'route' => 'admin.roles.index',
                        'icon' => 'fas fa-user-tag',
                        'active' => $this->isRouteActive(['roles.*'])
                    ],
                    [
                        'name' => 'Usuarios',
                        'route' => 'admin.usuarios.index',
                        'icon' => 'fas fa-users',
                        'active' => $this->isRouteActive(['usuarios.*'])
                    ],
                    [
                        'name' => 'Inscripción',
                        'route' => 'admin.inscripcion.index',
                        'icon' => 'fas fa-clipboard-list',
                        'active' => $this->isRouteActive(['inscripcion.*'])
                    ],
                ];
                break;

            case 'responsable_area':
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ],
                    [
                        'name' => 'Solicitud Inscripcion',
                        'route' => '#',
                        'icon' => 'fas fa-file-alt',
                        'active' => $this->isRouteActive(['responsable.inscripciones.*'])
                    ],
                    [
                        'name' => 'Registrar CSV',
                        'route' => '#',
                        'icon' => 'fas fa-file-csv',
                        'active' => $this->isRouteActive(['responsable.csv.*'])
                    ],
                    [
                        'name' => 'Modificar Usuario',
                        'route' => '#',
                        'icon' => 'fas fa-user-edit',
                        'active' => $this->isRouteActive(['responsable.usuarios.*'])
                    ],
                    [
                        'name' => 'Fases',
                        'route' => '#',
                        'icon' => 'fas fa-list-ol',
                        'active' => $this->isRouteActive(['responsable.fases.*'])
                    ],
                    [
                        'name' => 'Listas',
                        'route' => '#',
                        'icon' => 'fas fa-list',
                        'active' => $this->isRouteActive(['responsable.listas.*'])
                    ],
                    [
                        'name' => 'Premiados',
                        'route' => '#',
                        'icon' => 'fas fa-trophy',
                        'active' => $this->isRouteActive(['responsable.premiados.*'])
                    ],
                    [
                        'name' => 'Reclamos',
                        'route' => '#',
                        'icon' => 'fas fa-exclamation-triangle',
                        'active' => $this->isRouteActive(['responsable.reclamos.*'])
                    ],
                ];
                break;

            case 'evaluador':
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ],
                    [
                        'name' => 'Mis Evaluaciones',
                        'route' => '#',
                        'icon' => 'fas fa-clipboard-check',
                        'active' => $this->isRouteActive(['evaluador.evaluaciones.*'])
                    ],
                    [
                        'name' => 'Olimpistas',
                        'route' => '#',
                        'icon' => 'fas fa-user-graduate',
                        'active' => $this->isRouteActive(['evaluador.olimpistas.*'])
                    ]
                ];
                break;

            case 'coordinador':
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ],
                    [
                        'name' => 'Seguimiento',
                        'route' => '#',
                        'icon' => 'fas fa-tasks',
                        'active' => $this->isRouteActive(['coordinador.seguimiento.*'])
                    ],
                    [
                        'name' => 'Reportes',
                        'route' => '#',
                        'icon' => 'fas fa-chart-line',
                        'active' => $this->isRouteActive(['coordinador.reportes.*'])
                    ]
                ];
                break;

            default:
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ]
                ];
        }

        return $items;
    }

    /**
     * Check if current route matches any of the given patterns
     */
    private function isRouteActive(array $routePatterns): bool
    {
        $currentRoute = Route::currentRouteName();

        foreach ($routePatterns as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '', $pattern);
                if (str_starts_with($currentRoute, $pattern)) {
                    return true;
                }
            } else {
                if ($currentRoute === $pattern) {
                    return true;
                }
            }
        }

        return false;
    }

    public function render(): View
    {
        return view('components.sidebar');
    }
}
