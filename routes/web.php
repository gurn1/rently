<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Manager\PropertyController as ManagerPropertyController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Manager\ConversationController as ManagerConversationController;
use App\Http\Controllers\Tenant\ConversationController as TenantConversationController;
use App\Http\Controllers\Manager\WorkOrderController as ManagerWorkOrderController;
use App\Http\Controllers\Tenant\WorkOrderController as TenantWorkOrderController;
use App\Http\Controllers\Admin\WorkOrderController as AdminWorkOrderController;
use App\Http\Controllers\Manager\LeaseController as ManagerLeaseController;
use App\Http\Controllers\Tenant\LeaseController as TenantLeaseController;
use App\Http\Controllers\Admin\LeaseController as AdminLeaseController;
use App\Http\Controllers\Manager\DocumentController as ManagerDocumentController;
use App\Http\Controllers\Tenant\DocumentController as TenantDocumentController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\WorkOrderUpdateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

        Route::resource('messages', TenantConversationController::class)
            ->only(['index', 'show'])
            ->parameters(['messages' => 'conversation'])
            ->names([
                'index' => 'messages.index',
                'show'  => 'messages.show',
            ]);

        Route::post('/messages/{conversation}', [MessageController::class, 'store'])
            ->name('messages.store');

        Route::resource('leases', TenantLeaseController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'leases.index',
                'show'  => 'leases.show',
            ]);

        Route::resource('work-orders', TenantWorkOrderController::class)
            ->only(['index', 'show', 'create', 'store'])
            ->names([
                'index'  => 'work-orders.index',
                'show'   => 'work-orders.show',
                'create' => 'work-orders.create',
                'store'  => 'work-orders.store',
            ]);

        Route::post('/work-orders/{workOrder}/updates', [WorkOrderUpdateController::class, 'store'])
            ->name('work-orders.updates.store');

        Route::resource('documents', TenantDocumentController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'documents.index',
                'show'  => 'documents.show',
            ]);
        Route::post('/documents/{document}/sign', [TenantDocumentController::class, 'sign'])
            ->name('documents.sign');
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

        Route::resource('messages', ManagerConversationController::class)
            ->only(['index', 'show'])
            ->parameters(['messages' => 'conversation'])
            ->names([
                'index' => 'messages.index',
                'show' => 'messages.show'
            ]);

        Route::post('/messages/{conversation}', [MessageController::class, 'store'])
            ->name('messages.store');

        Route::resource('leases', ManagerLeaseController::class)
            ->names([
                'index' => 'leases.index',
                'create'  => 'leases.create',
                'store'   => 'leases.store',
                'show'  => 'leases.show',
            ]);

        Route::resource('work-orders', ManagerWorkOrderController::class)
            ->names([
                'index'   => 'work-orders.index',
                'create'  => 'work-orders.create',
                'store'   => 'work-orders.store',
                'show'    => 'work-orders.show',
                'edit'    => 'work-orders.edit',
                'update'  => 'work-orders.update',
                'destroy' => 'work-orders.destroy',
            ]);

        Route::post('/work-orders/{workOrder}/updates', [WorkOrderUpdateController::class, 'store'])
            ->name('work-orders.updates.store');

        Route::resource('documents', ManagerDocumentController::class)
            ->only(['index', 'show', 'create', 'store', 'destroy'])
            ->names([
                'index'   => 'documents.index',
                'show'    => 'documents.show',
                'create'  => 'documents.create',
                'store'   => 'documents.store',
                'destroy' => 'documents.destroy',
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

        Route::resource('leases', AdminLeaseController::class)
            ->names([
                'index'   => 'leases.index',
                'create'  => 'leases.create',
                'store'   => 'leases.store',
                'show'    => 'leases.show',
                'edit'    => 'leases.edit',
                'update'  => 'leases.update',
                'destroy' => 'leases.destroy',
            ]);

        Route::resource('work-orders', AdminWorkOrderController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'work-orders.index',
                'show'  => 'work-orders.show',
            ]);

        Route::resource('documents', AdminDocumentController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'documents.index',
                'show'  => 'documents.show',
            ]);
    });