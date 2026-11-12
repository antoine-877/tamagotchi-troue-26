<?php

use App\Http\Controllers\CreatureController;
use App\Http\Controllers\EnclosureController;
use App\Http\Controllers\GiftCodeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [CreatureController::class, 'index'])->name('dashboard');

    Route::resource('creatures', CreatureController::class)->except(['index', 'show']);
    Route::post('creatures/{creature}/jouer', [CreatureController::class, 'play'])->name('creatures.play');
    Route::post('creatures/{creature}/dormir', [CreatureController::class, 'sleep'])->name('creatures.sleep');

    Route::get('enclos/{user}', [EnclosureController::class, 'show'])->name('enclosures.show');
    Route::patch('enclos', [EnclosureController::class, 'update'])->name('enclosures.update');
    Route::post('enclos/{user}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('classement', LeaderboardController::class)->name('leaderboard');

    Route::get('magasin', [ShopController::class, 'index'])->name('shop.index');
    Route::post('magasin/{item}/acheter', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('codes-cadeau', [GiftCodeController::class, 'store'])->name('gift-codes.store');
});

require __DIR__.'/settings.php';
