<?php

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Zorg dat rollen bestaan voor elke test
beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'player']);
});

// ─── US2: Validatie bij registratie ──────────────────────────────────────────

test('US2: registratie mislukt bij lege velden', function () {
    $this->post(route('register.store'), [])
        ->assertSessionHasErrors(['username', 'email', 'password']);
});

test('US2: registratie mislukt bij bestaande gebruikersnaam', function () {
    $role = Role::where('name', 'player')->first();
    User::factory()->create(['username' => 'BestaandeNaam', 'role_id' => $role?->id]);

    $this->post(route('register.store'), [
        'username'              => 'BestaandeNaam',
        'email'                 => 'nieuw@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('username');
});

test('US2: registratie mislukt bij te kort wachtwoord', function () {
    $this->post(route('register.store'), [
        'username'              => 'NieuweSpeler',
        'email'                 => 'nieuw@test.com',
        'password'              => 'kort',
        'password_confirmation' => 'kort',
    ])->assertSessionHasErrors('password');
});

// ─── US7: Toegangsbeveiliging ─────────────────────────────────────────────────

test('US7: niet ingelogde gebruiker wordt omgeleid bij dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('US7: niet ingelogde gebruiker wordt omgeleid bij items', function () {
    $this->get(route('items.index'))->assertRedirect(route('login'));
});

test('US7: niet ingelogde gebruiker wordt omgeleid bij inventaris', function () {
    $this->get(route('inventory.index'))->assertRedirect(route('login'));
});

// ─── US8: Rollenbeheer ────────────────────────────────────────────────────────

test('US8: rollen speler en beheerder bestaan in database', function () {
    expect(Role::where('name', 'admin')->exists())->toBeTrue();
    expect(Role::where('name', 'player')->exists())->toBeTrue();
});

test('US8: nieuwe registratie krijgt spelerrol', function () {
    $this->post(route('register.store'), [
        'username'              => 'NieuweSpeler',
        'email'                 => 'speler@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'speler@test.com')->first();
    expect($user->role->name)->toBe('player');
});

// ─── US9: Admin toegang beperken ─────────────────────────────────────────────

test('US9: speler kan geen toegang krijgen tot adminpaneel', function () {
    $playerRole = Role::where('name', 'player')->first();
    $player = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($player)
        ->get(route('admin.items.index'))
        ->assertForbidden();
});

test('US9: beheerder heeft wel toegang tot adminpaneel', function () {
    $adminRole = Role::where('name', 'admin')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);

    $this->actingAs($admin)
        ->get(route('admin.items.index'))
        ->assertOk();
});

// ─── US10: Itemoverzicht bekijken ─────────────────────────────────────────────

test('US10: ingelogde speler ziet itemoverzicht', function () {
    $playerRole = Role::where('name', 'player')->first();
    $player = User::factory()->create(['role_id' => $playerRole?->id]);

    $item = Item::factory()->create(['name' => 'Testvuurzwaard', 'type' => 'weapon']);

    $this->actingAs($player)
        ->get(route('items.index'))
        ->assertOk()
        ->assertSee('Testvuurzwaard')
        ->assertSee('Wapen');
});

// ─── US11: Itemdetails bekijken ───────────────────────────────────────────────

test('US11: detailpagina toont alle vereiste velden', function () {
    $playerRole = Role::where('name', 'player')->first();
    $player = User::factory()->create(['role_id' => $playerRole?->id]);

    $item = Item::factory()->create([
        'name'           => 'Magiestaf',
        'description'    => 'Een krachtige staf.',
        'type'           => 'weapon',
        'rarity'         => 'epic',
        'strength'       => 80,
        'speed'          => 40,
        'durability'     => 60,
        'magic_property' => 'Vuurbom: ontploffing bij treffer',
        'required_level' => 30,
    ]);

    $this->actingAs($player)
        ->get(route('items.show', $item))
        ->assertOk()
        ->assertSee('Magiestaf')
        ->assertSee('Een krachtige staf.')
        ->assertSee('Wapen')
        ->assertSee('epic')
        ->assertSee('80')
        ->assertSee('40')
        ->assertSee('60')
        ->assertSee('Vuurbom: ontploffing bij treffer');
});

// ─── US15: Eigen inventory bekijken ──────────────────────────────────────────

test('US15: speler ziet alleen eigen items in inventaris', function () {
    $playerRole = Role::where('name', 'player')->first();
    $speler1 = User::factory()->create(['role_id' => $playerRole?->id]);
    $speler2 = User::factory()->create(['role_id' => $playerRole?->id]);

    $eigenItem = Item::factory()->create(['name' => 'Eigen Zwaard']);
    $andersItem = Item::factory()->create(['name' => 'Ander Zwaard']);

    Inventory::create(['user_id' => $speler1->id, 'item_id' => $eigenItem->id, 'quantity' => 1]);
    Inventory::create(['user_id' => $speler2->id, 'item_id' => $andersItem->id, 'quantity' => 1]);

    $this->actingAs($speler1)
        ->get(route('inventory.index'))
        ->assertOk()
        ->assertSee('Eigen Zwaard')
        ->assertDontSee('Ander Zwaard');
});

// ─── US23: Accounts aanmaken als beheerder ────────────────────────────────────

test('US23: beheerder kan nieuw account aanmaken met rol', function () {
    $adminRole  = Role::where('name', 'admin')->first();
    $playerRole = Role::where('name', 'player')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'username'              => 'NieuweSpeler',
            'email'                 => 'nieuwspeler@test.com',
            'password'              => 'DreamScape!Test#2026',
            'password_confirmation' => 'DreamScape!Test#2026',
            'role_id'               => $playerRole->id,
        ])
        ->assertRedirect(route('admin.users.index'));

    expect(User::where('email', 'nieuwspeler@test.com')->exists())->toBeTrue();
    expect(User::where('email', 'nieuwspeler@test.com')->first()->role->name)->toBe('player');
});

