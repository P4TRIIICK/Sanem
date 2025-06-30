<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pessoa;
use App\Models\Funcionario;
use Spatie\Permission\Models\Role;
<<<<<<< Updated upstream

class PessoaSeeder extends Seeder
{
    public function run()
    {
        // Define o guarda que vamos usar para procurar os papéis
=======
use Illuminate\Support\Facades\Hash; // <-- Importar o Hash

class PessoaSeeder extends Seeder
{
    /**
     * Popula o banco com usuários de teste e atribui perfis.
     */
    public function run(): void
    {
        // Define o 'guard' que será usado para procurar os perfis
>>>>>>> Stashed changes
        $guard = 'web';

        // --- Administrador ---
        $admin = Pessoa::firstOrCreate(
            ['email' => 'admin@sanem.com'],
            [
<<<<<<< Updated upstream
                'nome' => 'Admin Sanem', 'cpf' => '000.000.000-00', 'password' => 'senha123',
                'genero' => 'OUTRO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        Funcionario::firstOrCreate(['id' => $admin->id], ['nivel_acesso' => 'ADMINISTRADOR', 'data_contratacao' => now()]);
        $admin->assignRole(Role::where('name', 'Administrador')->where('guard_name', $guard)->first());
=======
                'nome' => 'Admin Sanem',
                'cpf' => '000.000.000-00',
                // Criptografa a senha antes de salvar
                'password' => Hash::make('senha123'),
                'genero' => 'OUTRO',
                'tipo_beneficiario' => 'DOADOR'
            ]
        );
        // Cria o registro de funcionário correspondente
        Funcionario::firstOrCreate(['id' => $admin->id], ['nivel_acesso' => 'ADMINISTRADOR', 'data_contratacao' => now()]);
        // Atribui o perfil 'Administrador'
        $adminRole = Role::where('name', 'Administrador')->where('guard_name', $guard)->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }
>>>>>>> Stashed changes

        // --- Consultor ---
        $consultor = Pessoa::firstOrCreate(
            ['email' => 'consultor@sanem.com'],
            [
<<<<<<< Updated upstream
                'nome' => 'Consultor Sanem', 'cpf' => '111.111.111-11', 'password' => 'senha123',
                'genero' => 'OUTRO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        Funcionario::firstOrCreate(['id' => $consultor->id], ['nivel_acesso' => 'CONSULTOR', 'data_contratacao' => now()]);
        $consultor->assignRole(Role::where('name', 'Consultor')->where('guard_name', $guard)->first());
=======
                'nome' => 'Consultor Sanem',
                'cpf' => '111.111.111-11',
                'password' => Hash::make('senha123'),
                'genero' => 'OUTRO',
                'tipo_beneficiario' => 'DOADOR'
            ]
        );
        Funcionario::firstOrCreate(['id' => $consultor->id], ['nivel_acesso' => 'CONSULTOR', 'data_contratacao' => now()]);
        $consultorRole = Role::where('name', 'Consultor')->where('guard_name', $guard)->first();
        if ($consultorRole) {
            $consultor->assignRole($consultorRole);
        }
>>>>>>> Stashed changes

        // --- Doador ---
        $doador = Pessoa::firstOrCreate(
            ['email' => 'doador@sanem.com'],
            [
<<<<<<< Updated upstream
                'nome' => 'Doador Teste', 'cpf' => '222.222.222-22', 'password' => 'senha123',
                'genero' => 'FEMININO', 'tipo_beneficiario' => 'DOADOR'
            ]
        );
        $doador->assignRole(Role::where('name', 'Doador')->where('guard_name', $guard)->first());
=======
                'nome' => 'Doador Teste',
                'cpf' => '222.222.222-22',
                'password' => Hash::make('senha123'),
                'genero' => 'FEMININO',
                'tipo_beneficiario' => 'DOADOR'
            ]
        );
        $doadorRole = Role::where('name', 'Doador')->where('guard_name', $guard)->first();
        if ($doadorRole) {
            $doador->assignRole($doadorRole);
        }
>>>>>>> Stashed changes
    }
}