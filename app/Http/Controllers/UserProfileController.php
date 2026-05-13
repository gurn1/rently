<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?? $user->profile()->create([
            'legal_name' => $user->first_name . ' ' . $user->last_name,
        ]);

        return view('dashboard.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name'                     => 'required|string|max:255',
            'last_name'                      => 'required|string|max:255',
            'email'                          => 'required|email|unique:users,email,' . $user->id,
            'legal_name'                     => 'required|string|max:255',
            'preferred_name'                 => 'nullable|string|max:255',
            'phone'                          => 'nullable|string|max:20',
            'address'                        => 'nullable|string|max:255',
            'emergency_contact_name'         => 'nullable|string|max:255',
            'emergency_contact_phone'        => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'profile_image'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Update user
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
        ]);

        // Handle profile image upload
        $profileData = collect($validated)->except([
            'first_name', 'last_name', 'email', 'profile_image'
        ])->toArray();

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile?->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }
            $profileData['profile_image'] = $request->file('profile_image')
                ->store('avatars', 'public');
        }

        // Update or create profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}