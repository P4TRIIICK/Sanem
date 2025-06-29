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
        
        $guard = 'web';

        // --- Permissões de Módulos ---
        Permission::firstOrCreate(['name' => 'acessar-dashboard', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'gerenciar-doacoes', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'gerenciar-beneficiarios', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'aprovar-beneficiarios', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'gerenciar-estoque', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'registrar-saidas', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'emitir-cartoes', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'ver-relatorios', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'enviar-notificacoes', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'gerenciar-permissoes', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'ver-auditoria', 'guard_name' => $guard]);
        Permission::firstOrCreate(['name' => 'registrar-propria-doacao', 'guard_name' => $guard]);
        
        // --- Papéis ---
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => $guard]);
        $consultorRole = Role::firstOrCreate(['name' => 'Consultor', 'guard_name' => $guard]);
        $doadorRole = Role::firstOrCreate(['name' => 'Doador', 'guard_name' => $guard]);
        // CORREÇÃO: Adiciona o papel que estava faltando.
        Role::firstOrCreate(['name' => 'Beneficiario', 'guard_name' => $guard]);

        // Atribuir permissões
        $adminRole->givePermissionTo(Permission::all());
        $consultorRole->givePermissionTo([
            'acessar-dashboard', 'gerenciar-doacoes', 'gerenciar-beneficiarios',
            'aprovar-beneficiarios', 'gerenciar-estoque', 'registrar-saidas',
            'emitir-cartoes', 'ver-relatorios', 'enviar-notificacoes',
        ]);
        $doadorRole->givePermissionTo('registrar-propria-doacao');
    }
}
