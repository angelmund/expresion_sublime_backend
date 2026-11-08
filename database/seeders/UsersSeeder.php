<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin prueba',   'email' => 'admin@gmail.com',   'role' => 'admin'],
            ['name' => 'Cliente prueba', 'email' => 'cliente1@gmail.com', 'role' => 'cliente'],
            ['name' => 'Usuario prueba', 'email' => 'usuario@gmail.com', 'role' => 'user'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('Allison640'),
                    'activo' => true,
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
