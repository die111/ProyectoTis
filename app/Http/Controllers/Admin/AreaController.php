<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $areas = Area::query()->with('competitionCategoryAreas');
        if ($query) {
            $areas->where('name', 'like', "%$query%");
        }
        $areas = $areas->paginate(10);
        return view('admin.areas.index', compact('areas'));
    }

    public function bulkActivate(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Area::whereIn('id', explode(',', $ids))->update(['is_active' => true]);
        }
        return redirect()->route('admin.areas.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Áreas activadas correctamente.'
        ]);
    }

    public function bulkDeactivate(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Area::whereIn('id', explode(',', $ids))->update(['is_active' => false]);
        }
        return redirect()->route('admin.areas.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Áreas desactivadas correctamente.'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:areas,name',
            'description' => 'required|string|max:255',
        ]);

        $area = new Area();
        $area->name = $validated['name'];
        $area->description = $validated['description'];
        $area->save();

        return redirect()->route('admin.areas.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Área creada correctamente.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:areas,name,' . $id,
            'description' => 'required|string|max:255',
        ]);

        $area = Area::findOrFail($id);
        $area->name = $validated['name'];
        $area->description = $validated['description'];
        $area->save();

        return redirect()->route('admin.areas.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Área actualizada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        
        // Verificar si hay usuarios asociados al área
        if ($area->users()->count() > 0) {
            return redirect()->route('admin.areas.index')
            ->with([
                'swal_custom' => true,
                'swal_title' => 'Error',
                'swal_icon' => 'error',
                'swal_text' => 'No se puede eliminar el área porque hay usuarios asignados. Los usuarios serán desvinculados del área si continúa.'
            ]);
        }
        
        // Verificar si el área está siendo usada en competiciones
        if ($area->isInUse()) {
            return redirect()->route('admin.areas.index')
            ->with([
                'swal_custom' => true,
                'swal_title' => 'Error',
                'swal_icon' => 'error',
                'swal_text' => 'No se puede eliminar el área porque está siendo utilizada en competiciones.'
            ]);
        }
        
        try {
            $area->delete();
            return redirect()->route('admin.areas.index')
            ->with([
                'swal_custom' => true,
                'swal_title' => '¡Éxito!',
                'swal_icon' => 'success',
                'swal_text' => 'Área eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.areas.index')
            ->with([
                'swal_custom' => true,
                'swal_title' => 'Error',
                'swal_icon' => 'error',
                'swal_text' => 'No se puede eliminar el área debido a restricciones de integridad.'
            ]);
        }
    }

    public function create()
    {
        return view('admin.areas.create-area');
    }

    public function edit($id)
    {
        $area = \App\Models\Area::findOrFail($id);
        return view('admin.areas.edit-area', compact('area'));
    }
}
