<?php

use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

// Zorg dat rollen bestaan voor elke test
beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'player']);
});

// ─── US3: Profiel bekijken ────────────────────────────────────────────────────

test('US3: ingelogde gebruiker kan profielpagina bekijken', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create([
        'username' => 'TestSpeler',
        'role_id'  => $playerRole?->id,
    ]);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee('TestSpeler');
});

test('US3: profielpagina toont gebruikersnaam van ingelogde gebruiker', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create([
        'username' => 'MijnNaam',
        'role_id'  => $playerRole?->id,
    ]);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee('MijnNaam');
});

test('US3: niet-ingelogde gebruiker wordt omgeleid bij profielpagina', function () {
    $this->get(route('profile.show'))
        ->assertRedirect(route('login'));
});

// ─── US4: Profiel aanpassen ───────────────────────────────────────────────────

test('US4: profielgegevens kunnen worden bijgewerkt', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('username', 'NieuweNaam')
        ->set('email', 'nieuw@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->username)->toBe('NieuweNaam');
    expect($user->email)->toBe('nieuw@example.com');
});

test('US4: aanpassingen worden opgeslagen in de database', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create([
        'username' => 'OudeNaam',
        'email'    => 'oud@example.com',
        'role_id'  => $playerRole?->id,
    ]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('username', 'OpgeslagenNaam')
        ->set('email', 'opgeslagen@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    expect(User::find($user->id)->username)->toBe('OpgeslagenNaam');
    expect(User::find($user->id)->email)->toBe('opgeslagen@example.com');
});

test('US4: profiel validatie mislukt bij lege gebruikersnaam', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('username', '')
        ->set('email', $user->email)
        ->call('updateProfileInformation')
        ->assertHasErrors(['username']);
});

test('US4: profiel validatie mislukt bij ongeldig e-mailadres', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('username', $user->username)
        ->set('email', 'geen-geldig-email')
        ->call('updateProfileInformation')
        ->assertHasErrors(['email']);
});

test('US4: profiel validatie mislukt bij bestaande gebruikersnaam', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user1 = User::factory()->create(['username' => 'BestaandeNaam', 'role_id' => $playerRole?->id]);
    $user2 = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($user2);

    Livewire::test('pages::settings.profile')
        ->set('username', 'BestaandeNaam')
        ->set('email', $user2->email)
        ->call('updateProfileInformation')
        ->assertHasErrors(['username']);
});

test('US4: instellingenpagina profiel is bereikbaar', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk();
});

// ─── US12: Items zoeken op naam ───────────────────────────────────────────────

test('US12: zoeken op naam toont overeenkomend item', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'Vuurzwaard', 'type' => 'weapon', 'rarity' => 'common']);
    Item::factory()->create(['name' => 'IJsschild',  'type' => 'armor',  'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index', ['search' => 'Vuur']))
        ->assertOk()
        ->assertSee('Vuurzwaard')
        ->assertDontSee('IJsschild');
});

test('US12: zoeken zonder resultaten toont lege staat', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'Draakvleugel', 'type' => 'armor', 'rarity' => 'rare']);

    $this->actingAs($user)
        ->get(route('items.index', ['search' => 'XYZBestaatNiet']))
        ->assertOk()
        ->assertDontSee('Draakvleugel');
});

test('US12: zoeken zonder filter toont alle items', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'ItemAlpha', 'type' => 'weapon', 'rarity' => 'common']);
    Item::factory()->create(['name' => 'ItemBeta',  'type' => 'armor',  'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index'))
        ->assertOk()
        ->assertSee('ItemAlpha')
        ->assertSee('ItemBeta');
});

// ─── US13: Items filteren op type ────────────────────────────────────────────

test('US13: filteren op type wapen toont alleen wapens', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'Strijdbijl',  'type' => 'weapon',    'rarity' => 'common']);
    Item::factory()->create(['name' => 'Leerharnas',  'type' => 'armor',     'rarity' => 'common']);
    Item::factory()->create(['name' => 'Gouden Ring', 'type' => 'accessory', 'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index', ['type' => 'weapon']))
        ->assertOk()
        ->assertSee('Strijdbijl')
        ->assertDontSee('Leerharnas')
        ->assertDontSee('Gouden Ring');
});

test('US13: filteren op type uitrusting toont alleen uitrusting', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'Scherpzwaard', 'type' => 'weapon', 'rarity' => 'common']);
    Item::factory()->create(['name' => 'Draakpantser', 'type' => 'armor',  'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index', ['type' => 'armor']))
        ->assertOk()
        ->assertSee('Draakpantser')
        ->assertDontSee('Scherpzwaard');
});

// ─── US14: Filteren op zeldzaamheid ──────────────────────────────────────────

test('US14: filteren op zeldzaamheid legendary toont alleen legendarische items', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'LegendStaf',   'type' => 'weapon', 'rarity' => 'legendary']);
    Item::factory()->create(['name' => 'GewoonZwaard', 'type' => 'weapon', 'rarity' => 'common']);
    Item::factory()->create(['name' => 'ZeldzaamBoog', 'type' => 'weapon', 'rarity' => 'rare']);

    $this->actingAs($user)
        ->get(route('items.index', ['rarity' => 'legendary']))
        ->assertOk()
        ->assertSee('LegendStaf')
        ->assertDontSee('GewoonZwaard')
        ->assertDontSee('ZeldzaamBoog');
});

