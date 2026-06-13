<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador inicial do sistema (provisionado direto no banco)
        User::firstOrCreate(
            ['email' => 'admin@coinpel.com'],
            [
                'name' => 'Administrador',
                'password' => 'password', // criptografada automaticamente pelo model
                'must_change_password' => false, // este admin já está "pronto"
                'blocked' => false,
            ]
        );
    }
}
