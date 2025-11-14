<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener datos reales de la base de datos
        $q = request('q', '');
        
        $categories = Categoria::query();
        
        // Filtrar por búsqueda si existe
        if ($q) {
            $categories = $categories->where(function($queryBuilder) use ($q) {
                $queryBuilder->where('nombre', 'like', "%{$q}%")
                            ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }
        
        // Paginación
        $categories = $categories->paginate(10);
        
        return view('admin.categorias.index', compact('categories', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los niveles
        $levels = \App\Models\Level::all();
        return view('admin.categorias.create', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'niveles' => 'required|string'
        ]);

        // Preparar los datos para guardar
        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'primero' => false,
            'segundo' => false,
            'tercero' => false,
            'cuarto' => false,
            'quinto' => false,
            'sexto' => false,
            'is_active' => true
        ];

        // Procesar los niveles seleccionados
        if ($request->niveles) {
            $nivelesSeleccionados = explode(',', $request->niveles);
            
            foreach ($nivelesSeleccionados as $nivel) {
                switch (trim($nivel)) {
                    case 'primero':
                        $data['primero'] = true;
                        break;
                    case 'segundo':
                        $data['segundo'] = true;
                        break;
                    case 'tercero':
                        $data['tercero'] = true;
                        break;
                    case 'cuarto':
                        $data['cuarto'] = true;
                        break;
                    case 'quinto':
                        $data['quinto'] = true;
                        break;
                    case 'sexto':
                        $data['sexto'] = true;
                        break;
                }
            }
        }

        try {
            // Crear la categoría
            Categoria::create($data);
            
            return redirect()->route('admin.categorias.index')
                           ->with('success', 'Categoría creada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear la categoría. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function activate($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->is_active = true;
            $categoria->save();
            
            return back()->with('success', 'La categoría ha sido activada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al activar la categoría. Por favor, inténtalo de nuevo.');
        }
    }

    public function deactivate($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->is_active = false;
            $categoria->save();
            
            return back()->with('success', 'La categoría ha sido desactivada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al desactivar la categoría. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'niveles' => 'required|string'
        ]);

        // Preparar los datos para actualizar
        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'primero' => false,
            'segundo' => false,
            'tercero' => false,
            'cuarto' => false,
            'quinto' => false,
            'sexto' => false
        ];

        // Procesar los niveles seleccionados
        if ($request->niveles) {
            $nivelesSeleccionados = explode(',', $request->niveles);
            
            foreach ($nivelesSeleccionados as $nivel) {
                switch (trim($nivel)) {
                    case 'primero':
                        $data['primero'] = true;
                        break;
                    case 'segundo':
                        $data['segundo'] = true;
                        break;
                    case 'tercero':
                        $data['tercero'] = true;
                        break;
                    case 'cuarto':
                        $data['cuarto'] = true;
                        break;
                    case 'quinto':
                        $data['quinto'] = true;
                        break;
                    case 'sexto':
                        $data['sexto'] = true;
                        break;
                }
            }
        }

        try {
            // Buscar y actualizar la categoría
            $categoria = Categoria::findOrFail($id);
            $categoria->update($data);
            
            return redirect()->route('admin.categorias.index')
                           ->with('success', 'Categoría actualizada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar la categoría. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
