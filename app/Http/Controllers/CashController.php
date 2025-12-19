<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\CashRegister;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    // 1. DASHBOARD DE CAJA Y ENTREGAS
  public function index()
    {
        // 1. Verificar si el usuario actual tiene CAJA ABIERTA
        $currentRegister = \App\Models\CashRegister::where('user_id', auth()->id())
                                        ->where('status', 'abierta')
                                        ->latest()
                                        ->first();

        // 2. Trabajos Pendientes
        // Traemos las ventas que están en proceso o listas para entregar
        $pendingWorks = \App\Models\Sale::with('patient')
                            ->where(function($query) {
                                // A. Que esté en laboratorio o listo, pero que deba dinero
                                $query->whereIn('status', ['laboratorio', 'listo'])
                                      ->where('payment_status', '!=', 'pagado');
                            })
                            ->orWhere('status', 'listo') // B. O que esté listo (para entregar) aunque ya esté pagado
                            ->latest()
                            ->paginate(10);

        // 3. CALCULAR TOTALES (Solo si hay caja abierta)
        $totalIngresos = 0;
        $totalEgresos = 0;
        $saldoActual = 0;
        
        if ($currentRegister) {
            $fechaInicio = $currentRegister->opened_at;

            // CORRECCIÓN PRINCIPAL AQUÍ:
            // Como la tabla 'payments' no tiene 'user_id', filtramos a través de la Venta ('sale')
            $totalIngresos = \App\Models\Payment::where('created_at', '>=', $fechaInicio)
                                ->whereHas('sale', function ($q) {
                                    $q->where('user_id', auth()->id());
                                })
                                ->sum('amount');
            
            // Sumar GASTOS de esta caja específica
            $totalEgresos = \App\Models\Expense::where('cash_register_id', $currentRegister->id)
                                ->sum('amount');

            // Calculamos el saldo actual en tiempo real para la vista
            $saldoActual = $currentRegister->opening_amount + $totalIngresos - $totalEgresos;
        }

        return view('cash.index', compact('currentRegister', 'pendingWorks', 'totalIngresos', 'totalEgresos', 'saldoActual'));
    }

    // 2. APERTURA DE CAJA
    public function open(Request $request)
    {
        // 1. Validar que llegue el monto
    $request->validate([
        'amount' => 'required|numeric|min:0',
    ]);

    // 2. Verificar si ya tiene una caja abierta (para evitar duplicados al hacer doble clic)
    $existe = CashRegister::where('user_id', auth()->id())
                          ->where('status', 'abierta')
                          ->exists();

    if ($existe) {
        return redirect()->route('cash.index')->with('error', 'Ya tienes una caja abierta.');
    }

    // 3. CREAR EL REGISTRO
    CashRegister::create([
        'user_id' => auth()->id(),
        'opening_amount' => $request->amount,
        'opened_at' => Carbon::now(), // Importante: Fecha actual
        'status' => 'abierta',           // CRUCIAL: Debe decir 'open'
    ]);

    // 4. Redirigir al index (ahora sí detectará la caja abierta)
    return redirect()->route('cash.index')->with('success', 'Caja abierta correctamente.');
    }

    // 3. REGISTRAR GASTO (EGRESO)
    public function storeExpense(Request $request)
    {
        $register = CashRegister::where('user_id', auth()->id()) // <--- Agregar esto
                        ->where('status', 'abierta')
                        ->firstOrFail();
        
        Expense::create([
            'cash_register_id' => $register->id,
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        return back()->with('success', 'Egreso registrado.');
    }

    // 4. CAMBIAR ESTADO (Laboratorio -> Listo -> Entregado)
    public function updateStatus(Sale $sale, $status)
    {
        // Validación: No se puede entregar si debe dinero
        if($status == 'entregado' && $sale->balance > 0) {
            return back()->with('error', 'No se puede entregar. El cliente tiene saldo pendiente.');
        }

        $sale->update(['status' => $status]);
        return back()->with('success', 'Estado actualizado a: ' . ucfirst($status));
    }

    // 5. COBRAR SALDO PENDIENTE
    public function payBalance(Request $request, Sale $sale)
    {
        // Validar caja abierta
        $register = CashRegister::where('user_id', auth()->id()) // <--- Agregar esto
                        ->where('status', 'abierta')
                        ->firstOrFail();
        if(!$register) return back()->with('error', 'Debe abrir caja antes de cobrar.');

        $amount = $request->amount;

        // Validar que no pague más de lo que debe
        if($amount > $sale->balance) {
            return back()->with('error', 'El monto excede la deuda.');
        }

        DB::transaction(function() use ($sale, $amount, $request) {
            // Crear pago
            Payment::create([
                'sale_id' => $sale->id,
                'amount' => $amount,
                'method' => $request->method // Efectivo, QR
            ]);

            // Actualizar venta
            $sale->paid_amount += $amount;
            $sale->balance -= $amount;
            
            if($sale->balance <= 0) {
                $sale->payment_status = 'pagado';
                $sale->balance = 0;
            } else {
                $sale->payment_status = 'parcial';
            }
            
            $sale->save();
        });

        return back()->with('success', 'Pago registrado correctamente.');
    }

    // 6. CIERRE DE CAJA (Reporte del día)
   public function close()
{
    $user = auth()->user();

    // 1. SEGURIDAD: Buscar SOLO la caja abierta de ESTE usuario
    $register = CashRegister::where('user_id', $user->id)
                            ->where('status', 'abierta')
                            ->latest()
                            ->firstOrFail();

    // Definir fecha de inicio (Usa opened_at, si falla usa created_at como respaldo)
    $fechaInicio = $register->opened_at ?? $register->created_at;

    // 2. Obtener PAGOS (Ingresos)
    // Filtramos que la fecha sea mayor a la apertura Y que la venta sea de este usuario
    $payments = Payment::with(['sale.patient', 'sale'])
        ->where('created_at', '>=', $fechaInicio)
        ->whereHas('sale', function($query) use ($user) {
            $query->where('user_id', $user->id); // Solo ventas de este usuario
        })
        ->get();

    // 3. Obtener GASTOS (Egresos)
    $expenses = Expense::where('cash_register_id', $register->id)->get();

    // 4. Cálculos Matemáticos
    $totalIngresos = $payments->sum('amount');
    $totalEgresos = $expenses->sum('amount');
    $totalSistema = $register->opening_amount + $totalIngresos - $totalEgresos;

    // 5. IMPORTANTE: Cerrar la caja en BD ANTES del PDF
    // Esto asegura que al recargar la página, ya aparezca cerrada.
    $register->update([
        'closing_amount' => $totalSistema,
        'calculated_amount' => $totalSistema, // Si tuvieras conteo de billetes, aquí iría la diferencia
        'status' => 'cerrada',
        'closed_at' => now()
    ]);

    // 6. Generar PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.cash_closing', compact(
        'register', 'payments', 'expenses', 'totalIngresos', 'totalEgresos', 'totalSistema', 'user'
    ));
    
    $pdf->setPaper('a4', 'portrait');

    // 7. Retornar el PDF al navegador
    return $pdf->stream('cierre-caja-' . date('d-m-Y-His') . '.pdf');
}
}