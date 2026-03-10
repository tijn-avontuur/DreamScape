<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

        $items = [
            // Weapons
            ['name' => 'Shadowbane Sword',    'description' => 'A blade forged in the shadow realm, it whispers with dark energy and cleaves through magical barriers.', 'type' => 'weapon',      'rarity' => 'legendary', 'strength' => 95, 'speed' => 70, 'durability' => 80, 'magic_property' => 'Shadow Strike: deals extra void damage on critical hits', 'required_level' => 40],
            ['name' => 'Iron Longsword',       'description' => 'A sturdy and reliable sword carried by many adventurers across the realm.',                              'type' => 'weapon',      'rarity' => 'common',    'strength' => 30, 'speed' => 40, 'durability' => 60, 'magic_property' => null,                                                               'required_level' => 1],
            ['name' => 'Frostbite Dagger',     'description' => 'A short blade enchanted with ice magic. Slows enemies on hit.',                                         'type' => 'weapon',      'rarity' => 'rare',      'strength' => 55, 'speed' => 85, 'durability' => 45, 'magic_property' => 'Frost Slow: reduces enemy speed by 25% for 3 seconds',           'required_level' => 20],
            ['name' => "Stormcaller's Staff",  'description' => 'Channels lightning through its ancient wood. Grants mastery over storm spells.',                        'type' => 'weapon',      'rarity' => 'epic',      'strength' => 78, 'speed' => 35, 'durability' => 50, 'magic_property' => 'Chain Lightning: chance to arc damage to nearby enemies',         'required_level' => 30],
            ['name' => "Hunter's Shortbow",    'description' => 'A lightweight bow crafted from elven pine, favored by scouts and rangers.',                             'type' => 'weapon',      'rarity' => 'uncommon',  'strength' => 45, 'speed' => 75, 'durability' => 55, 'magic_property' => 'Eagle Eye: +10% accuracy at long range',                          'required_level' => 10],
            ['name' => 'Rusted Axe',           'description' => 'A worn-down axe found in the dungeon. Not pretty, but still swings hard.',                             'type' => 'weapon',      'rarity' => 'common',    'strength' => 25, 'speed' => 30, 'durability' => 35, 'magic_property' => null,                                                               'required_level' => 1],

            // Armor
            ['name' => 'Dragonscale Plate',   'description' => 'Forged from the scales of an ancient dragon. Nearly impenetrable, glowing faintly with draconic energy.', 'type' => 'armor',   'rarity' => 'legendary', 'strength' => 20, 'speed' => 25, 'durability' => 98, 'magic_property' => 'Dragon Ward: 15% chance to reflect magic damage back to attacker', 'required_level' => 45],
            ['name' => 'Chain Mail Vest',      'description' => 'Interlocked iron rings provide solid protection without sacrificing too much mobility.',                    'type' => 'armor',   'rarity' => 'common',    'strength' => 10, 'speed' => 50, 'durability' => 65, 'magic_property' => null,                                                                'required_level' => 1],
            ['name' => 'Moonweave Robe',       'description' => 'Woven from moonlight threads, this robe enhances magical abilities and shimmers in the dark.',             'type' => 'armor',   'rarity' => 'epic',      'strength' =>  5, 'speed' => 70, 'durability' => 40, 'magic_property' => 'Lunar Barrier: absorbs up to 200 magical damage per encounter',   'required_level' => 28],
            ['name' => 'Forest Leather Armor', 'description' => 'Lightweight armor made from tanned forest hide, popular among rogues and rangers.',                        'type' => 'armor',   'rarity' => 'uncommon',  'strength' => 15, 'speed' => 65, 'durability' => 55, 'magic_property' => 'Camouflage: reduces detection range of enemies by 20%',           'required_level' => 8],
            ['name' => 'Steel Breastplate',    'description' => 'Standard-issue armor for guards and soldiers across the kingdom.',                                         'type' => 'armor',   'rarity' => 'common',    'strength' => 12, 'speed' => 40, 'durability' => 70, 'magic_property' => null,                                                                'required_level' => 5],
            ['name' => 'Abyssal Cloak',        'description' => 'A cloak imbued with void energy, making the wearer partially invisible in dim light.',                    'type' => 'armor',   'rarity' => 'rare',      'strength' =>  8, 'speed' => 80, 'durability' => 45, 'magic_property' => 'Phase Shift: 10% chance to dodge physical attacks completely',    'required_level' => 18],

            // Accessories
            ['name' => 'Amulet of Eternal Life', 'description' => "A golden amulet pulsing with healing magic. Slowly regenerates the wearer's health over time.", 'type' => 'accessory', 'rarity' => 'legendary', 'strength' =>  0, 'speed' =>  0, 'durability' => 90, 'magic_property' => 'Regeneration: restores 5 HP per second passively',             'required_level' => 35],
            ['name' => 'Ring of Swiftness',       'description' => 'An enchanted ring that makes the wearer feel light as a feather.',                              'type' => 'accessory', 'rarity' => 'rare',      'strength' =>  5, 'speed' => 90, 'durability' => 70, 'magic_property' => 'Haste: movement speed increased by 30%',                         'required_level' => 15],
            ['name' => 'Lucky Coin',              'description' => 'An old coin rubbed smooth. Said to bring fortune to those who carry it.',                       'type' => 'accessory', 'rarity' => 'common',    'strength' =>  0, 'speed' =>  0, 'durability' => 100,'magic_property' => 'Fortune: +2% chance on rare item drops',                        'required_level' => 1],
            ['name' => 'Arcane Focus Crystal',    'description' => 'Amplifies magical spells when held. A must-have for any serious wizard.',                       'type' => 'accessory', 'rarity' => 'epic',      'strength' =>  0, 'speed' =>  0, 'durability' => 60, 'magic_property' => 'Spell Amplification: all spells deal 25% additional damage',      'required_level' => 25],
            ['name' => 'Iron Bracers',            'description' => 'Simple iron bracers that add a bit of extra punch to melee strikes.',                          'type' => 'accessory', 'rarity' => 'common',    'strength' => 20, 'speed' =>  0, 'durability' => 65, 'magic_property' => null,                                                             'required_level' => 1],
            ['name' => 'Boots of the Wind',       'description' => 'Ancient boots whispering with wind spirits. Nearly doubles movement speed for short bursts.',  'type' => 'accessory', 'rarity' => 'epic',      'strength' =>  0, 'speed' => 95, 'durability' => 55, 'magic_property' => 'Wind Rush: sprint speed doubled for 5 seconds, 30s cooldown',    'required_level' => 22],

            // Consumables
            ['name' => 'Greater Healing Potion', 'description' => 'A vibrant red potion that restores a significant amount of health when consumed.', 'type' => 'consumable', 'rarity' => 'uncommon', 'strength' => 0, 'speed' => 0, 'durability' => 100, 'magic_property' => 'Instant Heal: restores 150 HP immediately',                    'required_level' => 1],
            ['name' => 'Elixir of Power',         'description' => "Temporarily doubles the drinker's power for 5 minutes. Tastes suspiciously like burnt caramel.", 'type' => 'consumable', 'rarity' => 'rare', 'strength' => 100, 'speed' => 0, 'durability' => 100, 'magic_property' => 'Power Surge: strength doubled for 5 minutes, single use', 'required_level' => 10],
        ];

        foreach ($items as $data) {
            Item::create(array_merge($data, ['created_by' => $admin?->id]));
        }
    }
}
