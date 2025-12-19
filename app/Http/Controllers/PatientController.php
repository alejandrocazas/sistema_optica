<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient; // <-- Agrega esto arriba, junto a los otros 'use'

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Iniciamos la consulta
    // Nota: Gracias al Trait Multitenantable, ya filtra por sucursal automáticamente
    $query = \App\Models\Patient::latest();

    // Lógica del Buscador
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('ci', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // Usamos paginate(10) en lugar de get()
    $patients = $query->paginate(10); 

    return view('patients.index', compact('patients'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'ci' => 'nullable|unique:patients,ci', // El CI no debe repetirse
            'age' => 'nullable|integer',
            'phone' => 'nullable|string',
            'occupation' => 'nullable|string',
        ]);

        // 2. Preparar los datos para guardar
        // Tomamos todos los datos del formulario...
        $data = $request->all();
        
        // ...y le agregamos la sucursal del usuario actual (Rosario -> Optica Omega)
        $data['branch_id'] = auth()->user()->branch_id;

        // 3. Crear el paciente
        \App\Models\Patient::create($data);

        // 4. Redireccionar
        return redirect()->route('patients.index')->with('success', 'Paciente creado correctamente.');
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
    public function edit(string $id)
    {
        $patient = Patient::find($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            // Validar que sea único, PERO ignorando al paciente actual ($id)
            'ci' => 'nullable|unique:patients,ci,' . $id, 
        ]);

        $patient = Patient::find($id);
        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::find($id);
        $patient->delete();

        return redirect()->route('patients.index');
    }
}
