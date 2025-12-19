<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // 1. Mostrar filtro (Seleccionar Categoría)
    public function index()
    {
        $categories = \App\Models\Category::all();
        return view('inventory.index', compact('categories'));
    }

    // 2. Generar el PDF
    public function print(Request $request)
    {
        $query = \App\Models\Product::query();

        // Si seleccionó una categoría específica
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
            $categoriaNombre = \App\Models\Category::find($request->category_id)->name;
        } else {
            $categoriaNombre = 'TODAS LAS CATEGORÍAS';
        }

        $products = $query->orderBy('name')->get();
        
        $pdf = \PDF::loadView('reports.inventory_pdf', compact('products', 'categoriaNombre'));
        return $pdf->stream('inventario.pdf');
    }
}
