<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $player = User::where('email', 'player@dreamscape.test')->first();
        $admin  = User::where('email', 'admin@dreamscape.test')->first();
        $items  = Item::all();

        if ($items->isEmpty()) {
            return;
        }

        // Geef de testspeler 6 willekeurige items
        if ($player) {
            $selectedItems = $items->random(min(6, $items->count()));
            foreach ($selectedItems as $item) {
                Inventory::create([
                    'user_id'     => $player->id,
                    'item_id'     => $item->id,
                    'quantity'    => rand(1, 3),
                    'obtained_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Geef de beheerder 5 vaste items (een van elk type)
        if ($admin) {
            $adminItems = collect([
                $items->where('rarity', 'legendary')->first(),
                $items->where('type', 'weapon')->where('rarity', 'epic')->first(),
                $items->where('type', 'armor')->where('rarity', 'rare')->first(),
                $items->where('type', 'accessory')->where('rarity', 'epic')->first(),
                $items->where('type', 'consumable')->first(),
            ])->filter();

            foreach ($adminItems as $item) {
                Inventory::create([
                    'user_id'     => $admin->id,
                    'item_id'     => $item->id,
                    'quantity'    => 1,
                    'obtained_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }

        // Geef overige spelers ook 2–4 willekeurige items
        $otherPlayers = User::whereHas('role', fn ($q) => $q->where('name', 'player'))
            ->where('email', '!=', 'player@dreamscape.test')
            ->get();

        foreach ($otherPlayers as $user) {
            $randomItems = $items->random(min(rand(2, 4), $items->count()));
            foreach ($randomItems as $item) {
                Inventory::create([
                    'user_id'     => $user->id,
                    'item_id'     => $item->id,
                    'quantity'    => 1,
                    'obtained_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }
}
