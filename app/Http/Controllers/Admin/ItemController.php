<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        return view('pages.admin.items.index', [
            'items' => Item::orderBy('type')->orderBy('name')->get(),
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
}
