<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Importar PDF

class PrescriptionController extends Controller
{

    public function index()
{
    // Optimización: Usamos paginate() en lugar de get() para no sobrecargar la memoria
    $prescriptions = \App\Models\Prescription::with(['patient', 'user']) // Carga inteligente (evita N+1)
                        ->latest()                      // Ordena por fecha descendente
                        ->paginate(20);                 // Muestra 20 recetas por página

    return view('prescriptions.index', compact('prescriptions'));
}
    // Paso 1: Mostrar formulario (Recibimos el ID del paciente)
    public function create(Patient $patient)
    {
        return view('prescriptions.create', compact('patient'));
    }

    // Paso 2: Guardar la receta
    public function store(Request $request, Patient $patient)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id(); // Guardamos quién hizo la receta
        $data['patient_id'] = $patient->id;

        $prescription = Prescription::create($data);

        // Redirigir a imprimir directamente o volver al historial
        return redirect()->route('prescriptions.print', $prescription->id);
    }

    // Paso 3: Generar PDF Ticket
    public function print($id)
    {
        $prescription = Prescription::with('patient', 'user')->findOrFail($id);
        
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));
        
        // Configurar tamaño ticket (80mm x alto variable o A5)
        // setPaper([0, 0, 226.77, 425.19], 'portrait'); // Aprox 80mm ancho
        
        return $pdf->stream('receta-'.$prescription->id.'.pdf');
    }
    // Ver el historial completo de UN paciente
    public function byPatient(Patient $patient)
    {
        // Traemos las recetas ordenadas por fecha (la más nueva primero)
        $prescriptions = $patient->prescriptions()->latest()->get();
        
        return view('prescriptions.history', compact('patient', 'prescriptions'));
    }

    // Nuevo método: Pantalla para buscar paciente antes de la receta
    public function selectPatient(Request $request)
    {
        $search = $request->input('search');
        
        $patients = [];
        
        if($search){
            $patients = Patient::where('name', 'LIKE', "%{$search}%")
                            ->orWhere('ci', 'LIKE', "%{$search}%")
                            ->limit(5) // Solo mostramos 5 para no llenar la pantalla
                            ->get();
        }

        return view('prescriptions.select_patient', compact('patients', 'search'));
    }
    // 1. Mostrar formulario de edición
    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient; // Necesitamos datos del paciente para el encabezado
        
        return view('prescriptions.edit', compact('prescription', 'patient'));
    }

    // 2. Guardar los cambios
    public function update(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        
        // Actualizamos todos los datos
        $prescription->update($request->all());

        // Redirigimos al historial de ESE paciente
        return redirect()->route('prescriptions.history', $prescription->patient_id)
                         ->with('success', 'Receta actualizada correctamente.');
    }

    // 3. Eliminar receta
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patientId = $prescription->patient_id; // Guardamos el ID para volver
        
        $prescription->delete();

        return redirect()->route('prescriptions.history', $patientId)
                         ->with('success', 'Receta eliminada.');
    }
}