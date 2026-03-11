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
        // Rollen aanmaken
        $adminRole  = Role::create(['name' => 'admin']);
        $playerRole = Role::create(['name' => 'player']);

        // Beheerder account
        User::factory()->create([
            'username' => 'Admin',
            'email'    => 'admin@dreamscape.test',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);

        // Test speler account
        User::factory()->create([
            'username' => 'TestSpeler',
            'email'    => 'player@dreamscape.test',
            'password' => Hash::make('password'),
            'role_id'  => $playerRole->id,
        ]);

        // Extra willekeurige spelers
        User::factory(8)->create(['role_id' => $playerRole->id]);

        // Voorwerpen aanmaken
        $this->call(ItemSeeder::class);

        // Inventaris vullen voor spelers en beheerder
        $this->call(InventorySeeder::class);
    }
}
