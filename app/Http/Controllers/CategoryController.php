<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Mostrar la lista de categorías
    public function index()
    {
        // Traemos las categorías y contamos cuántos productos tiene cada una
        $categories = Category::withCount('products')->get();
        
        return view('categories.index', compact('categories'));
    }

    // 2. Guardar una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        Category::create([
            'name' => $request->name
        ]);

        // "back()" nos devuelve a la misma página para seguir creando
        return back()->with('success', 'Categoría creada correctamente.');
    }

    // 3. Borrar categoría (Opcional, pero útil)
    public function destroy(Category $category)
    {
        // Opcional: Validar si tiene productos antes de borrar
        if($category->products_count > 0){
             return back()->with('error', 'No puedes borrar una categoría que tiene productos.');
        }

        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
    
}