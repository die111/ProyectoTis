<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClasificadosController extends Controller
{
    public function index(Request $request) {
        return view('clasificados');
    }
}
