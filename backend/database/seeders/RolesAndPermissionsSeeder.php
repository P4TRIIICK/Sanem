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
        $guard = 'sanctum'; // Conforme definido anteriormente para Sanctum

        // --- Permissões de Módulos ---
        Permission::create(['name' => 'acessar-dashboard', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-doacoes', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-beneficiarios', 'guard_name' => $guard]);
        Permission::create(['name' => 'aprovar-beneficiarios', 'guard_name' => $guard]);
        Permission::create(['name' => 'gerenciar-estoque', 'guard_name' => $guard]);
        Permission::create(['name' => 'registrar-saidas', 'guard_name' => $guard]);
        Permission::create(['name' => 'emitir-cartoes', 'guard_name' => $guard]);
        Permission::create(['name' => 'ver-relatorios', 'guard_name' => $guard]);
        Permission::create(['name' => 'enviar-notificacoes', 'guard_name' => $guard]);

        // --- Permissões de Doador ---
        Permission::create(['name' => 'registrar-propria-doacao', 'guard_name' => $guard]);

        // --- Permissões de Nível Administrador ---
        Permission::create(['name' => 'gerenciar-permissoes', 'guard_name' => $guard]);
        Permission::create(['name' => 'ver-auditoria', 'guard_name' => $guard]);
        
        // --- Papel: Administrador (acesso total) ---
        $adminRole = Role::create(['name' => 'Administrador', 'guard_name' => $guard]);
        $adminRole->givePermissionTo(Permission::all());

        // --- Papel: Consultor (acesso operacional) ---
        $consultorRole = Role::create(['name' => 'Consultor', 'guard_name' => $guard]);
        $consultorRole->givePermissionTo([
            'acessar-dashboard',
            'gerenciar-doacoes',
            'gerenciar-beneficiarios',
            'aprovar-beneficiarios',
            'gerenciar-estoque',
            'registrar-saidas',
            'emitir-cartoes',
            'ver-relatorios',
            'enviar-notificacoes',
        ]);
        
        // --- Papel: Doador (acesso limitado) ---
        $doadorRole = Role::create(['name' => 'Doador', 'guard_name' => $guard]);
        $doadorRole->givePermissionTo('registrar-propria-doacao');
    }
}