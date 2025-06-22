<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pessoa;
use App\Models\Funcionario;

class PessoaSeeder extends Seeder
{
    public function run(): void
    {
        // --- Administrador ---
        $admin = Pessoa::create([
            'nome' => 'Admin Sanem', 'cpf' => '000.000.000-00', 'genero' => 'OUTRO',
            'tipo_beneficiario' => 'DOADOR', 'email' => 'admin@sanem.com', 'password' => 'senha123'
        ]);
        Funcionario::create(['id' => $admin->id, 'nivel_acesso' => 'ADMINISTRADOR', 'data_contratacao' => now()]);
        $admin->assignRole('Administrador');

        // --- Consultor ---
        $consultor = Pessoa::create([
            'nome' => 'Consultor Sanem', 'cpf' => '111.111.111-11', 'genero' => 'OUTRO',
            'tipo_beneficiario' => 'DOADOR', 'email' => 'consultor@sanem.com', 'password' => 'senha123'
        ]);
        Funcionario::create(['id' => $consultor->id, 'nivel_acesso' => 'CONSULTOR', 'data_contratacao' => now()]);
        $consultor->assignRole('Consultor');

        // --- Doador ---
        $doador = Pessoa::create([
            'nome' => 'Doador Teste', 'cpf' => '222.222.222-22', 'genero' => 'OUTRO',
            'tipo_beneficiario' => 'DOADOR', 'email' => 'doador@sanem.com', 'password' => 'senha123'
        ]);
        // Doador não é um funcionário, então não criamos um registro em 'funcionario'
        $doador->assignRole('Doador');
    }
}