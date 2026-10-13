<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name',
            'site_tagline',
            'phone',
            'email',
            'address',
            'announcement_1',
            'announcement_2',
            'announcement_3',
            'delivery_inside_dhaka',
            'delivery_outside_dhaka',
            'free_delivery_threshold',
            'facebook_url',
            'instagram_url',
            'logo',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        if ($request->hasFile('logo_file')) {
            $logoPath = $request->file('logo_file')->store('settings', 'public');
            Setting::set('logo', asset('storage/' . $logoPath));
        }

        return back()->with('success', 'Store settings updated successfully.');
    }
}
