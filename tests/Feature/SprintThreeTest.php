<?php

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Trade;
use App\Models\TradeItem;
use App\Models\User;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'player']);
});

// ─── Hulpfunctie ──────────────────────────────────────────────────────────────

function makePlayer(): User
{
    $role = Role::where('name', 'player')->first();
    return User::factory()->create(['role_id' => $role?->id]);
}

function giveItem(User $user, ?Item $item = null, int $qty = 1): Item
{
    $item ??= Item::factory()->create();
    Inventory::create(['user_id' => $user->id, 'item_id' => $item->id, 'quantity' => $qty]);
    return $item;
}

// ─── US18: Trade starten ─────────────────────────────────────────────────────

test('US18: speler kan ruilverzoek versturen', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $this->actingAs($sender)
        ->post(route('trades.store'), [
            'receiver_id' => $receiver->id,
            'item_id'     => $item->id,
        ])
        ->assertRedirect(route('trades.index'));

    expect(Trade::where('sender_id', $sender->id)->where('receiver_id', $receiver->id)->exists())->toBeTrue();
});

test('US18: ruilverzoek wordt opgeslagen met juist item', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $this->actingAs($sender)
        ->post(route('trades.store'), [
            'receiver_id' => $receiver->id,
            'item_id'     => $item->id,
        ]);

    $trade = Trade::where('sender_id', $sender->id)->first();
    expect($trade)->not->toBeNull();
    expect($trade->tradeItems()->where('item_id', $item->id)->exists())->toBeTrue();
});

test('US18: speler kan geen ruilverzoek sturen van een item dat hij niet bezit', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = Item::factory()->create(); // niet in sender's inventory

    $this->actingAs($sender)
        ->post(route('trades.store'), [
            'receiver_id' => $receiver->id,
            'item_id'     => $item->id,
        ])
        ->assertStatus(404);
});

test('US18: formulierpagina is bereikbaar voor ingelogde speler', function () {
    $user = makePlayer();
    giveItem($user);

    $this->actingAs($user)
        ->get(route('trades.create'))
        ->assertOk();
});

// ─── US19: Trade ontvangen ────────────────────────────────────────────────────

test('US19: ontvanger ziet inkomend ruilverzoek in overzicht', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)
        ->get(route('trades.index'))
        ->assertOk()
        ->assertSee($sender->username);
});

test('US19: status van ruilverzoek is zichtbaar in overzicht', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)
        ->get(route('trades.index'))
        ->assertOk()
        ->assertSee('Accepteren')
        ->assertSee('Weigeren');
});

// ─── US20: Trade accepteren ───────────────────────────────────────────────────

test('US20: accepteren van trade verplaatst item naar ontvanger', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)
        ->patch(route('trades.accept', $trade))
        ->assertRedirect(route('trades.index'));

    // Item is weg bij sender
    expect(Inventory::where('user_id', $sender->id)->where('item_id', $item->id)->exists())->toBeFalse();
    // Item is nu bij receiver
    expect(Inventory::where('user_id', $receiver->id)->where('item_id', $item->id)->exists())->toBeTrue();
});

test('US20: trade status wordt accepted na acceptatie', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)->patch(route('trades.accept', $trade));

    expect($trade->fresh()->status)->toBe('accepted');
});

test('US20: alleen de ontvanger kan een trade accepteren', function () {
    $sender      = makePlayer();
    $receiver    = makePlayer();
    $bystander   = makePlayer();
    $item        = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($bystander)
        ->patch(route('trades.accept', $trade))
        ->assertForbidden();
});

// ─── US21: Trade weigeren ─────────────────────────────────────────────────────

test('US21: weigeren van trade geeft status rejected', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)
        ->patch(route('trades.reject', $trade))
        ->assertRedirect(route('trades.index'));

    expect($trade->fresh()->status)->toBe('rejected');
});

