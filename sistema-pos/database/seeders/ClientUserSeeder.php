<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientUserSeeder extends Seeder
{
    public function run(): void
    {
        $clientRole = Role::query()->where('nombre', 'cliente')->first();

        if (! $clientRole) {
            return;
        }

        $client = User::query()->firstOrCreate(
            ['email' => 'cliente@pos.local'],
            [
                'name' => 'Cliente Demo',
                'password' => Hash::make('Cliente12345*'),
                'estado' => 'activo',
            ],
        );

        $client->roles()->sync([$clientRole->id]);
    }
}
