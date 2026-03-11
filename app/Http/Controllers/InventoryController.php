<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    private const RARITY_ORDER = ['common' => 1, 'uncommon' => 2, 'rare' => 3, 'epic' => 4, 'legendary' => 5];

    public function index(Request $request)
    {
        $type   = $request->get('type', '');
        $sortBy = $request->get('sort', '');

        $entries = Inventory::with('item')
            ->where('user_id', Auth::id())
            ->when($type, fn ($q) => $q->whereHas('item', fn ($q2) => $q2->where('type', $type)))
            ->get();

        $entries = match ($sortBy) {
            'strength' => $entries->sortByDesc(fn ($e) => $e->item->strength)->values(),
            'rarity'   => $entries->sortByDesc(fn ($e) => self::RARITY_ORDER[$e->item->rarity] ?? 0)->values(),
            default    => $entries,
        };

        return view('pages.inventory.index', compact('entries', 'type', 'sortBy'));
    }
}
