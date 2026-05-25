<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            CatalogSeeder::class,
            ProductsSeeder::class,
            ClientUserSeeder::class,
        ]);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@pos.local'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin12345*'),
                'estado' => 'activo',
            ],
        );

        $adminRole = Role::query()->where('nombre', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }
    }
}
