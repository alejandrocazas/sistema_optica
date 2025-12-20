<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Update the user's profile information.
     */
   public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'max:1024'], // Máx 1MB
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // 1. Actualizar datos básicos
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // 2. Actualizar Contraseña (si se proporcionó)
        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        // 3. Actualizar Foto (si se subió una nueva)
        if ($request->hasFile('photo')) {
            // Borrar foto anterior si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        
        return Redirect::route('dashboard')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
