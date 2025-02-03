<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CargoEnum;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Criar Superadmin
        $superadmin = User::create([
            'primeiro_nome' => 'Super',
            'ultimo_nome' => 'Admin',
            'email' => 'superadmin@test.com',
            'data_nascimento' => '1990-01-01',
            'cargo' => CargoEnum::ADMINISTRACAO, // 🔹 Agora é um ENUM correto
            'funcao' => 'Gestor de Sistema',
            'password' => Hash::make('1234'),
        ]);
        $superadmin->assignRole('superadmin');

        // Criar Admin
        $admin = User::create([
            'primeiro_nome' => 'Admin',
            'ultimo_nome' => 'User',
            'email' => 'admin@test.com',
            'data_nascimento' => '1992-06-15',
            'cargo' => CargoEnum::DIRECAO, // 🔹 ENUM correto
            'funcao' => 'Administrador',
            'password' => Hash::make('1234'),
        ]);
        $admin->assignRole('admin');

        // Criar Colaborador
        $colaborador = User::create([
            'primeiro_nome' => 'João',
            'ultimo_nome' => 'Costa',
            'email' => 'colaborador@test.com',
            'data_nascimento' => '1998-07-22',
            'cargo' => CargoEnum::COLABORADOR, // 🔹 ENUM correto
            'funcao' => 'Assistente de Vendas',
            'password' => Hash::make('1234'),
        ]);
        $colaborador->assignRole('colaborador');
    }
}
