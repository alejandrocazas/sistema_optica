<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Listar productos con sus categorías
    public function index(Request $request)
{
    // 1. Iniciamos la consulta cargando la relación 'category' para optimizar
    $query = \App\Models\Product::with('category')->latest();

    // 2. Lógica del Buscador (Si escriben algo en el input)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")       // Buscar por nombre
              ->orWhere('code', 'like', "%{$search}%");    // Buscar por código
        });
    }

    // 3. Paginamos (10 productos por página)
    // Importante: Usar paginate() es lo que habilita el método ->links() en la vista
    $products = $query->paginate(10);

    return view('products.index', compact('products'));
}

    // Formulario de creación (Necesitamos enviar las categorías para el Select)
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Guardar producto
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'code' => 'required|unique:products',
            'name' => 'required',
            'stock' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'image' => 'nullable|image|max:2048' // Máximo 2MB
        ]);

        $data = $request->all();

        // Subir Imagen
        if ($request->hasFile('image')) {
            // Se guarda en storage/app/public/products
            $path = $request->file('image')->store('products', 'public'); 
            $data['image_path'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto registrado.');
    }

    // Borrar producto e imagen
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('products.index');
    }
    // Muestra el formulario de edición
    public function edit(Product $product)
    {
        $categories = Category::all(); // Necesitamos la lista para el select
        return view('products.edit', compact('product', 'categories'));
    }

    // Guarda los cambios
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required',
            'code' => 'required|unique:products,code,'.$product->id, // Ignorar el propio ID al validar único
            'name' => 'required',
            'price_buy' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('image'); // Tomamos todos los datos MENOS la imagen por ahora

        // Lógica de la Imagen
        if ($request->hasFile('image')) {
            // 1. Borrar foto anterior si existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            // 2. Guardar nueva foto
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }
    // Muestra el formulario de edición
    
    // Método rápido para guardar categoría desde el mismo formulario (AJAX o simple)
    // Por ahora lo haremos simple, usaremos un controlador aparte para categorias si deseas, 
    // o un método storeCategory aquí mismo si queremos simplificar.
}
