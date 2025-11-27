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
        // Mostrar la vista `dashboard.default` para todos los roles por defecto.
        return view('dashboard.default');
    }

    // Los dashboards específicos por rol fueron removidos: ahora se usa `dashboard.default` para todos los roles.
}