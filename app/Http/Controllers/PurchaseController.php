<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        // Traemos las compras con el usuario y la sucursal para saber a dónde fue
        $purchases = \App\Models\Purchase::with(['user', 'branch'])
                        ->latest()
                        ->paginate(20);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $branches = \App\Models\Branch::all(); 

        return view('purchases.create', compact('products', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string',
            'purchase_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'products' => 'required|array', 
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 1. Crear Cabecera de Compra
                $purchase = \App\Models\Purchase::create([
                    'user_id' => auth()->id(),
                    'branch_id' => $request->branch_id,
                    'supplier' => $request->supplier,
                    'purchase_date' => $request->purchase_date,
                    'total_cost' => 0 // <--- CORRECCIÓN 1: Usamos 'total_cost' para evitar el error SQL
                ]);

                $totalCompra = 0;

                // 2. Procesar cada producto
                foreach ($request->products as $item) {
                    $subtotal = $item['quantity'] * $item['cost'];
                    $totalCompra += $subtotal;

                    // Guardar detalle
                    \App\Models\PurchaseDetail::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost']
                    ]);

                    // ---------------------------------------------------------
                    // 3. ACTUALIZACIÓN DE STOCK (GLOBAL Y SUCURSAL)
                    // ---------------------------------------------------------
                    $product = \App\Models\Product::find($item['id']);

                    // A. Actualizar precio de compra global
                    $product->update(['price_buy' => $item['cost']]);

                    // B. CORRECCIÓN 2: Sumar al stock GLOBAL (Tabla products)
                    // Esto arregla el desfase que tenías en el Dashboard vs Sucursal
                    $product->increment('stock', $item['quantity']); 

                    // C. Actualizar STOCK ESPECÍFICO DE LA SUCURSAL (Tabla branch_product)
                    $branchProduct = $product->branches()->where('branch_id', $request->branch_id)->first();

                    if ($branchProduct) {
                        // SI EXISTE: Sumamos al stock actual de esa sucursal
                        $newStock = $branchProduct->pivot->stock + $item['quantity'];
                        $product->branches()->updateExistingPivot($request->branch_id, [
                            'stock' => $newStock
                        ]);
                    } else {
                        // NO EXISTE: Creamos el registro en esa sucursal
                        $product->branches()->attach($request->branch_id, [
                            'stock' => $item['quantity']
                        ]);
                    }
                }

                // 4. Actualizar el total final de la compra
                $purchase->update(['total_cost' => $totalCompra]);
            });

            return redirect()->route('purchases.index')->with('success', 'Compra registrada y stock actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage())->withInput();
        }
    }
}