test('US23: speler heeft geen toegang tot gebruikersbeheer', function () {
    $playerRole = Role::where('name', 'player')->first();
    $player = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($player)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

// ─── US24: Items beheren (CRUD) ───────────────────────────────────────────────

test('US24: beheerder kan nieuw item aanmaken', function () {
    $adminRole = Role::where('name', 'admin')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);

    $this->actingAs($admin)
        ->post(route('admin.items.store'), [
            'name'           => 'Testitem',
            'description'    => 'Een testitem.',
            'type'           => 'weapon',
            'rarity'         => 'common',
            'strength'       => 50,
            'speed'          => 50,
            'durability'     => 50,
            'magic_property' => null,
            'required_level' => 1,
        ])
        ->assertRedirect(route('admin.items.index'));

    expect(Item::where('name', 'Testitem')->exists())->toBeTrue();
});

test('US24: beheerder kan item wijzigen', function () {
    $adminRole = Role::where('name', 'admin')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);
    $item = Item::factory()->create(['name' => 'Oud Zwaard']);

    $this->actingAs($admin)
        ->put(route('admin.items.update', $item), [
            'name'           => 'Nieuw Zwaard',
            'description'    => 'Bijgewerkt.',
            'type'           => 'weapon',
            'rarity'         => 'rare',
            'strength'       => 70,
            'speed'          => 60,
            'durability'     => 50,
            'magic_property' => null,
            'required_level' => 15,
        ])
        ->assertRedirect(route('admin.items.index'));

    expect($item->fresh()->name)->toBe('Nieuw Zwaard');
});

test('US24: beheerder kan item verwijderen', function () {
    $adminRole = Role::where('name', 'admin')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);
    $item = Item::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.items.destroy', $item))
        ->assertRedirect(route('admin.items.index'));

    expect(Item::find($item->id))->toBeNull();
});

test('US24: statistieken worden gevalideerd op 0-100', function () {
    $adminRole = Role::where('name', 'admin')->first();
    $admin = User::factory()->create(['role_id' => $adminRole?->id]);

    $this->actingAs($admin)
        ->post(route('admin.items.store'), [
            'name'           => 'Foutief Item',
            'type'           => 'weapon',
            'rarity'         => 'common',
            'strength'       => 150,
            'speed'          => -5,
            'durability'     => 50,
            'required_level' => 1,
        ])
        ->assertSessionHasErrors(['strength', 'speed']);
});
