<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chama os seeders na ordem de dependência
        $this->call([
<<<<<<< Updated upstream
=======
            ProdutoSeeder::class,  
>>>>>>> Stashed changes
            RolesAndPermissionsSeeder::class, // 1º - Cria os papéis e permissões
            PessoaSeeder::class,              // 2º - Cria as pessoas e atribui os papéis
        ]);
    }
}