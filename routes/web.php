<?php

use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Item catalog (US10)
    Route::get('items', fn () => view('pages.items.index', [
        'items' => Item::orderBy('type')->orderBy('name')->get(),
    ]))->name('items.index');

    // Item detail (US11)
    Route::get('items/{item}', fn (Item $item) => view('pages.items.show', [
        'item' => $item,
    ]))->name('items.show');

    // Inventory (US15)
    Route::get('inventory', fn () => view('pages.inventory.index', [
        'entries' => Inventory::with('item')->where('user_id', Auth::id())->get(),
    ]))->name('inventory.index');

    // Trades (player)
    Route::view('trades', 'pages.trades.index')->name('trades.index');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // US24 – Items CRUD
        Route::resource('items', AdminItemController::class)->only(['index','create','store','edit','update','destroy']);
        // US23 – Gebruikers aanmaken
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::view('stats', 'pages.admin.stats')->name('stats');
    });
});

require __DIR__.'/settings.php';
