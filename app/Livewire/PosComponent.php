<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Patient;
use App\Models\Sale;
use App\Models\Prescription;
use App\Models\SaleDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PosComponent extends Component
{
    // Variables para Receta Externa
    public $od_esfera, $od_cilindro, $od_eje, $add_od;
    public $oi_esfera, $oi_cilindro, $oi_eje, $add_oi;
    public $dip, $diagnostico, $observaciones_receta;

    // Buscadores
    public $searchProduct = '';
    public $searchPatient = '';
    
    // Datos de la Venta
    public $cart = [];
    public $patient_id = null;
    public $selectedPatientName = null;
    
    // Pago
    public $paymentMethod = 'Efectivo';
    public $amountPaid = ''; // Usamos vacío para que el placeholder se vea
    
    // Total
    public $total = 0;
    public $delivery_date;
    public $observations;
    public $paymentReference = '';

    protected $listeners = ['confirmSale' => 'saveSale'];

    public function mount()
    {
        $this->cart = [];
        $this->amountPaid = '';
    }

    // --- CARRITO ---
    public function addToCart($productId)
{
    $product = \App\Models\Product::find($productId);
    
    // 1. Validar que el producto exista
    if(!$product) {
        return;
    }

    // 2. Obtener el stock real de la sucursal (usando el Accessor que creamos antes)
    $stockDisponible = $product->stock_actual;

    // 3. Validar si hay stock inicial (mayor a 0)
    if($stockDisponible <= 0) {
        $this->dispatch('error', 'Producto agotado en esta sucursal.'); // O tu sistema de notificaciones
        return;
    }

    // 4. Lógica de Agregado al Carrito
    if(isset($this->cart[$productId])) {
        
        // VALIDACIÓN CRÍTICA: 
        // Antes de sumar 1, verificamos que no superemos el stock disponible
        if ($this->cart[$productId]['quantity'] + 1 > $stockDisponible) {
            $this->dispatch('error', 'No hay suficiente stock. Solo quedan ' . $stockDisponible . ' unidades.');
            return;
        }

        $this->cart[$productId]['quantity']++;
        $this->cart[$productId]['subtotal'] = $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
        
    } else {
        // Si es nuevo en el carrito, ya validamos arriba que stock > 0
        $this->cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price_sell,
            'quantity' => 1,
            'subtotal' => $product->price_sell,
            'code' => $product->code
        ];
    }

    $this->calculateTotal();
    
    // Opcional: Limpiar el buscador y enfocar el input de nuevo
    // $this->searchProduct = ''; 
    // $this->dispatch('focus-search'); 
}

    public function decreaseQuantity($productId)
    {
        if(isset($this->cart[$productId])) {
            if($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
                $this->cart[$productId]['subtotal'] = $this->cart[$productId]['quantity'] * $this->cart[$productId]['price'];
                $this->calculateTotal();
            }
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_sum(array_column($this->cart, 'subtotal'));
    }

    // --- CLIENTE ---
    public function selectPatient($id, $name)
    {
        $this->patient_id = $id;
        $this->selectedPatientName = $name;
        $this->searchPatient = '';
    }

    // --- GUARDAR VENTA ---
    public function saveSale()
    {
        if(empty($this->cart)) return;
        if(!$this->patient_id) return;
        // Validación QR
    if ($this->paymentMethod == 'QR' && empty($this->paymentReference)) {
        $this->dispatch('sale-error', ['message' => 'Debe ingresar el Nro de Referencia del QR.']);
        return;
    }

        DB::transaction(function () {
            // 1. Crear Venta
            // Convertimos amountPaid a float por si viene vacío
            $montoPagado = (float)$this->amountPaid;

            $sale = Sale::create([
                'receipt_number' => 'V-'.time(),
                'user_id' => auth()->id(),
                'patient_id' => $this->patient_id,
                'total' => $this->total,
                'paid_amount' => $montoPagado,
                'balance' => max(0, $this->total - $montoPagado),
                'status' => 'laboratorio',
                'payment_status' => ($montoPagado >= $this->total) ? 'pagado' : 'parcial',
                'delivery_date' => $this->delivery_date ?: null, // Guardar fecha
                'observations' => $this->observations,
            ]);
            

            // 2. Detalles
            foreach($this->cart as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            // 3. Pago
            if($montoPagado > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => $montoPagado,
                    'method' => $this->paymentMethod,
                    'reference' => $this->paymentReference // <--- Guardamos la referencia
                ]);
            }
            
            // 4. Limpiar
            $this->reset(['cart', 'total', 'patient_id', 'amountPaid', 'delivery_date', 'observations', 'selectedPatientName', 'paymentReference']);
            
            // 5. Emitir evento ÚNICO con el ID para imprimir
            $this->dispatch('sale-success', ['saleId' => $sale->id]);
        });
    }

    // --- RENDERIZADO ---
    public function render()
    {
        if(strlen($this->searchProduct) > 0) {
            $products = Product::with('category')
                        ->where('name', 'like', '%'.$this->searchProduct.'%')
                        ->orWhere('code', 'like', '%'.$this->searchProduct.'%')
                        ->take(20)->get();
        } else {
            $products = Product::with('category')->latest()->take(20)->get();
        }

        $patients = [];
        if(strlen($this->searchPatient) > 0) { 
            $patients = Patient::where('name', 'like', '%'.$this->searchPatient.'%')
                        ->orWhere('ci', 'like', '%'.$this->searchPatient.'%')
                        ->take(5)->get();
        }

        return view('livewire.pos-component', [
            'products' => $products,
            'patients' => $patients
        ]);
    }

    // --- GUARDAR RECETA ---
    public function savePrescription()
    {
        if(!$this->patient_id) {
            // Aquí podrías emitir un error si quisieras
            return;
        }

        Prescription::create([
            'patient_id' => $this->patient_id,
            'user_id' => auth()->id(),
            'od_esfera' => $this->od_esfera,
            'od_cilindro' => $this->od_cilindro,
            'od_eje' => $this->od_eje,
            'add_od' => $this->add_od,
            'oi_esfera' => $this->oi_esfera,
            'oi_cilindro' => $this->oi_cilindro,
            'oi_eje' => $this->oi_eje,
            'add_oi' => $this->add_oi,
            'dip' => $this->dip,
            'diagnostico' => $this->diagnostico,
            'observaciones' => "RECETA EXTERNA. " . $this->observaciones_receta,
        ]);

        $this->reset(['od_esfera', 'od_cilindro', 'od_eje', 'add_od', 'oi_esfera', 'oi_cilindro', 'oi_eje', 'add_oi', 'dip', 'diagnostico', 'observaciones_receta']);

        $this->dispatch('close-prescription-modal');
        // Usamos otro evento o un mensaje simple para no confundir con la venta
        $this->dispatch('simple-alert', ['message' => 'Receta guardada']); 
    }
}