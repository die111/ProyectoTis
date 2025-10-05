<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Phase;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    public function index()
    {
        $phases = Phase::orderBy('name')->paginate(10);
        return view('admin.etapas.index', compact('phases'));
    }

    public function create()
    {
        return view('admin.etapas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'clasificados' => 'required|integer|min:1',
        ]);
        Phase::create($request->all());
        return redirect()->route('admin.etapas.index')->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase creada exitosamente'
        ]);
    }

    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
        return view('admin.etapas.edit', compact('phase'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'clasificados' => 'required|integer|min:1',
        ]);
        $phase = Phase::findOrFail($id);
        $phase->update($request->all());
       
         return redirect()->route('admin.etapas.index')->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase actualizada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $phase = Phase::findOrFail($id);
        $phase->delete();
        return redirect()->route('admin.etapas.index')
        ->with('success', 'Fase eliminada correctamente.');
    }
}
