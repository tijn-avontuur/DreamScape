<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'rarity',
        'strength',
        'speed',
        'durability',
        'magic_property',
        'required_level',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userItems(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function rarityColor(): string
    {
        return match($this->rarity) {
            'uncommon'  => 'text-green-400',
            'rare'      => 'text-blue-400',
            'epic'      => 'text-purple-400',
            'legendary' => 'text-amber-400',
            default     => 'text-gray-400',
        };
    }

    public function rarityBadgeClass(): string
    {
        return match($this->rarity) {
            'uncommon'  => 'rarity-bg-uncommon border rarity-uncommon',
            'rare'      => 'rarity-bg-rare border rarity-rare',
            'epic'      => 'rarity-bg-epic border rarity-epic',
            'legendary' => 'rarity-bg-legendary border rarity-legendary',
            default     => 'rarity-bg-common border rarity-common',
        };
    }
}
