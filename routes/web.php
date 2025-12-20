<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

// Controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\DB;

// Modelos (Para el dashboard)
use App\Models\Sale;
use App\Models\Patient;
use App\Models\Product;

// --- RUTA PÚBLICA ---
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/debug-storage', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    echo "<h1>🛠️ Diagnóstico y Reparación de Storage</h1>";

    // 1. Verificar si la carpeta real existe (Donde se guardan las fotos)
    echo "<h3>1. Verificando Volumen Real</h3>";
    if (is_dir($target)) {
        echo "✅ La carpeta de origen existe: <code>$target</code><br>";
        
        // Listar archivos para ver si la foto está ahí
        $files = scandir($target . '/profile-photos');
        echo "📸 <b>Fotos encontradas en el disco:</b><br><ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') echo "<li>$file</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ ERROR CRÍTICO: La carpeta de origen NO existe. El Volumen no está bien montado.<br>";
    }

    // 2. Verificar el Enlace (El puente)
    echo "<h3>2. Verificando Enlace Simbólico (public/storage)</h3>";
    if (file_exists($link)) {
        if (is_link($link)) {
            echo "✅ El enlace YA existe y apunta a: <code>" . readlink($link) . "</code><br>";
        } else {
            echo "❌ PROBLEMA DETECTADO: 'public/storage' es una <b>CARPETA REAL</b>, no un enlace. <br>";
            echo "🗑️ Intentando eliminar la carpeta estorbo... ";
            // Intentar borrar la carpeta vacía para poder crear el link
            @rmdir($link); 
            if (!file_exists($link)) { echo "¡Eliminada con éxito!<br>"; } 
            else { echo "Falló la eliminación (quizás tiene archivos dentro).<br>"; }
        }
    } else {
        echo "⚠️ El enlace no existe (Está limpio).<br>";
    }

    // 3. Regenerar el enlace a la fuerza
    echo "<h3>3. Regenerando Enlace...</h3>";
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        echo "✅ Comando ejecutado: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        echo "❌ Error al ejecutar comando: " . $e->getMessage();
    }

    return "<br><br><a href='/'>Volver al Inicio</a>";
});
// --- RUTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth', 'verified'])->group(function () {


// 1. DASHBOARD (Acceso: TODOS)
Route::get('/dashboard', function () {
    
    // Obtener sucursal del usuario actual
    $branchId = auth()->user()->branch_id;

    // 1. Ventas de Hoy
    // (El Trait Multitenantable filtra automáticamente por sucursal en el modelo Sale)
    $ventasHoy = \App\Models\Sale::whereDate('created_at', now())->sum('total');

    // 2. Total Pacientes
    $pacientesTotal = \App\Models\Patient::count();

    // 3. Trabajos en Laboratorio
    $trabajosPendientes = \App\Models\Sale::where('status', 'laboratorio')->count();

    // 4. STOCK BAJO (CORREGIDO PARA MULTI-SUCURSAL)
    // Consultamos directamente la tabla pivote para ver el stock REAL de ESTA sucursal
    $productosBajoStock = DB::table('branch_product')
                            ->where('branch_id', $branchId)
                            ->where('stock', '<=', 9) // Umbral de alerta (puedes cambiar el 10)
                            ->count();

    // 5. Últimas Ventas
    $ultimasVentas = \App\Models\Sale::with('patient')->latest()->take(5)->get();

    return view('dashboard', compact('ventasHoy', 'pacientesTotal', 'trabajosPendientes', 'productosBajoStock', 'ultimasVentas'));

})->middleware(['auth', 'verified'])->name('dashboard');
    // 2. PERFIL DE USUARIO (Acceso: TODOS)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. GESTIÓN DE PACIENTES (Acceso: Vendedor y Optometrista)
    // Nota: El Admin entra automáticamente por la lógica del Middleware.
    Route::middleware(['role:vendedor,optometrista'])->group(function () {
        Route::resource('patients', PatientController::class);
        // Historial clínico también lo pueden ver ambos (o solo opto, según prefieras)
        Route::get('patients/{patient}/historial', [PrescriptionController::class, 'byPatient'])->name('prescriptions.history');
    });

    // 4. MÓDULO DE VENTAS Y CAJA (Acceso: Solo Vendedor)
    Route::middleware(['role:vendedor'])->group(function () {
        // Ventas
        Route::get('/ventas', [SaleController::class, 'index'])->name('sales.index');
        Route::get('ventas/nueva', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/ventas', [SaleController::class, 'store'])->name('sales.store'); // Faltaba el store
        Route::get('/ventas/{sale}/imprimir', [SaleController::class, 'print'])->name('sales.print');
        Route::delete('/ventas/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::patch('/ventas/{sale}/fecha', [SaleController::class, 'updateDate'])->name('sales.updateDate');
        Route::patch('/ventas/{sale}/observaciones', [SaleController::class, 'updateObservations'])->name('sales.updateObs');

        // Caja
        Route::get('/caja', [CashController::class, 'index'])->name('cash.index');
        Route::post('/caja/abrir', [CashController::class, 'open'])->name('cash.open');
        Route::post('/caja/cerrar', [CashController::class, 'close'])->name('cash.close');
        Route::post('/caja/gasto', [CashController::class, 'storeExpense'])->name('cash.expense');
        
        // Cobros de Saldos
        Route::get('/trabajo/{sale}/estado/{status}', [CashController::class, 'updateStatus'])->name('work.status');
        Route::post('/trabajo/{sale}/cobrar', [CashController::class, 'payBalance'])->name('work.pay');
    });

    // 5. MÓDULO CLÍNICO / RECETAS (Acceso: Solo Optometrista)
    Route::middleware(['role:optometrista'])->group(function () {
        Route::get('atenciones', [PrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('atenciones/nueva', [PrescriptionController::class, 'selectPatient'])->name('prescriptions.selectPatient');
        
        // Gestión de Recetas
        Route::get('patients/{patient}/receta/nueva', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('patients/{patient}/receta', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('receta/{id}/imprimir', [PrescriptionController::class, 'print'])->name('prescriptions.print');
        Route::get('receta/{prescription}/editar', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('receta/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('receta/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    });

    // 6. ADMINISTRACIÓN TOTAL (Acceso: Solo Admin)
    // Aunque el Admin pasa los filtros anteriores, estas rutas son EXCLUSIVAS para él.
    Route::middleware(['role:admin'])->group(function () {
        // Usuarios
        Route::resource('users', UserController::class);
        Route::resource('branches', \App\Http\Controllers\BranchController::class);

        // Inventario (Productos y Categorías)
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);

        // Compras (Ingreso de Stock)
        Route::get('/compras', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/compras/crear', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/compras', [PurchaseController::class, 'store'])->name('purchases.store');

        // Reportes Físicos y Financieros
        Route::get('/inventario/imprimir', [InventoryController::class, 'print'])->name('inventory.print');
        Route::get('/reportes', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reportes/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    });
});


require __DIR__.'/auth.php';

