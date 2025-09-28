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
        $areas = Area::query();
        if ($query) {
            $areas->where('nombre', 'like', "%$query%");
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
        return redirect()->route('admin.areas.index')->with('success', 'Áreas activadas correctamente.');
    }

    public function bulkDeactivate(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            Area::whereIn('id', explode(',', $ids))->update(['is_active' => false]);
        }
        return redirect()->route('admin.areas.index')->with('success', 'Áreas desactivadas correctamente.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $area = new Area();
        $area->name = $validated['name'];
        $area->description = $validated['description'];
        $area->save();

        return redirect()->route('admin.areas.index')->with('success', 'Área creada correctamente.');
    }
}
