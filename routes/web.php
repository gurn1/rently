<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Manager\PropertyController as ManagerPropertyController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('properties', PropertyController::class)->only(['index', 'show']);

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

/** Breeze profile routes */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/**
 * Tenant routes
 * Add 'verified' to middleware array to enable email verification
 */
Route::middleware(['auth', 'role:tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.tenant.dashboard');
        })->name('dashboard');
    });

/**
 * Property Manager routes
 * Add 'verified' to middleware array to enable email verification
 */
Route::middleware(['auth', 'role:property_manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.manager.dashboard');
        })->name('dashboard');

        Route::resource('properties', ManagerPropertyController::class)
            ->names([
                'index'   => 'properties.index',
                'create'  => 'properties.create',
                'store'   => 'properties.store',
                'show'    => 'properties.show',
                'edit'    => 'properties.edit',
                'update'  => 'properties.update',
                'destroy' => 'properties.destroy',
            ]);
    });

/**
 * Admin routes
 * Add 'verified' to middleware array to enable email verification
 */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin.dashboard');
        })->name('dashboard');

        Route::resource('properties', AdminPropertyController::class)
            ->names([
                'index'   => 'properties.index',
                'create'  => 'properties.create',
                'store'   => 'properties.store',
                'show'    => 'properties.show',
                'edit'    => 'properties.edit',
                'update'  => 'properties.update',
                'destroy' => 'properties.destroy',
            ]);
    });