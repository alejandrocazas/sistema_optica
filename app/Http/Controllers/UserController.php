<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch; // Importamos el modelo Branch
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. Listar usuarios
    public function index()
    {
        $users = User::with('branch') // Carga la sucursal de cada usuario
                    ->latest()       // Ordena por el más nuevo
                    ->paginate(10);  // Muestra 10 usuarios por página

        return view('users.index', compact('users'));
    }

    // 2. Formulario de creación
    public function create()
    {
        // Obtenemos todas las sucursales para el select
        $branches = Branch::all();
        
        return view('users.create', compact('branches'));
    }

    // 3. Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required',
            'branch_id' => 'required|exists:branches,id' // Validación sucursal
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'branch_id' => $request->branch_id, // Guardar sucursal
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    // 4. Formulario de edición (CORREGIDO)
    public function edit(User $user)
    {
        // IMPORTANTE: Enviamos las sucursales a la vista de edición
        $branches = Branch::all();

        return view('users.edit', compact('user', 'branches'));
    }

    // 5. Actualizar usuario (CORREGIDO)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required'],
            'branch_id' => ['required', 'exists:branches,id'], // Validar que la sucursal exista
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->branch_id, // Actualizar la sucursal
        ];

        // Solo actualizamos la contraseña si el usuario escribió una nueva
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()]
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // 6. Eliminar usuario
    public function destroy(User $user)
    {
        // Evitar suicidio (borrarse a sí mismo)
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}