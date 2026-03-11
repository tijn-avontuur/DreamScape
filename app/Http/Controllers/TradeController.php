<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Notification;
use App\Models\Trade;
use App\Models\TradeItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    // US19: overzicht inkomende + uitgaande trades
    public function index()
    {
        $user = Auth::user();

        $incoming = Trade::with(['sender', 'tradeItems.item'])
            ->where('receiver_id', $user->id)
            ->latest()
            ->get();

        $outgoing = Trade::with(['receiver', 'tradeItems.item'])
            ->where('sender_id', $user->id)
            ->latest()
            ->get();

        return view('pages.trades.index', compact('incoming', 'outgoing'));
    }

    // US18: formulier om trade te starten
    public function create()
    {
        $user = Auth::user();

        $players   = User::where('id', '!=', $user->id)->get(['id', 'username']);
        $inventory = Inventory::with('item')->where('user_id', $user->id)->get();

        return view('pages.trades.create', compact('players', 'inventory'));
    }

    // US18: opslaan van trade-verzoek
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'item_id'     => ['required', 'exists:items,id'],
        ]);

        abort_if((int) $validated['receiver_id'] === $user->id, 422);

        // Verifieer dat de verzender het item bezit
        $entry = Inventory::where('user_id', $user->id)
            ->where('item_id', $validated['item_id'])
            ->firstOrFail();

        DB::transaction(function () use ($user, $validated) {
            $trade = Trade::create([
                'sender_id'   => $user->id,
                'receiver_id' => $validated['receiver_id'],
                'status'      => 'pending',
            ]);

            TradeItem::create([
                'trade_id'     => $trade->id,
                'item_id'      => $validated['item_id'],
                'from_user_id' => $user->id,
                'quantity'     => 1,
            ]);

            // US22: notificatie voor ontvanger
            Notification::send(
                (int) $validated['receiver_id'],
                "Je hebt een ruilverzoek ontvangen van {$user->username}."
            );
        });

        return redirect()->route('trades.index')
            ->with('success', 'Ruilverzoek verstuurd!');
    }

    // US20: trade accepteren – items wisselen van eigenaar
    public function accept(Trade $trade)
    {
        $user = Auth::user();

        abort_if($trade->receiver_id !== $user->id, 403);
        abort_if(! $trade->isPending(), 403);

        DB::transaction(function () use ($trade, $user) {
            foreach ($trade->tradeItems as $tradeItem) {
                // Verwijder item uit inventaris van verzender
                $senderEntry = Inventory::where('user_id', $tradeItem->from_user_id)
                    ->where('item_id', $tradeItem->item_id)
                    ->first();

                if ($senderEntry) {
                    if ($senderEntry->quantity > $tradeItem->quantity) {
                        $senderEntry->decrement('quantity', $tradeItem->quantity);
                    } else {
                        $senderEntry->delete();
                    }
                }

                // Voeg item toe aan inventaris van ontvanger
                $receiverEntry = Inventory::where('user_id', $user->id)
                    ->where('item_id', $tradeItem->item_id)
                    ->first();

                if ($receiverEntry) {
                    $receiverEntry->increment('quantity', $tradeItem->quantity);
                } else {
                    Inventory::create([
                        'user_id'  => $user->id,
                        'item_id'  => $tradeItem->item_id,
                        'quantity' => $tradeItem->quantity,
                    ]);
                }
            }

            $trade->update(['status' => 'accepted']);

            // US22: notificatie voor verzender
            Notification::send(
                $trade->sender_id,
                "{$user->username} heeft je ruilverzoek geaccepteerd."
            );
        });

        return redirect()->route('trades.index')
            ->with('success', 'Ruilverzoek geaccepteerd!');
    }

    // US21: trade weigeren
    public function reject(Trade $trade)
    {
        $user = Auth::user();

        abort_if($trade->receiver_id !== $user->id, 403);
        abort_if(! $trade->isPending(), 403);

        $trade->update(['status' => 'rejected']);

        // US22: notificatie voor verzender
        Notification::send(
            $trade->sender_id,
            "{$user->username} heeft je ruilverzoek geweigerd."
        );

        return redirect()->route('trades.index')
            ->with('info', 'Ruilverzoek geweigerd.');
    }
}
