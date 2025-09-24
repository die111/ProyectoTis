<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    public function index()
    {
        return view('admin.etapas.index');
    }
}
