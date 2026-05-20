<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        ];

        return view('dashboard.admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group');

        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $value = $request->input($setting->key);

            // Handle boolean — unchecked checkboxes won't appear in request
            if ($setting->type === 'boolean') {
                $value = $request->has($setting->key) ? '1' : '0';
            }

            // Skip empty encrypted fields — don't overwrite with blank
            if ($setting->type === 'encrypted' && empty($value)) {
                continue;
            }

            $setting->value = $value;
            $setting->save();
        }

        return redirect()->route('admin.settings.index', ['tab' => $group])
            ->with('success', ucfirst($group) . ' settings saved successfully.');
    }
}