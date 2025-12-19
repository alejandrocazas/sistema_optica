<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('users')->get(); // Contamos cuántos empleados hay en cada una
        return view('branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        Branch::create($request->all());

        return redirect()->back()->with('success', 'Sucursal creada correctamente.');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $branch->update($request->all());

        return redirect()->back()->with('success', 'Sucursal actualizada.');
    }

    public function destroy(Branch $branch)
    {
        // Evitar borrar la sucursal 1 (Matriz) por seguridad
        if ($branch->id == 1) {
            return redirect()->back()->with('error', 'No puedes eliminar la Casa Matriz.');
        }
        
        $branch->delete();
        return redirect()->back()->with('success', 'Sucursal eliminada.');
    }
}