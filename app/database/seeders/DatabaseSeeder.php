<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, //  seeder de Roles e Permissões
            UserSeeder::class, // criar os utilizadores automaticamente
        ]);
    }
}
