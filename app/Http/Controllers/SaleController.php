<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Sale;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\Product;

class SaleController extends Controller
{
    public function create() {
    return view('sales.pos');
}

public function index() {
    // Cambiamos get() por paginate(20)
    $sales = \App\Models\Sale::with(['patient', 'user']) // Trae datos de paciente y vendedor
                         ->latest()                      // Ordena por fecha (más nuevo primero)
                         ->paginate(20);                 // Muestra 20 por página

    
    // ===> AGREGAR ESTO <===
    // Verificar si el usuario tiene una caja abierta actualmente
    $hasOpenRegister = \App\Models\CashRegister::where('user_id', auth()->id())
                        ->where('status', 'abierta')
                        ->exists();

    return view('sales.index', compact('sales', 'hasOpenRegister'));
}

public function print($id)
{
    $sale = Sale::with(['details.product', 'patient', 'user'])->findOrFail($id);

    // Generamos el QR con la URL pública de la venta o datos clave
    // Ejemplo: "Venta #123 | Total: 500 | Fecha: ..."
    $qrData = "RECIBO: {$sale->receipt_number} | TOTAL: {$sale->total}";

    // Convertimos a imagen Base64 para que DomPDF lo entienda
    $qrImage = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

    $pdf = Pdf::loadView('sales.receipt', compact('sale', 'qrImage')); // Pasamos la variable
    $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');

    return $pdf->stream('ticket-'.$sale->receipt_number.'.pdf');
}

    public function destroy($id)
    {
        // 1. Validar permiso (Solo Admin)
    if(auth()->user()->role !== 'admin') {
        return back()->with('error', 'No tienes permisos para anular ventas.');
    }

    $sale = Sale::with('details')->findOrFail($id);

    // 2. Bloqueo de seguridad: Si ya está entregado, no se puede borrar simple
    // (Opcional: Si quieres permitirlo con justificación, comenta este if)
    if($sale->status === 'entregado') {
         return back()->with('error', 'No se puede eliminar una venta que ya fue entregada al cliente.');
    }

    // 3. Validar que venga la justificación
    if(!$request->input('reason')) {
        return back()->with('error', 'Es obligatorio indicar el motivo de la anulación.');
    }

    DB::transaction(function () use ($sale, $request) {
        // A. Restaurar Stock (Iterar producto por producto)
        foreach($sale->details as $detail) {
            $product = Product::find($detail->product_id);
            if($product) {
                $product->increment('stock', $detail->quantity);
            }
        }

        // B. Guardar auditoría antes de borrar
        $sale->deletion_reason = $request->input('reason');
        $sale->deleted_by = auth()->id();
        $sale->status = 'anulado'; // Cambiamos estado visualmente también
        $sale->save();

        // C. Borrado Lógico (Soft Delete)
        $sale->delete();
    });

    return back()->with('success', 'Venta anulada correctamente. El stock ha sido restaurado.');
    }

    public function updateDate(Request $request, Sale $sale)
{
    $sale->update(['delivery_date' => $request->delivery_date]);
    return back()->with('success', 'Fecha de entrega actualizada.');
}

public function updateObservations(Request $request, Sale $sale)
{
    $sale->update(['observations' => $request->observations]);
    return back()->with('success', 'Observaciones guardadas.');
}
}
