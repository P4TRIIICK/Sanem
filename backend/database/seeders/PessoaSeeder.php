<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pessoa; // Importando seu modelo Pessoa
use App\Models\Funcionario; // Importando o modelo Funcionário

class PessoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Criar um Administrador ---
        $admin = Pessoa::create([
            'nome' => 'Admin Sanem',
            'cpf' => '000.000.000-00',
            'genero' => 'OUTRO',
            'tipo_beneficiario' => 'DOADOR', // Funcionário também pode ser doador
            'email' => 'admin@sanem.com',
            'password' => 'senha123'
        ]);
        // Associa os dados de funcionário
        Funcionario::create([
            'id' => $admin->id,
            'nivel_acesso' => 'ADMINISTRADOR', // Mantendo por consistência, mas o pacote Spatie irá controlar
            'data_contratacao' => now()
        ]);
        // Atribui o papel do Spatie
        $admin->assignRole('Administrador');


        // --- Criar um Consultor ---
        $consultor = Pessoa::create([
            'nome' => 'Consultor Sanem',
            'cpf' => '111.111.111-11',
            'genero' => 'OUTRO',
            'tipo_beneficiario' => 'DOADOR',
            'email' => 'consultor@sanem.com',
            'password' => 'senha123'
        ]);
        // Associa os dados de funcionário
        Funcionario::create([
            'id' => $consultor->id,
            'nivel_acesso' => 'CONSULTOR',
            'data_contratacao' => now()
        ]);
        // Atribui o papel do Spatie
        $consultor->assignRole('Consultor');
    }
}