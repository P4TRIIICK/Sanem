<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pessoa;
use App\Models\Funcionario;
use Spatie\Permission\Models\Role;

class PessoaSeeder extends Seeder
{
    public function run()
    {
        // Define o guarda que vamos usar para procurar os papéis
        $guard = 'web';

        // --- Administrador ---
        $admin = Pessoa::firstOrCreate(
            ['email' => 'admin@sanem.com'],
            [
                'nome' => 'Admin Sanem', 'cpf' => '000.000.000-00', 'password' => 'senha123',
                'genero' => 'OUTRO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        Funcionario::firstOrCreate(['id' => $admin->id], ['nivel_acesso' => 'ADMINISTRADOR', 'data_contratacao' => now()]);
        $admin->assignRole(Role::where('name', 'Administrador')->where('guard_name', $guard)->first());

        // --- Consultor ---
        $consultor = Pessoa::firstOrCreate(
            ['email' => 'consultor@sanem.com'],
            [
                'nome' => 'Consultor Sanem', 'cpf' => '111.111.111-11', 'password' => 'senha123',
                'genero' => 'OUTRO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        Funcionario::firstOrCreate(['id' => $consultor->id], ['nivel_acesso' => 'CONSULTOR', 'data_contratacao' => now()]);
        $consultor->assignRole(Role::where('name', 'Consultor')->where('guard_name', $guard)->first());

        // --- Doador ---
        $doador = Pessoa::firstOrCreate(
            ['email' => 'doador@sanem.com'],
            [
                'nome' => 'Doador Teste', 'cpf' => '222.222.222-22', 'password' => 'senha123',
                'genero' => 'FEMININO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        $doador->assignRole(Role::where('name', 'Doador')->where('guard_name', $guard)->first());
    }
}