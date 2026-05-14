<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'profile'])
            ->latest()
            ->paginate(20);

        return view('dashboard.admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('dashboard.admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        $user->profile()->create([
            'legal_name' => $validated['first_name'] . ' ' . $validated['last_name'],
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'profile', 'properties', 'leases', 'tenants']);

        $managers = User::role('property_manager')->get();

        return view('dashboard.admin.users.show', compact('user', 'managers'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load(['roles', 'profile']);

        return view('dashboard.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|exists:roles,name',
            'password'   => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Sync role
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }

    public function assignTenant(Request $request, User $manager)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:users,id',
        ]);

        $tenant = User::findOrFail($validated['tenant_id']);

        // Check not already assigned
        if ($manager->tenants()->where('tenant_id', $tenant->id)->exists()) {
            return redirect()->back()
                ->with('error', 'Tenant is already assigned to this manager.');
        }

        $manager->tenants()->attach($tenant->id);

        return redirect()->back()
            ->with('success', $tenant->first_name . ' assigned to ' . $manager->first_name . ' successfully.');
    }

    public function removeTenant(User $manager, User $tenant)
    {
        $manager->tenants()->detach($tenant->id);

        return redirect()->back()
            ->with('success', 'Tenant removed from manager.');
    }
}