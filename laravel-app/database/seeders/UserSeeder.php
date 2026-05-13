<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@uts.edu.co'],
            [
                'name'     => 'Administrador UTS',
                'password' => Hash::make('Admin2026!'),
                'role'     => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'estudiante@uts.edu.co'],
            [
                'name'              => 'Estudiante Prueba',
                'password'          => Hash::make('Student2026!'),
                'role'              => 'estudiante',
                'codigo_estudiante' => '2023001',
                'programa'          => 'Ingeniería de Sistemas',
            ]
        );
    }
}