test('US14: filteren op zeldzaamheid epic toont alleen epische items', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'EpischSchild', 'type' => 'armor', 'rarity' => 'epic']);
    Item::factory()->create(['name' => 'GewoonSchild', 'type' => 'armor', 'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index', ['rarity' => 'epic']))
        ->assertOk()
        ->assertSee('EpischSchild')
        ->assertDontSee('GewoonSchild');
});

test('US14: combinatie van type en zeldzaamheid filter werkt correct', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    Item::factory()->create(['name' => 'EpischWapen',    'type' => 'weapon', 'rarity' => 'epic']);
    Item::factory()->create(['name' => 'EpischeRustng',  'type' => 'armor',  'rarity' => 'epic']);
    Item::factory()->create(['name' => 'GewoonWapen',    'type' => 'weapon', 'rarity' => 'common']);

    $this->actingAs($user)
        ->get(route('items.index', ['type' => 'weapon', 'rarity' => 'epic']))
        ->assertOk()
        ->assertSee('EpischWapen')
        ->assertDontSee('EpischeRustng')
        ->assertDontSee('GewoonWapen');
});

// ─── US16: Inventory sorteren ─────────────────────────────────────────────────

test('US16: sorteren op kracht zet sterkste item eerst', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $weak   = Item::factory()->create(['name' => 'ZwakZwaard',   'type' => 'weapon', 'rarity' => 'common', 'strength' => 10]);
    $strong = Item::factory()->create(['name' => 'SterkZwaard',  'type' => 'weapon', 'rarity' => 'common', 'strength' => 90]);

    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $weak->id,   'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $strong->id, 'quantity' => 1]);

    $response = $this->actingAs($user)
        ->get(route('inventory.index', ['sort' => 'strength']))
        ->assertOk()
        ->assertSee('SterkZwaard')
        ->assertSee('ZwakZwaard');

    $content = $response->getContent();
    expect(strpos($content, 'SterkZwaard'))->toBeLessThan(strpos($content, 'ZwakZwaard'));
});

test('US16: sorteren op zeldzaamheid zet legendarisch item eerst', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $common    = Item::factory()->create(['name' => 'GewoonItem',      'type' => 'weapon', 'rarity' => 'common',    'strength' => 50]);
    $legendary = Item::factory()->create(['name' => 'LegendairItem',   'type' => 'weapon', 'rarity' => 'legendary', 'strength' => 50]);

    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $common->id,    'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $legendary->id, 'quantity' => 1]);

    $response = $this->actingAs($user)
        ->get(route('inventory.index', ['sort' => 'rarity']))
        ->assertOk();

    $content = $response->getContent();
    expect(strpos($content, 'LegendairItem'))->toBeLessThan(strpos($content, 'GewoonItem'));
});

// ─── US17: Inventory filteren op type ────────────────────────────────────────

test('US17: filteren op type wapen toont alleen eigen wapens', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $weapon = Item::factory()->create(['name' => 'MijnZwaard',  'type' => 'weapon', 'rarity' => 'common']);
    $armor  = Item::factory()->create(['name' => 'MijnHarnas',  'type' => 'armor',  'rarity' => 'common']);

    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $weapon->id, 'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $armor->id,  'quantity' => 1]);

    $this->actingAs($user)
        ->get(route('inventory.index', ['type' => 'weapon']))
        ->assertOk()
        ->assertSee('MijnZwaard')
        ->assertDontSee('MijnHarnas');
});

test('US17: filter heeft geen invloed op inventaris van andere speler', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user1 = User::factory()->create(['role_id' => $playerRole?->id]);
    $user2 = User::factory()->create(['role_id' => $playerRole?->id]);

    $item1 = Item::factory()->create(['name' => 'SpelerEenWapen', 'type' => 'weapon', 'rarity' => 'common']);
    $item2 = Item::factory()->create(['name' => 'SpelerTweeItem', 'type' => 'weapon', 'rarity' => 'common']);

    \App\Models\Inventory::create(['user_id' => $user1->id, 'item_id' => $item1->id, 'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user2->id, 'item_id' => $item2->id, 'quantity' => 1]);

    $this->actingAs($user1)
        ->get(route('inventory.index', ['type' => 'weapon']))
        ->assertOk()
        ->assertSee('SpelerEenWapen')
        ->assertDontSee('SpelerTweeItem');
});

test('US17: filter zonder type toont alle eigen items', function () {
    $playerRole = Role::where('name', 'player')->first();
    $user = User::factory()->create(['role_id' => $playerRole?->id]);

    $w = Item::factory()->create(['name' => 'BoogWapen',    'type' => 'weapon',    'rarity' => 'common']);
    $a = Item::factory()->create(['name' => 'PlaatPantser', 'type' => 'armor',     'rarity' => 'common']);
    $x = Item::factory()->create(['name' => 'GoudRing',     'type' => 'accessory', 'rarity' => 'common']);

    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $w->id, 'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $a->id, 'quantity' => 1]);
    \App\Models\Inventory::create(['user_id' => $user->id, 'item_id' => $x->id, 'quantity' => 1]);

    $this->actingAs($user)
        ->get(route('inventory.index'))
        ->assertOk()
        ->assertSee('BoogWapen')
        ->assertSee('PlaatPantser')
        ->assertSee('GoudRing');
});
