<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LostItemController;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\AiMatchingController;

Route::get('/', function () {

    $lostCount = LostItem::count();

    $foundCount = FoundItem::count();

    return view(
        'welcome',
        compact(
            'lostCount',
            'foundCount'
        )
    );

});

Route::get('/dashboard', function () {

    $lostCount = LostItem::count();

    $foundCount = FoundItem::count();

    $lostItems = LostItem::latest()
        ->take(5)
        ->get();

    return view(
        'dashboard',
        compact(
            'lostCount',
            'foundCount',
            'lostItems'
        )
    );

})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::resource(
        'lost-items',
        LostItemController::class
    );

});

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/lost-items/{lostItem}/ai-matches',
        [AiMatchingController::class, 'show']
    )->name('lost-items.ai-matches');

});

Route::middleware(['auth'])->group(function () {

    Route::resource(
        'found-items',
        FoundItemController::class
    );

});

Route::middleware(['auth'])->group(function () {
    // Submit a claim for a found item
    Route::post('/found-items/{foundItem}/claims',
        [ClaimController::class, 'store']
    )->name('claims.store');

    // View claims submitted by the logged-in user
    Route::get('/my-claims',
        [ClaimController::class, 'myClaims']
    )->name('my-claims.index');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin claim management
        Route::get('/claims',
            [ClaimController::class, 'adminIndex']
        )->name('claims.index');

        // Approve a claim
        Route::patch('/claims/{claim}/approve',
            [ClaimController::class, 'approve']
        )->name('claims.approve');

        // Reject a claim
        Route::patch('/claims/{claim}/reject',
            [ClaimController::class, 'reject']
        )->name('claims.reject');

        // View ownership proof
        Route::get('/claims/{claim}/proof',
            [ClaimController::class, 'proof']
        )->name('claims.proof');
    });

