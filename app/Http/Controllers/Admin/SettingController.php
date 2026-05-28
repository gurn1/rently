<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        $groups = [
            'general'       => 'General',
            'payments'      => 'Payments',
            'leases'        => 'Leases',
            'notifications' => 'Notifications',
            'accounts'      => 'Accounts'
        ];

        return view('dashboard.admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group');
        $settings = Setting::where('group', $group)->get();

        // Handle logo upload separately
        if ($request->hasFile('site_logo_file')) {
            $request->validate([
                'site_logo_file' => 'image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            ]);

            $logoSetting = Setting::where('key', 'site_logo')->first();

            // Delete old logo if exists
            if ($logoSetting?->value) {
                Storage::disk('public')->delete($logoSetting->value);
            }

            $path = $request->file('site_logo_file')->store('logos', 'public');

            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path]
            );
        }

        foreach ($settings as $setting) {
            // Skip logo — handled above
            if ($setting->key === 'site_logo') continue;

            $value = $request->input($setting->key);

            if ($setting->type === 'boolean') {
                $value = $request->has($setting->key) ? '1' : '0';
            }

            if ($setting->type === 'encrypted' && empty($value)) {
                continue;
            }

            $setting->value = $value;
            $setting->save();
        }

        cache()->forget('enable_frontend_registration');

        return redirect()->route('admin.settings.index', ['tab' => $group])
            ->with('success', ucfirst($group) . ' settings saved successfully.');
    }
}