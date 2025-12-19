<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el Super Admin 
    \App\Models\User::factory()->create([
        'name' => 'Administrador KAD',
        'email' => 'admin@kad.com',
        'password' => bcrypt('admin123'), // Contraseña inicial
        'role' => 'admin', // Importante: Este es el rol que definimos antes
        'is_active' => true,
    ]);

    // Opcional: Crear un usuario Optometrista de prueba
    \App\Models\User::factory()->create([
        'name' => 'Doctor Ejemplo',
        'email' => 'doc@kad.com',
        'password' => bcrypt('password'),
        'role' => 'optometrista',
    ]);
    }
}
