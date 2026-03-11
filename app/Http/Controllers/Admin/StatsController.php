<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;

class StatsController extends Controller
{
    // US25: statistieken inzien
    public function index()
    {
        $items = Item::withCount('userItems')
            ->orderByDesc('user_items_count')
            ->get();

        $totalPlayers    = User::whereHas('role', fn ($q) => $q->where('name', 'player'))->count();
        $totalItemTypes  = Item::count();
        $totalAssigned   = Inventory::sum('quantity');

        return view('pages.admin.stats', compact('items', 'totalPlayers', 'totalItemTypes', 'totalAssigned'));
    }
}
