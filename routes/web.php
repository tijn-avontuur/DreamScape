<?php

use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemCatalogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Profiel bekijken (US3)
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');

    // Item catalog (US10, US12, US13, US14)
    Route::get('items', [ItemCatalogController::class, 'index'])->name('items.index');

    // Item detail (US11)
    Route::get('items/{item}', fn (Item $item) => view('pages.items.show', [
        'item' => $item,
    ]))->name('items.show');

    // Inventory (US15, US16, US17)
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Trades (US18-22)
    Route::get('trades', [TradeController::class, 'index'])->name('trades.index');
    Route::get('trades/create', [TradeController::class, 'create'])->name('trades.create');
    Route::post('trades', [TradeController::class, 'store'])->name('trades.store');
    Route::patch('trades/{trade}/accept', [TradeController::class, 'accept'])->name('trades.accept');
    Route::patch('trades/{trade}/reject', [TradeController::class, 'reject'])->name('trades.reject');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // US24 – Items CRUD
        Route::resource('items', AdminItemController::class)->only(['index','create','store','edit','update','destroy']);
        // US25 – Item toekennen
        Route::get('items/{item}/assign', [AdminItemController::class, 'showAssign'])->name('items.assign.show');
        Route::post('items/{item}/assign', [AdminItemController::class, 'assign'])->name('items.assign');
        // US23 – Gebruikers aanmaken
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // US25 – Statistieken
        Route::get('stats', [AdminStatsController::class, 'index'])->name('stats');
    });
});

require __DIR__.'/settings.php';
