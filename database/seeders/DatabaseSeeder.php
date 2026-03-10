<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole  = Role::create(['name' => 'admin']);
        $playerRole = Role::create(['name' => 'player']);

        // Admin account
        User::factory()->create([
            'username' => 'Admin',
            'email'    => 'admin@dreamscape.test',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);

        // Test player account
        User::factory()->create([
            'username' => 'TestPlayer',
            'email'    => 'player@dreamscape.test',
            'password' => Hash::make('password'),
            'role_id'  => $playerRole->id,
        ]);

        // Extra random players
        User::factory(8)->create(['role_id' => $playerRole->id]);

        // Seed items
        $this->call(ItemSeeder::class);
    }
}
