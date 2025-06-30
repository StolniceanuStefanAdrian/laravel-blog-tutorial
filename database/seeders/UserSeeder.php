<?php

namespace Database\Seeders;

use App\Models\User; // Asigură-te că User este importat
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Asigură-te că Hash este importat

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creează un utilizator administrator
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(), // Marchează emailul ca verificat imediat
        ]);

        // Creează 10 utilizatori obișnuiți folosind factory
        User::factory(10)->create();

        // Creează un utilizator de test specific
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}