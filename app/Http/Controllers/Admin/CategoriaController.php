<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\CompetitionCategoryArea;
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
            // Limpiar y normalizar la búsqueda
            $searchTerm = trim($q);
            
            // Función para remover acentos
            $normalizeString = function($str) {
                $unwanted = [
                    'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ä' => 'a', 'Ä' => 'A',
                    'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ë' => 'e', 'Ë' => 'E',
                    'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'ï' => 'i', 'Ï' => 'I',
                    'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ö' => 'o', 'Ö' => 'O',
                    'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ü' => 'u', 'Ü' => 'U',
                    'ñ' => 'n', 'Ñ' => 'N'
                ];
                return strtr($str, $unwanted);
            };
            
            // Normalizar el término de búsqueda
            $normalizedSearch = $normalizeString($searchTerm);
            
            // Dividir en palabras para búsqueda más flexible
            $words = preg_split('/\s+/', $normalizedSearch);
            
            $categories = $categories->where(function($queryBuilder) use ($words, $searchTerm, $normalizedSearch) {
                // Buscar coincidencia directa (con acentos)
                $queryBuilder->where('nombre', 'like', "%{$searchTerm}%")
                            ->orWhere('descripcion', 'like', "%{$searchTerm}%");
                
                // Buscar coincidencia sin acentos usando REPLACE anidados
                $queryBuilder->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(LOWER(nombre), 
                    'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'), 
                    'à', 'a'), 'è', 'e'), 'ì', 'i'), 'ò', 'o'), 'ù', 'u'),
                    'ä', 'a'), 'ë', 'e'), 'ï', 'i'), 'ö', 'o'), 'ü', 'u'),
                    'ñ', 'n'), 'â', 'a'), 'ê', 'e') LIKE ?", ["%{$normalizedSearch}%"])
                            ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    REPLACE(REPLACE(REPLACE(LOWER(descripcion), 
                    'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'), 
                    'à', 'a'), 'è', 'e'), 'ì', 'i'), 'ò', 'o'), 'ù', 'u'),
                    'ä', 'a'), 'ë', 'e'), 'ï', 'i'), 'ö', 'o'), 'ü', 'u'),
                    'ñ', 'n'), 'â', 'a'), 'ê', 'e') LIKE ?", ["%{$normalizedSearch}%"]);
                
                // Si hay múltiples palabras, buscar cada palabra individualmente
                if (count($words) > 1) {
                    foreach ($words as $word) {
                        if (strlen($word) > 2) { // Solo palabras de más de 2 caracteres
                            $queryBuilder->orWhere('nombre', 'like', "%{$word}%")
                                        ->orWhere('descripcion', 'like', "%{$word}%")
                                        ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                                REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                                REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                                REPLACE(REPLACE(REPLACE(LOWER(nombre), 
                                'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'), 
                                'à', 'a'), 'è', 'e'), 'ì', 'i'), 'ò', 'o'), 'ù', 'u'),
                                'ä', 'a'), 'ë', 'e'), 'ï', 'i'), 'ö', 'o'), 'ü', 'u'),
                                'ñ', 'n'), 'â', 'a'), 'ê', 'e') LIKE ?", ["%{$word}%"]);
                        }
                    }
                }
            });
        }
        
        // Paginación
        $categories = $categories->paginate(10);
        
        // Verificar qué categorías están en uso en competiciones
        $categoriesInUse = CompetitionCategoryArea::whereIn('categoria_id', $categories->pluck('id'))
            ->distinct()
            ->pluck('categoria_id')
            ->toArray();
        
        return view('admin.categorias.index', compact('categories', 'q', 'categoriesInUse'));
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
