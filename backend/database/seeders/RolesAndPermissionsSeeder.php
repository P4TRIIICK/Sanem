<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Usando 'sanctum' como o guarda para todas as permissões e papéis
        $guard = 'sanctum';

        // --- Criar Permissões ---
        Permission::create(['name' => 'ver-dashboard', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-pessoas', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-doacoes', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-estoque', 'guard_name' => $guard]);
        Permission::create(['name' => 'criar-relatorios', 'guard_name' => $guard]);
        Permission::create(['name' => 'registrar-propria-doacao', 'guard_name' => $guard]);

        // --- Criar Papéis ---
        $roleAdmin = Role::create(['name' => 'Administrador', 'guard_name' => $guard]);
        $roleConsultor = Role::create(['name' => 'Consultor', 'guard_name' => $guard]);
        $roleDoador = Role::create(['name' => 'Doador', 'guard_name' => $guard]);

        // --- Atribuir Permissões aos Papéis ---
        $roleAdmin->givePermissionTo(Permission::all());

        $roleConsultor->givePermissionTo([
            'ver-dashboard',
            'gerenciar-doacoes',
            'gerenciar-estoque',
            'criar-relatorios'
        ]);
        
        $roleDoador->givePermissionTo('registrar-propria-doacao');
    }
}