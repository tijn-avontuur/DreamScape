<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Item catalog (player)
    Route::view('items', 'pages.items.index')->name('items.index');

    // Inventory (player)
    Route::view('inventory', 'pages.inventory.index')->name('inventory.index');

    // Trades (player)
    Route::view('trades', 'pages.trades.index')->name('trades.index');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('items', 'pages.admin.items.index')->name('items.index');
        Route::view('users', 'pages.admin.users.index')->name('users.index');
        Route::view('stats', 'pages.admin.stats')->name('stats');
    });
});

require __DIR__.'/settings.php';
