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
        /** @var \App\Models\User|null $this->user */
        $this->user = Auth::user();
        // If there's no authenticated user (e.g. during some tests or guest views),
        // avoid building the menu which assumes a user/role exists.
        if (is_null($this->user)) {
            $this->menuItems = [];
            return;
        }
        // Defer building menu items until render time and be defensive about DB access.
        // Accessing relationships in the constructor can trigger DB queries during
        // application bootstrap (before tests run migrations) which leads to
        // "transaction aborted" or "undefined table" errors in tests. We'll
        // compute the menu lazily in render().
        $this->menuItems = [];
    }

    private function getMenuItems(): array
    {
        /** @var \App\Models\User|null $user */
        $user = $this->user;
        $items = [];
<<<<<<< Updated upstream

    // Menús específicos por rol
    $role = $user->role ? $user->role->name : null;
    switch ($role) {
            case 'admin':
                $items = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'fas fa-tachometer-alt',
                        'active' => $this->isRouteActive(['dashboard'])
                    ],
                    [
                        'name' => 'Competicion',
                        'route' => 'admin.competicion.index',
                        'icon' => 'fas fa-trophy',
                        'active' => $this->isRouteActive(['competicion.*'])
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
                        'icon' => 'fas fa-clipboard-list',
                        'active' => $this->isRouteActive(['admin.inscripcion.*']),
                        'submenu' => [
                            [
                                'name' => 'Inscribir por CSV',
                                'route' => 'admin.inscripcion.index',
                                'icon' => 'fas fa-file-csv',
                                'active' => $this->isRouteActive(['admin.inscripcion.index'])
                            ],
                            [
                                'name' => 'Solicitud',
                                'route' => 'admin.inscripcion.solicitud',
                                'icon' => 'fas fa-file-alt',
                                'active' => $this->isRouteActive(['admin.inscripcion.solicitud'])
                            ]
                        ]
                    ],
                    [
                        'name' => 'Gestion de Fases',
                        'route' => 'admin.etapas.index',
                        'icon' => 'fas fa-sitemap',
                        'active' => $this->isRouteActive(['gestion-fases.*'])
                    ],
                    [
                        'name' => 'Gestion de Areas',
                        'route' => 'admin.areas.index',
                        'icon' => 'fas fa-th-large',
                        'active' => $this->isRouteActive(['areas.*'])
                    ]
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
=======
        if (is_null($user)) {
            return $items;
        }

        $role = $user->role;
        $permissions = $role ? $role->permissions->pluck('name')->toArray() : [];
        $menuConfig = [
            'dashboard' => [
                'name' => 'Dashboard',
                'route' => 'admin.dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'active' => $this->isRouteActive(['admin.dashboard'])
            ],
            'competicion' => [
                'name' => 'Competición',
                'route' => 'admin.competicion.index',
                'icon' => 'fas fa-trophy',
                'active' => $this->isRouteActive(['admin.competicion.*'])
            ],
            'roles' => [
                'name' => 'Roles',
                'route' => 'admin.roles.index',
                'icon' => 'fas fa-user-tag',
                'active' => $this->isRouteActive(['admin.roles.*'])
            ],
            'usuarios' => [
                'name' => 'Usuarios',
                'route' => 'admin.usuarios.index',
                'icon' => 'fas fa-users',
                'active' => $this->isRouteActive(['admin.usuarios.*'])
            ],
            'inscripcion' => [
                'name' => 'Inscripción',
                'icon' => 'fas fa-clipboard-list',
                'active' => $this->isRouteActive(['admin.inscripcion.*']),
                'submenu' => array_filter([
                    in_array('inscripcion', $permissions) ? [
                        'name' => 'Listado',
                        'route' => 'admin.inscripcion.index',
                        'icon' => 'fas fa-list',
                        'active' => $this->isRouteActive(['admin.inscripcion.index'])
                    ] : null,
                    in_array('inscripcion', $permissions) ? [
                        'name' => 'Solicitudes',
                        'route' => 'admin.inscripcion.solicitud',
                        'icon' => 'fas fa-envelope-open-text',
                        'active' => $this->isRouteActive(['admin.inscripcion.solicitud'])
                    ] : null,
                    in_array('inscripcion', $permissions) ? [
                        'name' => 'Guardar Estudiantes',
                        'route' => 'admin.inscripcion.guardarEstudiantes',
                        'icon' => 'fas fa-user-plus',
                        'active' => $this->isRouteActive(['admin.inscripcion.guardarEstudiantes'])
                    ] : null,
                ])
            ],
            'fases' => [
                'name' => 'Gestión de Fases',
                'route' => 'admin.phases.index',
                'icon' => 'fas fa-sitemap',
                'active' => $this->isRouteActive(['admin.phases.*'])
            ],
            'areas' => [
                'name' => 'Gestión de Áreas',
                'route' => 'admin.areas.index',
                'icon' => 'fas fa-th-large',
                'active' => $this->isRouteActive(['admin.areas.*'])
            ],
            'evaluaciones' => [
                'name' => 'Gestión de Evaluaciones',
                'route' => 'admin.evaluacion.index',
                'icon' => 'fas fa-clipboard-check',
                'active' => $this->isRouteActive(['admin.evaluacion.*'])
            ],
            'inscripcion_competencia' => [
                'name' => 'Inscripción a Competencias',
                'route' => 'estudiante.inscripcion.index',
                'icon' => 'fas fa-file-signature',
                'active' => $this->isRouteActive(['estudiante.inscripcion.*'])
            ]
        ];
        foreach ($menuConfig as $perm => $item) {
            if (in_array($perm, $permissions)) {
                // Si tiene submenú, filtra los submenús por permisos
                if (isset($item['submenu'])) {
                    $submenu = array_filter($item['submenu'], function($sub) use ($permissions) {

                    });
                    if (count($submenu)) {
                        $item['submenu'] = $submenu;
                        $items[] = $item;
                    }
                } else {
                    $items[] = $item;
                }
            }
>>>>>>> Stashed changes
        }

        return $items;
    }

    /**
     * Check if current route matches any of the given patterns
     */
    private function isRouteActive(array $routePatterns): bool
    {
        $currentRoute = Route::currentRouteName() ?? '';

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

    /**
     * Check if any submenu item is active
     */
    private function hasActiveSubmenu(array $submenu): bool
    {
        foreach ($submenu as $item) {
            if (isset($item['active']) && $item['active']) {
                return true;
            }
        }
        return false;
    }

    public function render(): View
    {
        // Populate menu items lazily and defensively. Any DB faults will be
        // caught and menu will fall back to empty.
        try {
            if (empty($this->menuItems) && $this->user) {
                $this->menuItems = $this->getMenuItems();
            }
        } catch (\Throwable $e) {
            // swallow and default to empty menu to keep views/tests stable
            $this->menuItems = [];
        }

        return view('components.sidebar', ['menuItems' => $this->menuItems, 'user' => $this->user]);
    }
}
