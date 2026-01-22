<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder de usuário admin inicial.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@adritreinos.com'],
            [
                'name' => 'Adriana (Admin)',
                'email' => 'admin@adritreinos.com',
                'password' => bcrypt('admin123'), // TROCAR EM PRODUÇÃO!
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Usuário de teste (opcional)
        User::updateOrCreate(
            ['email' => 'teste@teste.com'],
            [
                'name' => 'Usuário Teste',
                'email' => 'teste@teste.com',
                'password' => bcrypt('teste123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
