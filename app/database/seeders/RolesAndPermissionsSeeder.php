<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Permissões
        $permissions = [
            'manage-users',       // Gerir utilizadores
            'manage-companies',   // Gerir empresas
            'view-dashboard',     // Aceder ao painel do Filament
            'request-leave',      // Marcar férias
            'approve-leave',      // Aprovar férias
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar Roles (se ainda não existirem)
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $colaborador = Role::firstOrCreate(['name' => 'colaborador']);

        // Atribuir permissões às Roles
        $superadmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(['view-dashboard', 'manage-companies', 'request-leave', 'approve-leave']);
        $colaborador->givePermissionTo(['view-dashboard', 'request-leave']);
    }
}
