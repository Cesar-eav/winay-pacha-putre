<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Contraseña de desarrollo: "password" (default del factory). Cambiar antes de producción.
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@winaypachaputre.cl',
            'is_admin' => true,
        ]);

        $this->call(WinayPlaceholderSeeder::class);
    }
}
