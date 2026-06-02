<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = auth()->user()->tenants;

        return view('dashboard.manager.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if(!auth()->user()->tenants->contains($user->id)) {
            abort(403);
        }

        $user->load(['roles', 'profile', 'leases']);

        return view('dashboard.manager.users.show', compact('user'));
    }

}
