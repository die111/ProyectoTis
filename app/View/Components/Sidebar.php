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
        // Defer building menu items until render time to avoid DB access during bootstrap/tests
        // and handle cases where there is no authenticated user.
        if (is_null($this->user)) {
            $this->menuItems = [];
            return;
        }
        $this->menuItems = [];
    }

    private function getMenuItems(): array
    {
        /** @var \App\Models\User|null $user */
        $user = $this->user;
        $items = [];
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
            'categorias' => [
                'name' => 'Gestión de Categorías',
                'route' => 'admin.categorias.index',
                'icon' => 'fas fa-tags',
                'active' => $this->isRouteActive(['admin.categorias.*'])
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
            ],
        ];
        foreach ($menuConfig as $perm => $item) {
            if (in_array($perm, $permissions)) {
                // Si tiene submenú, filtra los submenús por permisos
                if (isset($item['submenu'])) {
                    $submenu = array_filter($item['submenu'], function($sub) use ($permissions) {
                        // Si quieres permisos separados por subítem, aquí puedes personalizar
                        return $sub !== null;
                    });
                    if (count($submenu)) {
                        $item['submenu'] = $submenu;
                        $items[] = $item;
                    }
                } else {
                    $items[] = $item;
                }
            }
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
        // Populate menu items lazily and defensively; fail closed on errors
        try {
            if (empty($this->menuItems) && $this->user) {
                $this->menuItems = $this->getMenuItems();
            }
        } catch (\Throwable $e) {
            $this->menuItems = [];
        }

        return view('components.sidebar', ['menuItems' => $this->menuItems, 'user' => $this->user]);
    }
}
