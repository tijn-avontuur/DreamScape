<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemCatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $type   = $request->get('type', '');
        $rarity = $request->get('rarity', '');

        $items = Item::query()
            ->when($search, fn ($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->when($type,   fn ($q) => $q->where('type', $type))
            ->when($rarity, fn ($q) => $q->where('rarity', $rarity))
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('pages.items.index', compact('items', 'search', 'type', 'rarity'));
    }
}
