<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        return view('pages.admin.items.index', [
            'items' => Item::withCount('userItems')->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.admin.items.form', ['item' => new Item]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'required|in:weapon,armor,accessory,consumable,other',
            'rarity'         => 'required|in:common,uncommon,rare,epic,legendary',
            'strength'       => 'required|integer|min:0|max:100',
            'speed'          => 'required|integer|min:0|max:100',
            'durability'     => 'required|integer|min:0|max:100',
            'magic_property' => 'nullable|string|max:255',
            'required_level' => 'required|integer|min:1|max:100',
        ]);

        $validated['created_by'] = Auth::id();
        Item::create($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Voorwerp succesvol aangemaakt.');
    }

    public function edit(Item $item)
    {
        return view('pages.admin.items.form', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'required|in:weapon,armor,accessory,consumable,other',
            'rarity'         => 'required|in:common,uncommon,rare,epic,legendary',
            'strength'       => 'required|integer|min:0|max:100',
            'speed'          => 'required|integer|min:0|max:100',
            'durability'     => 'required|integer|min:0|max:100',
            'magic_property' => 'nullable|string|max:255',
            'required_level' => 'required|integer|min:1|max:100',
        ]);

        $item->update($validated);

        return redirect()->route('admin.items.index')
            ->with('success', 'Voorwerp succesvol bijgewerkt.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Voorwerp succesvol verwijderd.');
    }

    // US25: toon formulier om item toe te kennen
    public function showAssign(Item $item)
    {
        $players = User::whereHas('role', fn ($q) => $q->where('name', 'player'))
            ->orderBy('username')
            ->get(['id', 'username']);

        return view('pages.admin.items.assign', compact('item', 'players'));
    }

    // US25: item toekennen aan speler
    public function assign(Request $request, Item $item)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $entry = Inventory::firstOrNew([
            'user_id' => $validated['user_id'],
            'item_id' => $item->id,
        ]);

        $entry->quantity = ($entry->quantity ?? 0) + 1;
        $entry->save();

        return redirect()->route('admin.items.index')
            ->with('success', "'{$item->name}' toegekend aan speler.");
    }
}
