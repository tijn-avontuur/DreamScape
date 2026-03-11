<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => $this->faker->words(3, true),
            'description'    => $this->faker->sentence(),
            'type'           => $this->faker->randomElement(['weapon', 'armor', 'accessory', 'consumable']),
            'rarity'         => $this->faker->randomElement(['common', 'uncommon', 'rare', 'epic', 'legendary']),
            'strength'       => $this->faker->numberBetween(0, 100),
            'speed'          => $this->faker->numberBetween(0, 100),
            'durability'     => $this->faker->numberBetween(0, 100),
            'magic_property' => $this->faker->optional()->sentence(),
            'required_level' => $this->faker->numberBetween(1, 100),
            'created_by'     => null,
        ];
    }
}
