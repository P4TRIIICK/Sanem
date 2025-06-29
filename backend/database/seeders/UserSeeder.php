<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pessoa; // <-- Importante!

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Pessoa::firstOrCreate(
            ['email' => 'admin@sanem.com'], // Condição para encontrar a pessoa
            [
                'nome'      => 'Admin Sanem',
                'cpf'       => '000.000.000-00', // CPF de exemplo
                'password'  => Hash::make('password'), // A senha será 'password'
                // Adicione outros campos obrigatórios da sua tabela pessoa, se houver
            ]
        );
    }
}