<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\PathaoService;
use App\Services\ProductAiImportService;
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
            // Pathao API Configuration
            'pathao_environment',
            'pathao_client_id',
            'pathao_client_secret',
            'pathao_client_email',
            'pathao_client_password',
            'pathao_store_id',
            // Google Gemini AI Key
            'gemini_api_key',
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

        return back()->with('success', 'Store, courier, and AI settings updated successfully.');
    }

    public function testGemini(Request $request, ProductAiImportService $aiService)
    {
        $key = $request->input('gemini_api_key', Setting::get('gemini_api_key'));
        $result = $aiService->testConnection($key);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    public function testPathao(Request $request, PathaoService $pathaoService)
    {
        $credentials = [
            'environment' => $request->input('pathao_environment', Setting::get('pathao_environment', 'sandbox')),
            'client_id' => $request->input('pathao_client_id', Setting::get('pathao_client_id')),
            'client_secret' => $request->input('pathao_client_secret', Setting::get('pathao_client_secret')),
            'email' => $request->input('pathao_client_email', Setting::get('pathao_client_email')),
            'password' => $request->input('pathao_client_password', Setting::get('pathao_client_password')),
        ];

        $result = $pathaoService->testConnection($credentials);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
