<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('property_manager')) {
        return redirect()->route('manager.dashboard');
    } elseif ($user->hasRole('tenant')) {
        return redirect()->route('tenant.dashboard');
    }

    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/** Dashboard routes */

/**
 * Tenant route
 * 
 * add 'verified' to middleware array to enable email verfication
 */
Route::middleware(['auth', 'role:tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('tenant.dashboard');
        })->name('dashboard');
});

/**
 * Property Manager route
 * 
 * add 'verified' to middleware array to enable email verfication
 */
Route::middleware(['auth', 'role:property_manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('dashboard');

        Route::resource('properties', PropertyController::class)
            ->except(['index', 'show'])
            ->names('properties');
});

/**
 * Admin route
 * 
 * add 'verified' to middleware array to enable email verfication
 */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('properties', PropertyController::class)
            ->names('properties');
});

/** Listings routes */
// Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
// Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::resource('properties', PropertyController::class)->only(['index', 'show']);