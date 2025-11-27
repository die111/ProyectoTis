<?php

return [
    'quick_access' => [
        'competicion' => [
            'title' => 'Competiciones',
            'description' => 'Gestionar competiciones',
            'icon' => 'fa-trophy',
            'route' => 'admin.competicion.index',
            'color' => 'blue'
        ],
        'roles' => [
            'title' => 'Roles',
            'description' => 'Gestionar roles y permisos',
            'icon' => 'fa-user-shield',
            'route' => 'admin.roles.index',
            'color' => 'purple'
        ],
        'usuarios' => [
            'title' => 'Usuarios',
            'description' => 'Gestionar usuarios del sistema',
            'icon' => 'fa-users',
            'route' => 'admin.usuarios.index',
            'color' => 'green'
        ],
        'inscripcion' => [
            'title' => 'Inscripciones',
            'description' => 'Gestionar inscripciones',
            'icon' => 'fa-clipboard-list',
            'route' => 'admin.inscripcion.index',
            'color' => 'orange'
        ],
        'fases' => [
            'title' => 'Fases',
            'description' => 'Gestionar fases de competición',
            'icon' => 'fa-stream',
            'route' => 'admin.phases.index',
            'color' => 'indigo'
        ],
        'areas' => [
            'title' => 'Áreas',
            'description' => 'Gestionar áreas académicas',
            'icon' => 'fa-shapes',
            'route' => 'admin.areas.index',
            'color' => 'teal'
        ],
        'reclamos' => [
            'title' => 'Reclamos',
            'description' => 'Gestionar reclamos',
            'icon' => 'fa-exclamation-circle',
            'route' => 'admin.reclamos.index',
            'color' => 'red'
        ],
        'categorias' => [
            'title' => 'Categorías',
            'description' => 'Gestionar categorías',
            'icon' => 'fa-tags',
            'route' => 'admin.categorias.index',
            'color' => 'pink'
        ],
        'evaluaciones' => [
            'title' => 'Evaluaciones',
            'description' => 'Gestionar evaluaciones',
            'icon' => 'fa-star',
            'route' => 'admin.evaluacion.index',
            'color' => 'yellow'
        ],
        'inscripcion_competencia' => [
            'title' => 'Mis Inscripciones',
            'description' => 'Ver y gestionar mis inscripciones',
            'icon' => 'fa-edit',
            'route' => 'estudiante.inscripcion.index',
            'color' => 'cyan'
        ],
    ],

    'color_classes' => [
        'blue' => 'bg-blue-500 hover:bg-blue-600',
        'purple' => 'bg-purple-500 hover:bg-purple-600',
        'green' => 'bg-green-500 hover:bg-green-600',
        'orange' => 'bg-orange-500 hover:bg-orange-600',
        'indigo' => 'bg-indigo-500 hover:bg-indigo-600',
        'teal' => 'bg-teal-500 hover:bg-teal-600',
        'red' => 'bg-red-500 hover:bg-red-600',
        'pink' => 'bg-pink-500 hover:bg-pink-600',
        'yellow' => 'bg-yellow-500 hover:bg-yellow-600',
        'cyan' => 'bg-cyan-500 hover:bg-cyan-600',
    ],
];
