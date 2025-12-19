<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. FECHAS
        $filter = $request->input('filter', 'today');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        switch ($filter) {
            case 'week': $startDate = Carbon::now()->startOfWeek(); $endDate = Carbon::now()->endOfWeek(); break;
            case 'month': $startDate = Carbon::now()->startOfMonth(); $endDate = Carbon::now()->endOfMonth(); break;
            case 'year': $startDate = Carbon::now()->startOfYear(); $endDate = Carbon::now()->endOfYear(); break;
            case 'custom':
                if($customStart && $customEnd) {
                    $startDate = Carbon::parse($customStart);
                    $endDate = Carbon::parse($customEnd)->endOfDay();
                }
                break;
        }

        // 2. SUCURSAL
        $branchId = null;
        if (auth()->user()->role === 'admin') {
            if ($request->has('branch_id') && $request->branch_id != '') {
                $branchId = $request->branch_id;
            }
        } else {
            $branchId = auth()->user()->branch_id;
        }

        // 3. CONSULTAS INDEX

        // A. Ventas (Tiene branch_id directo)
        $salesQuery = Sale::whereBetween('created_at', [$startDate, $endDate]);
        if ($branchId) {
            $salesQuery->where('branch_id', $branchId);
        }
        $sales = $salesQuery->with('patient')->latest()->get();

        // B. Ingresos/Pagos (CORREGIDO: Filtrar via Venta)
        $incomeQuery = Payment::whereBetween('created_at', [$startDate, $endDate]);
        if ($branchId) {
            $incomeQuery->whereHas('sale', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $totalIncome = $incomeQuery->sum('amount');

        // C. Egresos/Gastos (Filtrar via Caja->Usuario)
        $expenseQuery = Expense::whereBetween('created_at', [$startDate, $endDate]);
        if ($branchId) {
            $expenseQuery->whereHas('cashRegister.user', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $totalExpenses = $expenseQuery->sum('amount');

        // Totales
        $totalSales = $sales->sum('total');
        $netProfit = $totalIncome - $totalExpenses;

        return view('reports.index', compact(
            'sales', 'totalSales', 'totalIncome', 'totalExpenses', 'netProfit', 
            'filter', 'startDate', 'endDate'
        ));
    }

    public function pdf(Request $request)
    {
        // 1. FECHAS
        $filter = $request->input('filter', 'today');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        $startDate = Carbon::today();
        $endDate = Carbon::today()->endOfDay();

        switch ($filter) {
            case 'week': $startDate = Carbon::now()->startOfWeek(); $endDate = Carbon::now()->endOfWeek(); break;
            case 'month': $startDate = Carbon::now()->startOfMonth(); $endDate = Carbon::now()->endOfMonth(); break;
            case 'year': $startDate = Carbon::now()->startOfYear(); $endDate = Carbon::now()->endOfYear(); break;
            case 'custom':
                if($customStart && $customEnd) {
                    $startDate = Carbon::parse($customStart);
                    $endDate = Carbon::parse($customEnd)->endOfDay();
                }
                break;
        }

        // 2. SUCURSAL
        $branchId = null;
        if (auth()->user()->role === 'admin') {
            if ($request->has('branch_id') && $request->branch_id != '') {
                $branchId = $request->branch_id;
            }
        } else {
            $branchId = auth()->user()->branch_id;
        }

        // 3. CONSULTAS PDF

        // A. Ingresos
        $incomesQuery = Payment::with(['sale.patient', 'sale.user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc');
        
        if ($branchId) {
            // CORREGIDO: Filtrar por relación Sale -> Branch
            $incomesQuery->whereHas('sale', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $incomes = $incomesQuery->get();

        // B. Gastos (AQUI ESTABA EL ERROR)
        // Quitamos 'user' del with() y dejamos solo 'cashRegister.user'
        $expensesQuery = Expense::with(['cashRegister.user']) 
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc');

        if ($branchId) {
            $expensesQuery->whereHas('cashRegister.user', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }
        $expenses = $expensesQuery->get();

        // Totales
        $totalIngresos = $incomes->sum('amount');
        $totalEgresos = $expenses->sum('amount');
        $saldoTotal = $totalIngresos - $totalEgresos;

        // 4. PDF
        $pdf = Pdf::loadView('reports.pdf_export', compact(
            'incomes', 'expenses', 'totalIngresos', 'totalEgresos', 'saldoTotal', 'startDate', 'endDate', 'filter'
        ));

        $pdf->setPaper('a4', 'landscape'); 

        $fileName = 'reporte_' . $startDate->format('d-m-Y') . '.pdf';
        return $pdf->stream($fileName);
    }
}