test('US21: weigeren verandert niets aan inventaris', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)->patch(route('trades.reject', $trade));

    // Item blijft bij sender
    expect(Inventory::where('user_id', $sender->id)->where('item_id', $item->id)->exists())->toBeTrue();
    // Receiver heeft het item niet gekregen
    expect(Inventory::where('user_id', $receiver->id)->where('item_id', $item->id)->exists())->toBeFalse();
});

test('US21: alleen de ontvanger kan een trade weigeren', function () {
    $sender    = makePlayer();
    $receiver  = makePlayer();
    $bystander = makePlayer();
    $item      = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($bystander)
        ->patch(route('trades.reject', $trade))
        ->assertForbidden();
});

// ─── US22: Notificaties ontvangen ────────────────────────────────────────────

test('US22: ontvanger krijgt notificatie bij nieuw ruilverzoek', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $this->actingAs($sender)
        ->post(route('trades.store'), [
            'receiver_id' => $receiver->id,
            'item_id'     => $item->id,
        ]);

    $notification = Notification::where('user_id', $receiver->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain($sender->username);
});

test('US22: verzender krijgt notificatie bij acceptatie', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)->patch(route('trades.accept', $trade));

    $notification = Notification::where('user_id', $sender->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('geaccepteerd');
});

test('US22: verzender krijgt notificatie bij weigering', function () {
    $sender   = makePlayer();
    $receiver = makePlayer();
    $item     = giveItem($sender);

    $trade = Trade::create(['sender_id' => $sender->id, 'receiver_id' => $receiver->id, 'status' => 'pending']);
    TradeItem::create(['trade_id' => $trade->id, 'item_id' => $item->id, 'from_user_id' => $sender->id, 'quantity' => 1]);

    $this->actingAs($receiver)->patch(route('trades.reject', $trade));

    $notification = Notification::where('user_id', $sender->id)->first();
    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain('geweigerd');
});

test('US22: dashboard toont notificaties voor ingelogde gebruiker', function () {
    $user = makePlayer();
    Notification::send($user->id, 'TestMelding: ruilverzoek ontvangen.');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('TestMelding: ruilverzoek ontvangen.');
});

// ─── US25: Items toekennen & statistieken ─────────────────────────────────────

function makeAdmin(): User
{
    $role = Role::where('name', 'admin')->first();
    return User::factory()->create(['role_id' => $role?->id]);
}

test('US25: admin kan item toekennen aan speler', function () {
    $admin  = makeAdmin();
    $player = makePlayer();
    $item   = Item::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.items.assign', $item), ['user_id' => $player->id])
        ->assertRedirect(route('admin.items.index'));

    expect(Inventory::where('user_id', $player->id)->where('item_id', $item->id)->exists())->toBeTrue();
});

test('US25: niet-admin kan geen item toekennen', function () {
    $player = makePlayer();
    $item   = Item::factory()->create();

    $this->actingAs($player)
        ->post(route('admin.items.assign', $item), ['user_id' => $player->id])
        ->assertForbidden();
});

test('US25: toekenformulier is bereikbaar voor admin', function () {
    $admin = makeAdmin();
    $item  = Item::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.items.assign.show', $item))
        ->assertOk();
});

test('US25: statistiekenpagina toont aantal bezitters per item', function () {
    $admin   = makeAdmin();
    $player1 = makePlayer();
    $player2 = makePlayer();
    $item    = Item::factory()->create();

    giveItem($player1, $item);
    giveItem($player2, $item);

    $this->actingAs($admin)
        ->get(route('admin.stats'))
        ->assertOk()
        ->assertSee($item->name)
        ->assertSee('2');
});

test('US25: statistieken kloppen met database', function () {
    $admin  = makeAdmin();
    $player = makePlayer();
    $item   = Item::factory()->create();

    giveItem($player, $item);

    $count = Inventory::where('item_id', $item->id)->count();
    expect($count)->toBe(1);

    $this->actingAs($admin)
        ->post(route('admin.items.assign', $item), ['user_id' => $player->id]);

    $updatedQuantity = Inventory::where('user_id', $player->id)->where('item_id', $item->id)->value('quantity');
    expect($updatedQuantity)->toBe(2);
});
