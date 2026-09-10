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
        User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@winaypachaputre.cl'),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            'is_admin' => true,
        ]);

        $this->call(WinayPlaceholderSeeder::class);
    }
}
